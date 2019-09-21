<?php

namespace App\Http\Controllers;

use App\Models\Called;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CalledController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('called.index');
    }

    /**
     * Generate dataTables no index
     */
    public function table()
    {
        $calleds = Called::join('establishment', 'called.id_establishment', '=', 'establishment.id')
                    ->join('links', 'called.id_link', '=', 'links.id') 
                    ->join('users', 'called.id_user_open', '=', 'users.id')
                    ->select(
                        [   
                            'called.id',
                            'called.caller_number',
                            'establishment.establishment_code',
                            'links.type_link',
                            'called.next_action',
                            'users.name'
                        ]
        );
        return DataTables::of($calleds)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
