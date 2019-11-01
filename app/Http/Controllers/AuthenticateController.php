<?php

namespace App\Http\Controllers;

use App\Models\Called;
use App\Models\Establishment;
use App\Models\Links;
use App\Models\SubCaller;
use App\Models\User;
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
        $dashBoard = null;

        //Verifica quantos estabelecimentos estão ativos
        $dashBoard['qtd_active_establishment'] = count(Establishment::where('establishment_status', 'open')->get());

        //Verifica a quantidade de chamados abertos
        $dashBoard['qtd_open_called'] = count(Called::whereBetween('status', [2,6])->get());

        //Links Ativos
        $links = Links::where('status', '=', 'active')->groupBy('type_link')->get();

        foreach($links as $link){
            $dashBoard['qtd_links_active'][$link->type_link] = count(Links::where(['status' => 'active', 'type_link' => $link->type_link])->get());
        }

        //Quantidade de chamados abertos por link
        foreach($links as $link){
            $idsLinkType = Links::where('type_link', $link->type_link)->select('id')->get()->pluck('id')->all();
            $dashBoard['qtd_open_called_by_link'][$link->type_link] = count(Called::whereIn('id_link', $idsLinkType)->whereBetween('status', [2,6])->get());
        }

        //Chamados Abertos no dia Atual
        $dashBoard['called_open_current_date'] = Called::whereBetween('created_at', [date('Y-m-d') . '00:00:00', date('Y-m-d') . '23:59:59'])->get();
        //Chamados Fechados no dia atual
        $dashBoard['called_closed_current_date'] = Called::whereBetween('updated_at', [date('Y-m-d') . '00:00:00', date('Y-m-d') . '23:59:59'])->where('status', 1)->get();

        //Chamados Abertos por responsabilidade
        $responsable = ['telecomunications_company' => 2 , 'technical' => 3 , 'semep' => 4, 'financial_default' => 6, 'energy_fault' => 5];

        foreach($responsable as $key => $resp){
            $dashBoard['called_open_by_responsability'][$key] = SubCaller::where(['type' => $resp, 'status' => 'open'])->orderBy('id', 'DESC')->first()->called();
        }





        dd($dashBoard);



        return view('home');
    }
}
