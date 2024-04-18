<?php
/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleColorRequest;
use App\Model\VehicleColor;
use DataTables;
use Illuminate\Http\Request;

class VehicleColorsController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['role:Admin']);
        $this->middleware('permission:VehicleColors add', ['only' => ['create', 'store']]);
        $this->middleware('permission:VehicleColors edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:VehicleColors delete', ['only' => ['bulk_delete', 'destroy']]);
        $this->middleware('permission:VehicleColors list');
    }

    public function index()
    {
        $index['data'] = VehicleColor::get();
        return view('vehicle_colors.index', $index);
    }

    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {

            $vehicle_types = VehicleColor::query();

            return DataTables::eloquent($vehicle_types)
                ->addColumn('check', function ($vehicle) {
                    $tag = '<input type="checkbox" name="ids[]" value="' . $vehicle->id . '" class="checkbox" id="chk' . $vehicle->id . '" onclick=\'checkcheckbox();\'>';

                    return $tag;
                })
                ->addColumn('action', function ($vehicle) {
                    return view('vehicle_colors.list-actions', ['row' => $vehicle]);
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'check'])
                ->make(true);
            //return datatables(User::all())->toJson();

        }
    }

    public function create()
    {
        return view('vehicle_colors.create');
    }

    public function store(VehicleColorRequest $request)
    {
        $new = VehicleColor::create([
            'color' => $request->color,
            'code' => $request->code,
        ]);

        return redirect()->route('vehicle-color.index');
    }

    public function edit($id)
    {
        $data['color'] = VehicleColor::find($id);
        return view('vehicle_colors.edit', $data);
    }

    public function update(VehicleColorRequest $request)
    {
        $data = VehicleColor::find($request->get('id'));
        $data->update([
            'color' => $request->color,
            'code' => $request->code,
        ]);

        return redirect()->route('vehicle-color.index');
    }

    public function destroy(Request $request)
    {
        VehicleColor::find($request->get('id'))->delete();
        return redirect()->route('vehicle-color.index');
    }

    public function bulk_delete(Request $request)
    {
        VehicleColor::whereIn('id', $request->ids)->delete();
        return back();
    }
}
