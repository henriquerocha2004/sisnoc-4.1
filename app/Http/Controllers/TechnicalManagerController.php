<?php

namespace App\Http\Controllers;

use App\Http\Requests\TechnicalManagerRequest;
use DB;
use App\Models\Establishment;
use App\Models\TechnicalManager;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TechnicalManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('technicalManager.index');
    }

    public function table()
    {
        $technicalManagers = TechnicalManager::select(['id', 'email', 'name', 'contact', 'status'])->where('id', '<>', 1);
        return DataTables::of($technicalManagers)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $establishments = Establishment::select(['id', 'establishment_code', 'state'])->orderBy('establishment_code')->get();

        return view('technicalManager.create', [
            'establishments' => $establishments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $technicalManager = new TechnicalManager();

            //First, Verify if exists anymore register in table for create a default manager
            $countTecnical = count(TechnicalManager::all());

            if ($countTecnical <= 0) {
                $createDefaultTechnical = $technicalManager->createDefaultTechnical();
                if (!$createDefaultTechnical) {
                    throw new Exception("Falha geral!");
                }
            }


            $technicalManager->fill($request->all());
            $technicalManager->status = 'active';

            if (!$technicalManager->save()) {
                throw new Exception("Houve uma Falha ao cadastrar os dados");
            }

            $establishmentsUpdate = Establishment::whereIn('id', $request->establishment_code);
            $establishmentsUpdate->update(['technician_code' => $technicalManager->id]);

            if (!$establishmentsUpdate) {
                throw new Exception("Houve uma Falha ao cadastrar os dados");
            }

            DB::commit();

            return redirect()->route('technicalManager.index')->with('alert', ['messageType' => 'success', 'message' => 'Responsável Técnico cadastrado com sucesso!']);
        } catch (Exception $e) {

            DB::rollback();
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => $e->getMessage()]);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $technicalManager = TechnicalManager::find($id);
        $establishments = Establishment::select(['id', 'establishment_code', 'state'])->orderBy('establishment_code')->get();

        return view('technicalManager.edit', [
            'technicalManager' => $technicalManager,
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
    public function update(TechnicalManagerRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $technicalManager = TechnicalManager::find($id);
            $technicalManager->fill($request->all());

            if (!$technicalManager->save()) {
                throw new Exception("Houve uma Falha ao atualizar os dados");
            }

            $idsEstalishmentDB = $technicalManager->idEstablishments();

            $compareNewOld = Utils::getDataOfArraysByComparing($idsEstalishmentDB['ids'], $request->establishment_code);

            if ($compareNewOld['delete']) {
                foreach ($compareNewOld['delete'] as $idDeleted) {
                    $estblishmentRemove = Establishment::find($idDeleted);
                    $estblishmentRemove->update(['technician_code' => 1]);

                    if (!$estblishmentRemove) {
                        throw new Exception("Houve uma Falha ao remover os estabelecimentos associados");
                    }
                }
            }

            if ($compareNewOld['insert']) {
                foreach ($compareNewOld['insert'] as $idInserted) {
                    $estblishmentInsert = Establishment::find($idInserted);
                    $estblishmentInsert->update(['technician_code' => $technicalManager->id]);

                    if (!$estblishmentInsert) {
                        throw new Exception("Houve uma Falha ao atualizar os novos estabelecimentos associados");
                    }
                }
            }


            DB::commit();

            return redirect()->route('technicalManager.index')->with('alert', ['messageType' => 'success', 'message' => 'Responsável Tecnico atualizado com sucesso!']);
        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
