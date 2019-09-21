<?php

namespace App\Http\Controllers;

use App\Models\Links;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LinksController extends Controller
{

    private $typeTlink = ['MPLS', 'ADSL', 'XDSL', 'IPConnect', 'Radio', 'SDWAN'];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('links.index');
    }

    /**
     * Generate dataTables at index
     */
    public function table()
    {
        $links = Links::join('establishment', 'links.establishment_id', '=', 'establishment.id')
            ->select(
                [
                    'links.id',
                    'establishment_id',
                    'establishment.establishment_code',
                    'links.type_link',
                    'links.link_identification',
                    'links.telecommunications_company',
                    'links.bandwidth'
                ]
            );
        return DataTables::of($links)->make(true);
    }

    /**
     * Show the form for creating a new resource.establishment
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('links.create', ['typeLink' => $this->typeTlink]);
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
