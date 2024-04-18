<?php
/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleModelRequest;
use App\Model\VehicleMake;
use App\Model\Vehicle_Model;
use DataTables;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['role:Admin']);
        $this->middleware('permission:VehicleModels add', ['only' => ['create', 'store']]);
        $this->middleware('permission:VehicleModels edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:VehicleModels delete', ['only' => ['bulk_delete', 'destroy']]);
        $this->middleware('permission:VehicleModels list');
    }

    public function index()
    {
        return view('vehicle_model.index');
    }

    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {

            $vehicle_types = Vehicle_Model::select('vehicle_model.*')->with('maker');

            return DataTables::eloquent($vehicle_types)
                ->addColumn('check', function ($vehicle) {
                    $tag = '<input type="checkbox" name="ids[]" value="' . $vehicle->id . '" class="checkbox" id="chk' . $vehicle->id . '" onclick=\'checkcheckbox();\'>';

                    return $tag;
                })
                ->addColumn('make', function ($vehicle) {
                    return ($vehicle->maker->make) ?? "";
                })
                ->addColumn('action', function ($vehicle) {
                    return view('vehicle_model.list-actions', ['row' => $vehicle]);
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'check'])
                ->make(true);
            //return datatables(User::all())->toJson();

        }
    }

    public function create()
    {
        $vehicle_makes = VehicleMake::get();
        return view('vehicle_model.create', compact('vehicle_makes'));
    }

    public function store(VehicleModelRequest $request)
    {
        $new = Vehicle_Model::create([
            'make_id' => $request->make_id,
            'model' => $request->model,
        ]);

        return redirect()->route('vehicle-model.index');
    }

    public function edit($id)
    {
        $data['vehicle_makes'] = VehicleMake::get();
        $data['vehicle_model'] = Vehicle_Model::find($id);
        return view('vehicle_model.edit', $data);
    }

    public function update(VehicleModelRequest $request)
    {

        $data = Vehicle_Model::find($request->get('id'));
        $data->update([
            'make_id' => $request->make_id,
            'model' => $request->model,
        ]);

        return redirect()->route('vehicle-model.index');
    }

    public function destroy(Request $request)
    {
        Vehicle_Model::find($request->get('id'))->delete();
        return redirect()->route('vehicle-model.index');
    }

    public function bulk_delete(Request $request)
    {
        Vehicle_Model::whereIn('id', $request->ids)->delete();
        return back();
    }
}
