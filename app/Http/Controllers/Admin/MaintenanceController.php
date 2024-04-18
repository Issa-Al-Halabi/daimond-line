<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Maintenance;
use App\Model\MaintenanceCategory;
use App\Model\VehicleModel;
use Illuminate\Http\Request;
use DB;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['vehicles'] = VehicleModel::where('in_service', '=', '1')->where('class_id', 'internal_vehicle')->get();

        $data['types'] = MaintenanceCategory::all();


        $data['maintenance'] = DB::table('maintenances')->get();



        return view('maintenance.index', $data);
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



        if ($request->status == 'on') {
            $status = '1';
        } else {
            $status = '0';
        }
        $types = implode(',', $request->get("type_id"));

        Maintenance::create([
            "vehicle_id" => $request->get("vehicle_id"),

            "type_id" =>  $types,
            "date" => $request->get('date'),

            "status" =>  $status,
        ]);
        return redirect()->route("maintenance.index");
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
        $data['maint'] = DB::table('maintenances')->find($id);


        $data['vehicles'] = VehicleModel::where('in_service', '=', '1')->get();

        $data['types'] = MaintenanceCategory::all();



        $data['maintenance'] = DB::table('maintenances')->get();
        return view("maintenance.edit", $data);
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
        if ($request->status == '1') {
            $status = '1';
        } else {
            $status = '0';
        }
        $types = implode(',', $request->get("type_id"));
        DB::table('maintenances')->where('id', $id)->update(['vehicle_id' => $request->get("vehicle_id"), 'type_id' =>  $types, 'date' => $request->get("date"), 'status' => $status]);

        return redirect()->route("maintenance.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $mm = DB::table('maintenances')->where('id', $request->get('id'))->delete();
        return redirect()->route('maintenance.index');
    }
    public function bulk_delete(Request $request)
    {
        DB::table('maintenances')->whereIn('id', $request->ids)->delete();
        return redirect()->route("maintenance.index");
    }
}
