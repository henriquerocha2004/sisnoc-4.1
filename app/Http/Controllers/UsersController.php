<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\Util\Exception;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function index(){

        if(Gate::denies('config-authorization')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        return view('users.index');
    }

    public function create(){

        if(Gate::denies('config-authorization')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        return view('users.create');
    }

    public function store(Users $request){

        if(Gate::denies('config-authorization')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->permission = $request->permission;

        try {
            $user->save();
            return redirect()->route('users.index')->with('alert', ['messageType' => 'success', 'message' => 'Usuário Cadastrado com sucesso!']);
        } catch (Exception $e) {
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => 'Houve um erro ao cadastrar o usuário!']);
        }
    }

    public function edit($id){

        if(Gate::denies('config-authorization')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }


        $user = User::find($id);

        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function update(Users $request, $id){

        if(Gate::denies('config-authorization')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }


        $user = User::find($id);
        $user->name = $request->name;
        $user->permission = $request->permission;

        if(!empty($request->password)){
            $user->password = $request->password;
        }

        try {
            $user->save();
            return redirect()->route('users.index')->with('alert', ['messageType' => 'success', 'message' => 'Usuário Atualizado com sucesso!']);

        } catch (Exception $e) {
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => 'Houve um erro ao atualizar o usuário!']);
        }
    }

    public function table()
    {

        if(Gate::denies('config-authorization')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        $users = User::select(['id', 'name', 'email'])->get();
        return DataTables::of($users)->make(true);
    }
}
