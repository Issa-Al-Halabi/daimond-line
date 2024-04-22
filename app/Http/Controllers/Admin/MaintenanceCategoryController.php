<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\MaintenanceCategory;
use Illuminate\Http\Request;

class MaintenanceCategoryController extends Controller
{
    public function index()
    {
        $data['maint'] = MaintenanceCategory::get();
        return view("maint_categories.index", $data);
    }
  public function show($id){
    
  }
    public function store(Request $request)
    {
        if(isset($request->type)){

            MaintenanceCategory::create([
                "type" => $request->get("type")
            ]);

            return redirect()->route("maintcategory.index");

        }else{
            return redirect()->back()->withErrors(["empty"=>"الرجاء ادخال قيمة"]);
        }
    }
    public function edit($id)
    {
        $maintenance = MaintenanceCategory::find($id);
        return view("maint_categories.edit", compact('maintenance'));
    }
    public function update(Request $request, $id)
    {

        $maintenance = MaintenanceCategory::find($id);
        $maintenance->type =  $request->type;
        $maintenance->save();
        return redirect()->route("maintcategory.index");
    }
    public function destroy($id)
    {

        MaintenanceCategory::find($id)->delete();
        return redirect()->route("maintcategory.index");
    }
    public function bulk_delete(Request $request)
    {
        MaintenanceCategory::whereIn('id', $request->ids)->delete();
        return redirect()->route('maintcategory.index');
    }
}
