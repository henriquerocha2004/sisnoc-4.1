<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Establishment;
use Exception;
use Gate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ConfigController extends Controller
{

    public function index(){

        if(Gate::denies('config-authorization')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        $config = Config::first();

        return view('config.index', [
            'config' => $config
        ]);
    }

    public function update(Request $request){

        if(Gate::denies('config-authorization')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        try {
            $config = Config::first();
            $config->fill($request->all());
            $config->save();
            return redirect()->route('config.index')->with('alert', ['messageType' => 'success', 'message' => 'Ajustes efetuados com sucesso!']);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao salvar as configurações']);
        }
    }

    public function holyDayManager(){
        return view('config.holydayManager.index');
    }

    public function holyDayTable(){
        $establishment = Establishment::where('holyday', '=', date('Y-m-d'));
        return DataTables::of($establishment)->make(true);
    }

    public function removeHolyday($id){
        try {
            $establishment = Establishment::find($id);
            $establishment->holyday = '';
            $establishment->save();
            return  response()->json(['result' => true, 'message' => 'Operação feita com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['result' => false, 'message' => 'Houve uma falha ao alterar!']);
        }

    }

    public function updateSystem(){
        return view('config.updateSystem.index');
    }

    public static function checkUpdate(){

      $remote  = (int) shell_exec("cd C:\\xampp\\htdocs\\sisnoc && git rev-list  --remotes --count");
      $local = (int) shell_exec("cd C:\\xampp\\htdocs\\sisnoc && git rev-list --all --count");
      $lastCommitInfo = null;

      if(!empty($remote) && !empty($local)){
          if($remote < $local){
            exec("cd C:\\xampp\\htdocs\\sisnoc && git rev-list  --remotes --pretty --max-count=1", $lastCommitInfo);
            $lastCommitInfo = array_filter($lastCommitInfo);
          }
      }

      


    }


}
