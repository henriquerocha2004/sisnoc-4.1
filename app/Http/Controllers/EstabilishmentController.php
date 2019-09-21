<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstablishmentRequest;
use App\Models\Establishment;
use App\Models\RegionalManager;
use App\Models\TechnicalManager;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

        try {
            $establishment = new Establishment();
            $establishment->fill($request->all());
            $establishment->id_user = auth()->user()->id;

            if(!$establishment->save()){
              throw new Exception("Houve uma falha ao salvar o estabelecimento");
            }

            return redirect()->route('estabilishment.index')->with('alert', ['messageType' => 'success', 'message' => 'Estabelecimento Cadastrado com sucesso!']);

        } catch (Exception $e) {
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
        try {

            $establishment = Establishment::find($id);
            $establishment->fill($request->all());

            if(!$establishment->save()){
               throw new Exception("Houve uma Falha ao atualizar os dados");
            }

            return redirect()->route('estabilishment.index')->with('alert', ['messageType' => 'success', 'message' => 'Estabelecimento Atualizado com sucesso!']);


        } catch (Exception $e) {
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => $e->getMessage()]);
        }
    }
}
