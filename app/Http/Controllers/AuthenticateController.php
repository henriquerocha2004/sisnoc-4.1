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
        $dashBoard['qtd_active_establishment'] = Establishment::where('establishment_status', 'open')->get()->count();

        //Verifica a quantidade de chamados abertos
        $dashBoard['qtd_open_called'] = Called::whereBetween('status', [2,6])->get()->count();

        //Links Ativos
        $links = Links::where('status', '=', 'active')->groupBy('type_link')->get();

        foreach($links as $link){
            $dashBoard['qtd_links_active'][$link->type_link] = Links::where(['status' => 'active', 'type_link' => $link->type_link])->get()->count();
        }

        //Quantidade de chamados abertos por link
        foreach($links as $link){
            $idsLinkType = Links::where('type_link', $link->type_link)->select('id')->get()->pluck('id')->all();
            $dashBoard['qtd_open_called_by_link'][$link->type_link] = Called::whereIn('id_link', $idsLinkType)->whereBetween('status', [2,6])->get()->count();
        }

        //Chamados Abertos no dia Atual
        $dashBoard['called_open_current_date'] = Called::whereBetween('created_at', [date('Y-m-d') . '00:00:00', date('Y-m-d') . '23:59:59'])->get();
        //Chamados Fechados no dia atual
        $dashBoard['called_closed_current_date'] = Called::whereBetween('updated_at', [date('Y-m-d') . '00:00:00', date('Y-m-d') . '23:59:59'])->where('status', 1)->get();

        //Chamados Abertos por responsabilidade
        $responsable = ['Operadora' => 2 , 'Técnico Local' => 3 , 'SEMEP' => 4, 'Inadiplência' => 6, 'Falta de Energia' => 5];

        foreach($responsable as $key => $resp){
            $dashBoard['called_open_by_responsability'][$key]['callers'] = SubCaller::where(['status' => 'open', 'type' => $resp])->get()   ;
            $dashBoard['called_open_by_responsability'][$key]['total'] = $dashBoard['called_open_by_responsability'][$key]['callers']->count();
        }


        return view('home', [
            'dashboard' => $dashBoard
        ]);
    }
}
