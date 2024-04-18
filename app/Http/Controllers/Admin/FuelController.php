<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequest;
use App\Model\Expense;
use App\Model\FuelModel;
use App\Model\VehicleModel;
use App\Model\Vendor;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FuelController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['role:Admin']);
        $this->middleware('permission:Fuel add', ['only' => ['create']]);
        $this->middleware('permission:Fuel edit', ['only' => ['edit']]);
        $this->middleware('permission:Fuel delete', ['only' => ['bulk_delete', 'destroy']]);
        $this->middleware('permission:Fuel list');
    }

    public function index()
    {
        if (Auth::user()->user_type == "S" || Auth::user()->user_type != "D") {
            if (Auth::user()->group_id == null) {
                $vehicle_ids = VehicleModel::pluck('id')->toArray();
            } else {
                $vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
            }
        }
        if (Auth::user()->user_type == "D") {
            // $vehicle = DriverLogsModel::where('driver_id',Auth::user()->id)->get()->toArray();
            // $vehicle_ids = VehicleModel::where('id', $vehicle[0]['vehicle_id'])->pluck('id')->toArray();
            $vehicle_ids = auth()->user()->vehicles()->pluck('vehicles.id')->toArray();
        }

        $data['data'] = FuelModel::with(['vehicle_data.maker', 'vehicle_data.vehiclemodel'])->orderBy('id', 'desc')->whereIn('vehicle_id', $vehicle_ids)->get();
        return view('fuel.index', $data);
    }
  public function show($id){
    
  }
    public function create()
    {
        if (Auth::user()->user_type == "S" || Auth::user()->user_type != "D") {
            if (Auth::user()->group_id == null) {
                $data['vehicles'] = VehicleModel::whereIn_service("1")->with('maker', 'vehiclemodel')->get();
            } else {
                $data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->with('maker', 'vehiclemodel')->whereIn_service("1")->get();
            }
        }

        if (Auth::user()->user_type == "D") {
            // $vehicle = DriverLogsModel::where('driver_id',Auth::user()->id)->get()->toArray();
            // $data['vehicles'] = VehicleModel::where('id', $vehicle[0]['vehicle_id'])->whereIn_service("1")->get();
            $data['vehicles'] = auth()->user()->vehicles()->with('maker', 'vehiclemodel')->whereIn_service("1")->get();
        }
        $data['vendors'] = Vendor::where('type', 'fuel')->get();
        return view('fuel.create', $data);
    }

    public function store(Request $request)
    {
        dd($request);

        $fuel = new FuelModel();
        $fuel->vehicle_id = $request->get('vehicle_id');
        $fuel->user_id = $request->get('user_id');
        $condition = FuelModel::orderBy('id', 'desc')->where('vehicle_id', $request->get('vehicle_id'))->first();
       
        $fuel->reference = $request->get('reference');
        $fuel->province = $request->get('province');
        $fuel->note = $request->get('note');
        $fuel->qty = $request->get('qty');
        $fuel->fuel_from = $request->get('fuel_from');
        $fuel->vendor_name = $request->get('vendor_name');
        $fuel->cost_per_unit = $request->get('cost_per_unit');
        $fuel->date = $request->get('date');
        $fuel->complete = $request->get("complete");

        $fuel->save();
 return redirect()->back();
  
        //return redirect('admin/fuel');
    }

    public function edit($id)
    {
        
     
        $data['data'] =  FuelModel::find($id);
       
        //$data['vendors'] = Vendor::where('type', 'fuel')->get();
        return view('fuel.edit', $data);
    }

    public function update(Request $request)
    {
        $fuel = FuelModel::find($request->get("id"));
      
     

       
        $fuel->reference = $request->get('reference');
     
       

        $fuel->save();
      
        return redirect()->back();

    }

    public function destroy(Request $request)
    {
        $fuel = FuelModel::find($request->get('id'));

        if (!is_null($fuel->image) && file_exists('uploads/' . $fuel->image)) {
            unlink('uploads/' . $fuel->image);
        }

        $fuel->delete();

        Expense::where('exp_id', $request->get('id'))->where('expense_type', 8)->delete();
        return redirect()->route('fuel.index');
    }

    public function bulk_delete(Request $request)
    {
        $fuels = FuelModel::whereIn('id', $request->ids)->get();
        foreach ($fuels as $fuel) {
            if (!is_null($fuel->image) && file_exists('uploads/' . $fuel->image)) {
                unlink('uploads/' . $fuel->image);
            }
            $fuel->delete();
        }

        Expense::whereIn('exp_id', $request->ids)->where('expense_type', 8)->delete();
        return back();
    }
}
