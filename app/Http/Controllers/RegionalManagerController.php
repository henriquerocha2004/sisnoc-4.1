<?php

namespace App\Http\Controllers;

use DB;
use Gate;
use App\Models\Establishment;
use App\Models\RegionalManager;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\RegionalManagerRequest;
use App\Utils\Utils;
use Exception;

class RegionalManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        return view('regionalManager.index');
    }

    /**
     * Generate dataTables at index
     */
    public function table()
    {

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        $regionalManagers = RegionalManager::select(['id', 'email', 'name', 'contact', 'status'])->where('id', '<>', 1);
        return DataTables::of($regionalManagers)->make(true);
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

        $establishments = Establishment::select(['id', 'establishment_code', 'state'])->orderBy('establishment_code')->get();

        return view('regionalManager.create', [
            'establishments' => $establishments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegionalManagerRequest $request)
    {

        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }

        DB::beginTransaction();

        try {

            $regionalManager = new RegionalManager();

            //First, Verify if exists anymore register in table for create a default manager
            $countRegional = count(RegionalManager::all());

            if ($countRegional <= 0) {
                $createDefaultRegional = $regionalManager->createDefaultRegional();
                if (!$createDefaultRegional) {
                    throw new Exception("Falha geral!");
                }
            }


            $regionalManager->fill($request->all());
            $regionalManager->status = 'active';

            if (!$regionalManager->save()) {
                throw new Exception("Houve uma Falha ao cadastrar os dados");
            }

            if(!empty($request->establishment_code)){
                $establishmentsUpdate = Establishment::whereIn('id', $request->establishment_code);
                $establishmentsUpdate->update(['regional_manager_code' => $regionalManager->id]);

                if (!$establishmentsUpdate) {
                    throw new Exception("Houve uma Falha ao cadastrar os dados");
                }
            }

            DB::commit();

            return redirect()->route('regionalManager.index')->with('alert', ['messageType' => 'success', 'message' => 'Gerente Regional cadastrado com sucesso!']);
        } catch (Exception $e) {

            DB::rollback();
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => 'Falha ao salvar o regional']);
        }
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


        $regionalManager = RegionalManager::with('establishments')->find($id);
        $establishments = Establishment::select(['id', 'establishment_code', 'state'])->where(['establishment_status' => 'open'])->orderBy('establishment_code')->get();

        return view('regionalManager.edit', [
            'regionalManager' => $regionalManager,
            'establishments' => $establishments
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RegionalManagerRequest $request, $id)
    {
        if(Gate::denies('manager-establishment-regionalManager-links-caller-create-reports')){
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Ops! Você não está autorizado a acessar esse recurso!']);
        }
        DB::beginTransaction();

        try {
            $regionalManager = RegionalManager::find($id);
            $regionalManager->fill($request->all());

            if (!$regionalManager->save()) {
                throw new Exception("Houve uma Falha ao atualizar os dados");
            }

            $idsEstalishmentDB = $regionalManager->idEstablishments();

            $compareNewOld = Utils::getDataOfArraysByComparing($idsEstalishmentDB, $request->establishment_code);

            if ($compareNewOld['delete']) {
                foreach ($compareNewOld['delete'] as $idDeleted) {
                    $estblishmentRemove = Establishment::find($idDeleted);
                    $estblishmentRemove->update(['regional_manager_code' => 1]);

                    if (!$estblishmentRemove) {
                        throw new Exception("Houve uma Falha ao remover os estabelecimentos associados");
                    }
                }
            }

            if ($compareNewOld['insert']) {
                foreach ($compareNewOld['insert'] as $idInserted) {
                    $estblishmentInsert = Establishment::find($idInserted);
                    $estblishmentInsert->update(['regional_manager_code' => $regionalManager->id]);

                    if (!$estblishmentInsert) {
                        throw new Exception("Houve uma Falha ao atualizar os novos estabelecimentos associados");
                    }
                }
            }


            DB::commit();

            return redirect()->route('regionalManager.index')->with('alert', ['messageType' => 'success', 'message' => 'Gerente Regional atualizado com sucesso!']);
        } catch (Exception $e) {
             dd($e->getMessage());

            DB::rollback();
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => 'Falha ao atualizar o regional']);
        }
    }
}
