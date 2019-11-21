<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstablishmentRequest;
use App\Models\Called;
use App\Models\Establishment;
use App\Models\Links;
use App\Models\NotesEstablishment;
use App\Models\RegionalManager;
use App\Models\TechnicalManager;
use App\Utils\NetWork;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Gate;

class EstabilishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('establishment.index');
    }

    /**
     * Generate dataTables no index
     */
    public function table()
    {
        $establishment = Establishment::select(['id', 'establishment_code', 'city', 'state', 'neighborhood']);
        return DataTables::of($establishment)->make(true);
    }

    public function tableEstablilishmentCalled(Request $request){

        $calleds = Called::join('establishment', 'called.id_establishment', '=', 'establishment.id')
            ->join('links', 'called.id_link', '=', 'links.id')
            ->join('users', 'called.id_user_open', '=', 'users.id')
            ->select(
                [
                    'called.id',
                    'called.caller_number',
                    'links.type_link',
                    'called.next_action',
                    'called.status',
                    'users.name'
                ]
            )->where(['establishment.id' => $request->id])->orderBy('called.status', 'DESC');
        return DataTables::of($calleds)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        $regionalManagers = RegionalManager::select(['id', 'name'])->get();
        $technicalManagers = TechnicalManager::select(['id', 'name'])->get();

        return view('establishment.create', [
            'regionalManagers' => $regionalManagers,
            'technicalManagers' => $technicalManagers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstablishmentRequest $request)
    {

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        try {
            $establishment = new Establishment();
            $establishment->fill($request->all());
            $establishment->id_user = auth()->user()->id;

            if(!$establishment->save()){
              throw new Exception("Houve uma falha ao salvar o estabelecimento");
            }

            return redirect()->route('estabilishment.index')->with('alert', ['messageType' => 'success', 'message' => 'Estabelecimento Cadastrado com sucesso!']);

        } catch (Exception $e) {
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => 'Falha ao salvar o estabelecimento']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $establishment = Establishment::with(['links', 'notesEstablishment'])->find($id);
        $notes = $establishment->notesEstablishment()->where('validate', '>=', date('Y-m-d'))->get();

        return view('establishment.show', [
            'establishment' => $establishment,
            'notes' => $notes
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        $establishment = Establishment::find($id);
        $regionalManagers = RegionalManager::select(['id', 'name'])->get();
        $technicalManagers = TechnicalManager::select(['id', 'name'])->get();

        return view('establishment.edit', [
            'establishment' => $establishment,
            'regionalManagers' => $regionalManagers,
            'technicalManagers' => $technicalManagers
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EstablishmentRequest $request, $id)
    {

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }


      DB::beginTransaction();

        try {

            $establishment = Establishment::find($id);
            $establishment->fill($request->all());

            if(!$establishment->save()){
               throw new Exception("Houve uma Falha ao atualizar os dados");
            }

            if($establishment->establishment_status = 'close'){
                $this->closeEstablishmentRoutine($establishment);
            }

            DB::commit();
            return redirect()->route('estabilishment.index')->with('alert', ['messageType' => 'success', 'message' => 'Estabelecimento Atualizado com sucesso!']);

        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => 'Falha ao atualizar o estabelecimento']);
        }
    }

    public function holyday(int $id){

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        try {
            $establishment = Establishment::find($id);
            $establishment->holyday = date('Y-m-d');
            $establishment->save();

          return response()->json(['result' => true, 'message' => 'Ação Realizada com sucesso!']);

        } catch (Exception $e) {
           return resposne()->json(['result' => false, 'Houve uma falha ao realizar a ação!']);
        }
    }

    public function restartTerminal(){
        exec("cd ".session('config')['path_web_terminal']." && node terminalWeb.js &", $o);
    }

    public function checkActiveProcessTerminal(){

        $r = ['result' => false];
        exec("ps -ef | grep -o terminalWeb.js", $out);
        $qtdProcess = count($out);

        if($qtdProcess >= 3){
            $r['result'] = true;
        }

        return  response()->json($r);
    }

    public function pingTest(Request $request){
        $link = Links::find($request->idLink);
        $testPing = NetWork::testePing($link->monitoring_ip, $link->type_link);

        $r = [
           'testResults' => json_decode($testPing),
           'link' => $link
        ];

       return response()->json($r);
    }

    public function notesEstablishment(Request $request){

        $request->validate([
            'desc' => 'required',
            'validate' => 'required'
        ]);

        $note = new NotesEstablishment();
        $note->desc = $request->desc;
        $note->validate = $request->validate;
        $note->id_establishment = $request->id_establishment;
        $note->user_id = auth()->user()->id;

        try {
            $note->save();
            return redirect()->route('estabilishment.show', $request->id_establishment)->with('alert', ['messageType' => 'success', 'message' => 'Nota Inserida com sucesso!']);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao salvar a nota!']);
        }

    }

    private function closeEstablishmentRoutine(Establishment $establishment){
        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        try {
            //Fechar os chamados Abertos
            $establishment->calls()->update(['status' => 1, 'id_user_close' => auth()->user()->id]);

            //Fechar Subchamados
            foreach($establishment->calls()->get() as $call){
                $call->subCallers()->update(['status' => 'close']);
            }
            //Inativar links
            $establishment->links()->update(['status' => 'inactive', 'local_ip_router' => '0.0.0.0', 'monitoring_ip' => '0.0.0.0']);

        } catch (Exception $e) {
           throw new Exception("Houve uma falha na rotina de fechamento do estabelecimento!");
        }

    }
}
