<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Exception;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index(){

        $config = Config::first();

        return view('config.index', [
            'config' => $config
        ]);
    }

    public function update(Request $request){
        try {
            $config = Config::first();
            $config->fill($request->all());
            $config->save();
            return redirect()->route('config.index')->with('alert', ['messageType' => 'success', 'message' => 'Ajustes efetuados com sucesso!']);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao salvar as configurações']);
        }
    }
}
