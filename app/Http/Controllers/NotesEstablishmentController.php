<?php

namespace App\Http\Controllers;

use App\Models\NotesEstablishment;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotesEstablishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('config.notes-establishment.index');
    }


    public function table()
    {
        $notes = NotesEstablishment::join('establishment', 'establishment.id', '=', 'notes_establishment.id_establishment')
                                     ->join('users', 'users.id', '=', 'notes_establishment.user_id')
                                     ->select(['notes_establishment.id','notes_establishment.desc','establishment.establishment_code', 'users.name']);
        return DataTables::of($notes)->make(true);
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
            $note = NotesEstablishment::find($id)->delete();
            return  response()->json(['result' => true, 'message' => 'Nota apagada com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['result' => false, 'message' => 'Houve uma falha ao remover a nota!']);
        }
    }
}
