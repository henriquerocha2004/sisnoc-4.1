<?php

namespace App\Http\Controllers;

use App\Models\Called;
use App\Models\Establishment;
use App\Models\Links;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    private $term;
    private $establishment;
    private $called;

    public function search(Request $request){

       $this->term = $request->term;

       if($this->verifyIp()){
           $this->searchByLink();
       }else{
           $this->searchByEstablishment();
       }

        if((empty($this->establishment) || count($this->establishment) == 0 ) && (empty($this->called) || count($this->called) == 0)){
            return redirect()->route('home')->with('alert', ['messageType' => 'danger', 'message' => 'Não Encontramos resultados com o termo ultilizado']);
        }else{
            return view('search.search', [
                'term' => $this->term,
                'establishments' => $this->establishment,
                'calleds' => $this->called
            ]);
        }
    }

    private function searchByEstablishment(): void{

        $this->establishment = Establishment::whereRaw("MATCH (establishment_code, address, neighborhood, city, state, manager_name) against('{$this->term}')")
                                ->select(['id', 'establishment_code', 'address', 'neighborhood', 'city', 'state', 'manager_name',
                                    'establishment_status', 'holyday', 'energy_fault'])->take(20)->get();

        if(count($this->establishment) == 0){
            $this->searchByLink();
        }
    }

    private function searchByLink() :void{
        $links = Links::where(['link_identification' => $this->term])
                 ->orWhere(['monitoring_ip' => $this->term])
                 ->orWhere(['local_ip_router' => $this->term])->first();

        if(!empty($links->id)){
            $this->establishment = $links->establishment()->get();
            $this->called = $links->called()->orderBy('id', 'DESC')->get();
        }else{
            $this->searchByCalled();
        }
    }

    private function searchByCalled():void{

        $this->called = Called::where(['caller_number' => $this->term])->first();

        if(!empty($this->called->id)){
            $this->establishment = $this->called->establishment()->get();
            $this->called = Called::where(['caller_number' => $this->term])->get();
        }

    }

    private function verifyIp(): bool {
        return filter_var($this->term, FILTER_VALIDATE_IP);
    }

}
