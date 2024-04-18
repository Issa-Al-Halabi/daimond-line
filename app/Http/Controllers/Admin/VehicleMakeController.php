<?php
/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleMakeRequest;
use App\Model\VehicleMake;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VehicleMakeController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['role:Admin']);
        $this->middleware('permission:VehicleMaker add', ['only' => ['create', 'store']]);
        $this->middleware('permission:VehicleMaker edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:VehicleMaker delete', ['only' => ['bulk_delete', 'destroy']]);
        $this->middleware('permission:VehicleMaker list');
    }

    public function index()
    {
        return view('vehicle_make.index');
    }

    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {

            $vehicle_types = VehicleMake::query();

            return DataTables::eloquent($vehicle_types)
                ->addColumn('check', function ($vehicle) {
                    $tag = '<input type="checkbox" name="ids[]" value="' . $vehicle->id . '" class="checkbox" id="chk' . $vehicle->id . '" onclick=\'checkcheckbox();\'>';

                    return $tag;
                })
                ->editColumn('image', function ($vehicle) {
                    $src = ($vehicle->image != null) ? asset('uploads/' . $vehicle->image) : asset('assets/images/vehicle.jpeg');

                    return '<img src="' . $src . '" height="70px" width="70px">';
                })
                ->addColumn('action', function ($vehicle) {
                    return view('vehicle_make.list-actions', ['row' => $vehicle]);
                })
                ->addIndexColumn()
                ->rawColumns(['image', 'action', 'check'])
                ->make(true);
            //return datatables(User::all())->toJson();

        }
    }

    public function create()
    {
        return view('vehicle_make.create');
    }

    public function store(VehicleMakeRequest $request)
    {
        $new = VehicleMake::create([
            'make' => $request->make,
        ]);
        $file = $request->file('image');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $destinationPath = './uploads'; // upload path
            $extension = $file->getClientOriginalExtension();
            $fileName1 = Str::uuid() . '.' . $extension;
            $file->move($destinationPath, $fileName1);
            $new->image = $fileName1;
            $new->save();
        }
        return redirect()->route('vehicle-make.index');
    }

    public function edit($id)
    {
        $data['vehicle_make'] = VehicleMake::find($id);
        return view('vehicle_make.edit', $data);
    }

    public function update(VehicleMakeRequest $request)
    {

        $data = VehicleMake::find($request->get('id'));
        $data->update([
            'make' => $request->make,
        ]);
        $file = $request->file('image');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $destinationPath = './uploads'; // upload path
            $extension = $file->getClientOriginalExtension();
            $fileName1 = Str::uuid() . '.' . $extension;
            $file->move($destinationPath, $fileName1);
            $data->image = $fileName1;
            $data->save();
        }
        return redirect()->route('vehicle-make.index');
    }

    public function destroy(Request $request)
    {
        VehicleMake::find($request->get('id'))->delete();
        return redirect()->route('vehicle-make.index');
    }

    public function bulk_delete(Request $request)
    {
        VehicleMake::whereIn('id', $request->ids)->delete();
        return back();
    }
}
