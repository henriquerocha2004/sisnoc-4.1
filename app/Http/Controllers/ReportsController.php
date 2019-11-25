<?php

namespace App\Http\Controllers;

use DB;
use Gate;
use App\Exports\Disponibility;
use App\Exports\SpreadSheetExport;
use App\Models\Called;
use App\Models\Establishment;
use App\Models\Links;
use App\Models\SubCaller;
use App\Utils\DateUtils;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ReportsController extends Controller
{

    public function index(){

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        return view('reports.index');
    }

    public function disponibility(Request $request){

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }


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

    public function callersTeleCompany(Request $request){

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

            $dataSource = Links::join('called', 'called.id_link', '=','links.id')
                    ->join('sub_caller', 'sub_caller.id_caller', '=', 'called.id')
                    ->join('establishment', 'establishment.id', 'called.id_establishment')
                    ->where('sub_caller.type', '=', 2)
                    ->where('sub_caller.status', '=', 'open')
                    ->select(DB::raw("distinct(sub_caller.id_caller)"),'establishment.establishment_code',  'called.caller_number', 'links.type_link',
                    'sub_caller.call_telecommunications_company_number', 'sub_caller.deadline');

            if($request->link != 'ALL'){
                $dataSource->where('links.type_link', '=', $request->link);
            }

            $dataSource = $dataSource->get();

            $header = [
                ['Chamados Abertos para a Operadora'],
                ['#', 'Loja', 'Chamado', 'Link', 'Chamado Operadora', 'Prazo de Normalização']];

        return Excel::download(new SpreadSheetExport($dataSource, $header), 'Chamados Abertos Operadora.xlsx');
    }

    public function callersOtrs(Request $request){

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        $dataSource = Links::join('called', 'called.id_link', '=','links.id')
            ->join('sub_caller', 'sub_caller.id_caller', '=', 'called.id')
            ->where('sub_caller.type', '=', 3)
            ->where('sub_caller.status', '=', 'open')
            ->select('called.caller_number', 'links.type_link',
            'sub_caller.otrs')->get();

            $header = [
                ['Chamados Abertos para os Técnicos'],
                [ 'Chamado', 'Link', 'OTRS']];

         return Excel::download(new SpreadSheetExport($dataSource, $header), 'Chamados Abertos Técnicos.xlsx');

    }


    public function semep(Request $request){

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        $dataSource = Links::join('called', 'called.id_link', '=','links.id')
        ->join('sub_caller', 'sub_caller.id_caller', '=', 'called.id')
        ->where('sub_caller.type', '=', 4)
        ->where('sub_caller.status', '=', 'open')
        ->select('called.caller_number', 'links.type_link',
        'sub_caller.sisman')->get();

        $header = [
            ['Chamados Abertos para os Técnicos'],
            [ 'Chamado', 'Link', 'SEMEP']];

       return Excel::download(new SpreadSheetExport($dataSource, $header), 'Chamados Abertos SEMEP.xlsx');
    }

    public function links(){

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        ini_set('max_execution_time', 300);

        $establishments = Establishment::where('establishment_status', '=', 'open')->select('id','establishment_code',
        'address', 'city', 'neighborhood')->get();
        $idsEstablishment = $establishments->pluck('id')->all();

        $links = Links::whereIn('establishment_id', $idsEstablishment)->get();

        $typesLink = $links->unique('type_link')->values()->pluck('type_link')->all();




        foreach($establishments as $establishment){

            foreach($typesLink as $type){

                $search = $links->filter(function($item, $key) use ($establishment, $type){
                    return $item->type_link == $type && $item->establishment_id == $establishment->id;
                });

                $indentification = (!empty($search) ? $search->pluck('link_identification')->first(): 'Não Possui');
                $company = $search->first()->telecommunications_company ?? 'Não Possui';
                $ipMon = $search->first()->monitoring_ip ?? 'Não Possui';
                $localIp = $search->first()->local_ip_router ?? 'Não Possui';

                $titleCompanyOper = 'operadora_'. $type;
                $propertyMonitoring = 'ip_monitoring_'.$type;
                $propertyLocalIp = 'local_ip_'.$type;

                $establishment->$type = $indentification ?? 'Não Possui';
                $establishment->$titleCompanyOper = $company;
                $establishment->$propertyMonitoring = $ipMon;
                $establishment->$propertyLocalIp = $localIp;
            }

            $search = $links->filter(function($item, $key) use ($establishment){
                return $item->establishment_id == $establishment->id;
            })->unique('installed_router_model')->values()->all();

            for($i = 1; $i <= 3; $i++){
                $routeInstalled = (!empty($search[$i]->installed_router_model) ? $search[$i]->installed_router_model : 'Não Possui');
                $propertyName = "roteador_". $i;
                $establishment->$propertyName = $routeInstalled;
                $propertyNameSerial = "serial_router_".$i;
                $establishment->$propertyNameSerial = (!empty($search[$i]->serial_router) ? $search[$i]->serial_router : 'Não Possui');
            }
        }

        $header = [
            ['Relação de Links'],
            ['#', 'Loja', 'Endereço', 'Cidade', 'Bairro']];

            foreach($typesLink as $type){
                $titles[] = $type;
                $titles[] = 'Operadora - '.$type;
                $titles[] = 'Ip Monitoramento - '.$type;
                $titles[] = 'Ip Lan - '.$type;
            }

            $header[1] = array_merge($header[1], $titles);

            for($i = 1; $i <= 3; $i++){
                $titlesRoute[] = 'Roteador - '. $i;
                $titlesRoute[] = 'Serial Roteador - '. $i;
            }

            $header[1] = array_merge($header[1], $titlesRoute);

            return Excel::download(new SpreadSheetExport($establishments, $header), 'Relação de Links.xlsx');

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
