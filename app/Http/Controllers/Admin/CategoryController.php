<?php

namespace App\Http\Controllers\Admin;


// use App\Model\Category;

use App\Model\Category;
use DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("categories.index");
    }
    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::select('categories.*');
            return DataTables::eloquent($categories)
                ->addColumn('check', function ($cat) {
                    $tag = '';
                    // if ($user->user_type = "driver") {
                    $tag = '<input type="checkbox" name="ids[]" value="' . $cat->id . '" class="checkbox" id="chk' . $cat->id . '" onclick=\'checkcheckbox();\'>';
                    // }
                    return $tag;
                })
                ->addColumn('action', function ($cat) {
                    return view('categories.list-actions', ['row' => $cat]);
                })

                ->rawColumns(['action', 'check'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("categories.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->get("name")
        ]);
        return redirect('/admin/categories');
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
        $category = Category::find($id);
        return view("categories.edit", compact("category"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $category = Category::find($id);
        $category->name = $request->get('name');
        $category->save();

        return redirect()->route("categories.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();

        return redirect()->route('categories.index');
    }
}
