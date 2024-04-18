<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Model\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("subcategories.index");
    }
    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $subcategories = SubCategory::select('subcategories.*');
            return DataTables::eloquent($subcategories)
                ->addIndexColumn()
                ->addColumn('check', function ($subcat) {
                    $tag = '';
   
                    $tag = '<input type="checkbox" name="ids[]" value="' . $subcat->id . '" class="checkbox" id="chk' . $subcat->id . '" onclick=\'checkcheckbox();\'>';
                   
                    return $tag;
                })
                ->addColumn('action', function ($subcat) {
                    return view('subcategories.list-actions', ['row' => $subcat]);
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
        $index['categories'] = Category::all();
        return view("subcategories.create", $index);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->category_id == "1") {
            $type = "1";
        } elseif ($request->category_id == "2") {
            $type = "2";
        } else {
            $type = "3";
        }
        $category = SubCategory::create([
            'title' => $request->get("title"),
            'category_id' => '2',
            'type_id' => '2'
        ]);
        return redirect('/admin/subcategories');
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
        $subcategory = SubCategory::find($id);
        $categories = Category::all();
        return view("subcategories.edit", compact("subcategory", 'categories'));
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
        $category = SubCategory::find($id);
        $category->title = $request->get('title');
        $category->category_id = $request->get('category_id');
        $category->save();

        return redirect()->route("subcategories.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = SubCategory::find($id);
        $cat->delete();

        return redirect()->route('subcategories.index');
    }
   public function bulk_delete(Request $request)
    {
        
        $subcategory = SubCategory::whereIn('id', $request->ids)->delete();
        

        return back();
    }
}
