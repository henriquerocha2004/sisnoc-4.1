<?php

namespace App\Http\Controllers;

use App\Exports\Disponibility;
use App\Models\Called;
use App\Models\Links;
use App\Utils\DateUtils;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function index(){
        return view('reports.index');
    }

    public function disponibility(Request $request){

        ini_set('max_execution_time', 300);

       $typeLinks = Links::distinct()->select('type_link')->get()->pluck('type_link')->all();
       $start = DateUtils::convertDataDataBase($request->start .' 00:00:00', true);
       $end = DateUtils::convertDataDataBase($request->end . ' 23:59:59', true);

       $calleds = Called::whereBetween('created_at', [$start, $end])
                     ->where('status', '!=', 7)->get();

       if(count($calleds) < 1){
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => 'Não foram encontrados resultados!']);
       }


       foreach($calleds as $key => $called)
       {

          $time = explode(' ', $called->created_at);

          $data[$key]['Data'] = DateUtils::convertDataToBR($time[0]);
          $data[$key]['Hora'] = $time[1];
          $data[$key]['Filial'] = $called->establishment()->first()->establishment_code;

          foreach($typeLinks as $typeLink){
              $data[$key][$typeLink] = $this->checkOnOff($typeLink, $called);
          }

          $data[$key]['Operacional'] = ($called->status == 1 ? 'NÃO' : 'SIM');
          $data[$key]['workTime'] = ($called->status == 1 ? $called->work_downtime : DateUtils::calcWorkDowntime(date('Y-m-d H:i:s'), $called->hr_down, DateUtils::diffTime($called->hr_down, date('Y-m-d H:i:s'), 'hour') . ":00:00") );
          $data[$key]['Responsabilidade'] = ($called->status == 1 ? $called->causeProblem()->first()->description_cause : 'Não Definido - Chamado Aberto');
          $data[$key]['Tempo Indisponível'] = ($called->status == 1 ? $called->downtime : DateUtils::diffTime($called->hr_down, date('Y-m-d H:i:s'), 'hour') . ":00:00" );
          $data[$key]['Chamado Sisnoc'] = $called->caller_number;
          $data[$key]['Operadora'] = $called->link()->first()->telecommunications_company;
       }

       $dataSource = collect($data);


       $header = [
        ['Disponiblildade e Interrupções'],
        ["Período: {$request->start} a {$request->end}"],
        [ 'Data', 'Hora', 'Filial']
       ];

       $header[2] = array_merge($header[2], $typeLinks);

       array_push($header[2], 'Operacional', 'Tempo indisp. durante o expediente*', 'Responsabilidade',
       'Tempo Indisponível', 'Chamado SISNOC', 'Operadora');

       return Excel::download(new Disponibility($dataSource, $header), 'Disponibilidade e Interrupções.xlsx');
    }

    public function links(){
        ini_set('max_execution_time', 300);
    }

    private function checkOnOff($currentLink, $called){

        $isInoperant = $called->subCallers()->orderBy('id', 'DESC')->first()->typeProblem()->where(['id_problem_type' => 1])->exists();
        $result = null;

        if($currentLink == $called->link()->first()->type_link && $isInoperant == true){
            $result = 'OFF';
        }else{
            $result = 'ON';
        }

        if($currentLink != $called->link()->first()->type_link){

            $link = Links::where(['type_link' => $currentLink, 'establishment_id' => $called->id_establishment])->get();

            if(count($link) > 0){

                if($called->status == 1){
                    $result = 'OFF';
                }else{
                    $result = 'ON';
                }

            }else{
                $result = 'Não Possui';
            }

        }

       return $result;

    }
}
