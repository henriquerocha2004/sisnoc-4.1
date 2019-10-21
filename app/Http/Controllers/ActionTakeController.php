<?php

namespace App\Http\Controllers;

use App\Models\ActionTake;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ActionTakeController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('config.actionTake.index');
    }

    public function table()
    {
        $actionTake = ActionTake::all();
        return DataTables::of($actionTake)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('config.actionTake.create');
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
            $actionTake = new ActionTake();
            $actionTake->action_description = $request->action_description;
            $actionTake->save();

            return redirect()->route('action-take.index')->with('alert', ['messageType' => 'success', 'message' => 'Ação Tomada salva com sucesso!']);

        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao salvar a Ação Tomada.']);
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
        $actionTake = ActionTake::find($id);

        return view('config.actionTake.edit',[
            'actionTake' => $actionTake
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
            $actionTake = ActionTake::find($id);
            $actionTake->action_description = $request->action_description;
            $actionTake->save();

            return redirect()->route('action-take.index')->with('alert', ['messageType' => 'success', 'message' => 'Ação Tomada atualizado com sucesso!']);

        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao atualizar a ação tomada.']);
        }
    }
}
