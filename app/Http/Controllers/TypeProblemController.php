<?php

namespace App\Http\Controllers;

use App\Models\TypeProblem;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TypeProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('config.typeProblem.index');
    }

    public function table()
    {
        $typeProblem = TypeProblem::all();
        return DataTables::of($typeProblem)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('config.typeProblem.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $typeProblem = new TypeProblem();
            $typeProblem->problem_description = $request->problem_description;
            $typeProblem->save();

            return redirect()->route('type-problem.index')->with('alert', ['messageType' => 'success', 'message' => 'Tipo de problema salvo com sucesso!']);

        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao salvar o tipo de problema.']);
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
        $typeProblem = TypeProblem::find($id);

        return view('config.typeProblem.edit',[
            'typeProblem' => $typeProblem
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $typeProblem = TypeProblem::find($id);
            $typeProblem->problem_description = $request->problem_description;
            $typeProblem->save();

            return redirect()->route('type-problem.index')->with('alert', ['messageType' => 'success', 'message' => 'Tipo de problema atualizado com sucesso!']);

        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao atualizar o tipo de problema.']);
        }
    }
}
