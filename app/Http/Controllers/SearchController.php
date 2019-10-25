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
       $this->searchByEstablishment();

        if((empty($this->establishment) || count($this->establishment) == 0 ) && (empty($this->called) || count($this->called) == 0)){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Não Encontramos resultados com o termo ultilizado']);
        }else{

            return view('search.search', [
                'term' => $this->term,
                'establishments' => $this->establishment,
                'calleds' => $this->called
            ]);
        }

    }

    private function searchByEstablishment(){

        $this->establishment = Establishment::whereRaw("MATCH (establishment_code, address, neighborhood, city, state, manager_name) against('{$this->term}')")
                                ->select(['id', 'establishment_code', 'address', 'neighborhood', 'city', 'state', 'manager_name'])->take(20)->get();

        if(count($this->establishment) == 0){
            $this->searchByLink();
        }
    }

    private function searchByLink(){

        $links = Links::whereRaw("MATCH (link_identification, monitoring_ip, local_ip_router) AGAINST ('{$this->term}')")->first();

        if(!empty($links->id)){
            $this->establishment = $links->establishment()->get();
            $this->called = $links->called()->get();
        }else{
            $this->searchByCalled();
        }
    }

    private function searchByCalled(){

        $this->called = Called::where(['caller_number' => $this->term])->first();

        if(!empty($this->called->id)){
            $this->establishment = $this->called->establishment()->get();
            $this->called = Called::where(['caller_number' => $this->term])->get();
        }

    }
}
