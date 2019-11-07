<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function index(){
        return view('users.index');
    }

    public function create(){
        return view('users.create');
    }

    public function store(Users $request){
        dd($request->all());
    }

    public function table()
    {
        $users = User::select(['id', 'name', 'email'])->get();
        return DataTables::of($users)->make(true);
    }
}
