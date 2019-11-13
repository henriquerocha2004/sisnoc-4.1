<?php

namespace App\Http\Controllers;

use App\Models\Called;
use App\Models\Config;
use App\Models\Establishment;
use App\Models\Links;
use App\Models\SubCaller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function index()
    {
        $users = User::all()->count();

        if($users <= 0){
            $this->createAdmin();
        }

        $config = Config::first();

        if(!$config){
            $config = new Config();
            $config->send_email = 0;
            $config->path_web_terminal  = '/var/www/html/sisnoc/terminal_web/';
            $config->save();
        }

        session(['config' => $config]);
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $user = $request->only(['email', 'password']);
        if (!empty($user['email']) && !empty($user['password'])) {

            if($request->ad_integration == 'on'){
               return  $this->ldapAuthenticate($user);
            }

            $auth = Auth::attempt($user);
            if (!$auth) {
                return back()->with('alert', ['messageType' => 'danger', 'message' => 'Usuário ou Senha inválidos!']);
            } else {
                return redirect()->route('home');
            }
        } else {
            return back()->with('alert', ['messageType' => 'warning', 'message' => 'Informe os dados para entrar!']);

}    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function home()
    {
        $dashBoard = null;

        //Verifica quantos estabelecimentos estão ativos
        $dashBoard['qtd_active_establishment'] = Establishment::select('id')->where('establishment_status', 'open')->get()->count();

        //Verifica a quantidade de chamados abertos
        $dashBoard['qtd_open_called'] = Called::select('id')->whereBetween('status', [2,6])->get()->count();

        //Typos de links
        $dashBoard['typeLinks'] = Links::distinct()->get(['type_link'])->pluck('type_link')->all();

        //Links Ativos
        $links = Links::where('status', '=', 'active')->groupBy('type_link')->get();

        foreach($links as $link){
            $dashBoard['qtd_links_active'][$link->type_link] = Links::where(['status' => 'active', 'type_link' => $link->type_link])->get()->count();
        }

        //Quantidade de chamados abertos por link
        foreach($links as $link){
            $idsLinkType = Links::where('type_link', $link->type_link)->select('id')->get()->pluck('id')->all();
            $dashBoard['qtd_open_called_by_link'][$link->type_link] = Called::select('id')->whereIn('id_link', $idsLinkType)->whereBetween('status', [2,6])->get()->count();
        }

        //Chamados Abertos no dia Atual
        $dashBoard['called_open_current_date'] = Called::whereBetween('created_at', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'])->whereBetween('status', [2,6])->orderBy('created_at', 'DESC')->get();
        //Chamados Fechados no dia atual
        $dashBoard['called_closed_current_date'] = Called::whereBetween('updated_at', [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'])->where('status', 1)->orderBy('created_at', 'DESC')->get();
        //Chamados Abertos por responsabilidade
        $responsable = ['Operadora' => 2 , 'Técnico Local' => 3 , 'SEMEP' => 4, 'Inadiplência' => 6, 'Falta de Energia' => 5];

        foreach($responsable as $key => $resp){
            $dashBoard['called_open_by_responsability'][$key]['callers'] = SubCaller::where(['status' => 'open', 'type' => $resp])->orderby('created_at', 'ASC')->get();
            $dashBoard['called_open_by_responsability'][$key]['total'] = $dashBoard['called_open_by_responsability'][$key]['callers']->count();
        }

        //Chamados Abertos pelo usuário logado
        $dashBoard['my_callers'] = Called::where(['id_user_open' => Auth::user()->id])->whereBetween('status', [2,6])->orderby('created_at', 'ASC')->get();

        return view('home', [
            'dashboard' => $dashBoard
        ]);
    }

    private function ldapAuthenticate($user){

        $server = env('DOMAIN_SERVER_AD');
        $domain = env('DOMAIN_AD');

        $ldapConn = ldap_connect($server);
        $Username = $domain . "\\" . $user['email'];
        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldapConn, $Username, $user['password']);

        if($bind){
            $filtro = "(sAMAccountName={$user['email']})";
            $result = ldap_search($ldapConn, "dc={$domain},dc=CORP", $filtro);
            $info = ldap_get_entries($ldapConn, $result);
            $authorization = false;

            foreach ($info[0]['memberof'] as $permission){
                if (preg_match('/CN=NOC/', $permission) || preg_match('/CN=G_Acesso_SISNOC/', $permission)){
                   $authorization = true;
                   break;
                }
            }

            if($authorization == true){
                $userDB = User::where(['email' => $user['email']])->first();
                if($userDB == null){
                    $newUser = new User();
                    $newUser->name = $info[0]['cn'][0];
                    $newUser->email = $user['email'];
                    $newUser->password = $user['password'];
                    $newUser->ad_user = 1;
                    $newUser->permission = (preg_match('/CN=NOC/', $permission) ? 2 : 3);
                    try {
                        $newUser->save();
                        $auth = Auth::attempt($user);

                        if($auth){
                            return redirect()->route('home');
                        }else{
                            throw new Exception("Falha ao autenticar o usuário");
                        }

                    } catch (Exception $e) {
                       redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Falha ao autenticar o usuário!']);
                    }
                }else{
                    if(!password_verify($user['password'], $userDB->password)){
                        $userDB->password = $user['password'];
                        $userDB->save();
                    }

                    $auth = Auth::attempt($user);
                    if($auth){
                        return redirect()->route('home');
                    }else{
                        throw new Exception("Falha ao autenticar o usuário");
                    }
                }
            }
        }else{
            return back()->with('alert', ['messageType' => 'danger', 'message' => 'Usuário ou Senha inválidos!']);
        }
    }

    private function createAdmin(){
        $user = new User();
        $user->name = 'sisnoc';
        $user->email = 'admin';
        $user->password = 'sisnoc';
        $user->permission = 1;
        $user->save();
    }

}
