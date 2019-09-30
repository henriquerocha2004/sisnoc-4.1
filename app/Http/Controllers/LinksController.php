<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinksRequest;
use App\Models\Establishment;
use App\Models\Links;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LinksController extends Controller
{

    private $typeLink = ['MPLS', 'ADSL', 'XDSL', 'IPConnect', 'Radio', 'SDWAN'];


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
        $establishments = Establishment::select(['id', 'establishment_code'])->get();
        return view('links.create', ['typeLink' => $this->typeLink, 'establishments' => $establishments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LinksRequest $request)
    {

        try {
            $link = new Links();
            $link->fill($request->all());
            $link->status = 'active';
    
            if(!$link->save()){
                throw new Exception("Houve uma Falha ao cadastrar o link");
            }

            return redirect()->route('links.index')->with('alert', ['messageType' => 'success', 'message' => 'Link cadastrado com sucesso!']);

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
        $link = Links::find($id);
        $establishments = Establishment::select(['id', 'establishment_code'])->get();

        return view('links.edit', [
            'link' => $link,
            'establishments' => $establishments,
            'typeLink' => $this->typeLink
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LinksRequest $request, $id)
    {
        try {
            $link = Links::find($id);
            $link->fill($request->all());

            if(!$link->save()){
                throw new Exception("Houve uma Falha ao atualizar o link");
            }

            return redirect()->route('links.index')->with('alert', ['messageType' => 'success', 'message' => 'Link atualizado com sucesso!']);

        } catch (Exception $e) {
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
