<?php

namespace App\Http\Controllers;

use App\Models\Called;
use App\Models\Establishment;
use App\Models\Links;
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
     * Generate dataTables at index
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
     * Function that returns the links of the informed establishment
     */
    public function getLinks(Request $request)
    {
        $result['response'] = false;

        $establishment = Establishment::where(['establishment_code' => $request->establishment_code])->first();

        if ($establishment != null) {

            $idEstablishment = $establishment->id;
            $links = Links::select(['id', 'link_identification', 'type_link'])
                ->where(['establishment_id' => $idEstablishment])->get();

            if (count($links) > 0) {
                $result['response'] = true;
                $result['links'] = $links;
                $result['establishment']['info'] = $establishment;
                $result['establishment']['regionalManager'] = $establishment->regionalManager()->first();
                $result['establishment']['technicalManager'] = $establishment->technicalManager()->first();
            } else {
                $result['message'] = "Esse estabelecimento não possui links cadastrados!";
            }
        } else {
            $result['message'] = "Estabelecimento não encontrado!";
        }

        return response()->json($result);
    }

    /**
     * function that checks for open calls
     */
    public function verifyOpenCalled(Request $request)
    {
        $result['response'] = false;
        $link = Links::find($request->id_link);

        $called = $link->called()
            ->where('status', '<>', 1)
            ->where('status', '<>', 7)
            ->get();

        if (count($called) > 0) {
            $result['reponse'] = true;
        }

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('called.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { }
}
