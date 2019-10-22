<?php

namespace App\Http\Controllers;

use App\Models\CategoryProblem;
use App\Models\ProblemCause;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

class CauseProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('config.problemCause.index');
    }

    public function table()
    {
        $causeProblem = ProblemCause::all();
        return DataTables::of($causeProblem)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CategoryProblem::all();
        return view('config.problemCause.create', [
            'categories' => $categories
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
        try {

            $causeProblem = new ProblemCause();
            $causeProblem->id_category = $request->id_category;
            $causeProblem->description_cause = $request->description_cause;
            $causeProblem->save();

            return redirect()->route('cause-problem.index')->with('alert', ['messageType' => 'success', 'message' => 'Causa salva com sucesso!']);

        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao salvar a causa do problema']);
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
        $causeProblem = ProblemCause::find($id);
        $categories = CategoryProblem::all();

        return view('config.problemCause.edit', [
            'causeProblem' => $causeProblem,
            'categories'   => $categories
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
            $causeProblem = ProblemCause::find($id);
            $causeProblem->description_cause = $request->description_cause;
            $causeProblem->id_category  = $request->id_category;
            $causeProblem->save();

            return redirect()->route('cause-problem.index')->with('alert', ['messageType' => 'success', 'message' => 'Causa atualizada com sucesso!']);

        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao atualizar a causa do problema']);
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
        try {
            $causeProblem = ProblemCause::find($id);
            $causeProblem->delete();
            return  response()->json(['result' => true, 'message' => 'Causa apagada com sucesso!']);

        } catch (Exception $e) {
            return response()->json(['result' => false, 'message' => 'Houve uma falha ao remover a causa!']);
        }
    }
}
