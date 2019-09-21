<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $user = $request->only(['email', 'password']);

        if (!empty($user['email']) && !empty($user['password'])) {

            $auth = Auth::attempt($user);

            if (!$auth) {
                return back()->with('alert', ['messageType' => 'danger', 'message' => 'Usuário ou Senha inválidos!']);
            } else {
                return redirect()->route('home');
            }
        } else {
            return back()->with('alert', ['messageType' => 'warning', 'message' => 'Informe os dados para entrar!']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }


    public function home()
    {
        return view('home');
    }
}
