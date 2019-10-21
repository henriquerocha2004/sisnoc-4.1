<?php

namespace App\Http\Controllers;

use App\Models\CategoryProblem;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('config.categoryProblem.index');
    }

    public function table()
    {
        $category = CategoryProblem::all();
        return DataTables::of($category)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('config.categoryProblem.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $category = new CategoryProblem();
            $category->description_category = $request->description_category;
            $category->save();
            return redirect()->route('category-problem.index')->with('alert', ['messageType' => 'success', 'message' => 'Categoria salva com sucesso!']);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao salvar a categoria']);
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
        $category = CategoryProblem::find($id);
        return view('categoryProblem.edit',[
            'category' => $category
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
            $category = CategoryProblem::find($id);
            $category->description_category = $request->description_category;
            $category->save();
            return redirect()->route('category-problem.index')->with('alert', ['messageType' => 'success', 'message' => 'Categoria atualizada com sucesso!']);

        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['messageType' => 'danger', 'message' => 'Houve uma falha ao atualizar a categoria']);
        }
    }
}
