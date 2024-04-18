<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpRequest;
use App\Model\DriverLogsModel;
use App\Model\ExpCats;
use App\Model\Expense;
use App\Model\ServiceItemsModel;
use App\Model\User;
use App\Model\VehicleModel;
use App\Model\Vendor;
use Auth;
use DB;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin']);
        $this->middleware('permission:Transactions add', ['only' => ['store']]);
        // $this->middleware('permission:Transactions edit', ['only' => ['edit']]);
        $this->middleware('permission:Transactions delete', ['only' => ['bulk_delete', 'destroy']]);
        $this->middleware('permission:Transactions list');
    }

    public function index(Request $request)
    {


        $data1['expenses']  = Expense::select('expense.*', 'users.first_name', 'users.last_name','bookings.date')
            ->join('users', 'users.id', 'expense.driver_id')
            ->join('bookings','bookings.id','expense.trip_id')
            ->where('expense_type','changeable')
            ->where('bookings.category_id','2')
            ->get();


        return view("expense.index", $data1);
    }
     public function changeStatus(Request $request)
    {
      
        $expense = Expense::find($request->expense_id);
        $expense->status = $request->status;
        $expense->save();
  
        return response()->json(['success'=>'Status change successfully.']);
    }

    public function edit($id)
    {
        $data['expense'] = Expense::find($id);
        $data['types'] = ExpCats::get();
        $data['users'] = User::where('user_type', '=', 'driver')->where('is_active', '=', '1')->get();
        return view("expense.edit", $data);
    }

    public function show($id)
    {
    }
    public function update(ExpRequest $request, $id)
    {

        $expense = Expense::find($id);
        $expenses = implode(',', $request->get("expense_type"));

        $expense->vehicle_id = $request->get("vehicle_id");

        $expense->driver_id = $request->get("driver_id");
        $expense->comment = $request->get("comment");
        $expense->expense_type =    $expenses;
        $expense->save();
        return redirect()->route("expense.index");
    }

    public function store(ExpRequest $request)
    {
        
        $expenses = implode(',', $request->get("expense_type"));

        Expense::create([
            "vehicle_id" => $request->get("vehicle_id"),
            "amount" => $request->get("revenue"),
       
            "driver_id" => $request->get("driver_id"),
            "date" => $request->get('date'),
            "comment" => $request->get('comment'),
            "expense_type" =>   $expenses,
          
            "vendor_id" => $request->get('vendor_id'),
        ]);

        return redirect()->route("expense.index");
    }

    public function destroy(Request $request)
    {
        
        Expense::find($request->get('id'))->delete();

        return redirect()->route('expense.index');
    }

    public function expense_records(Request $request)
    {

        $user = Auth::user();
        if ($user->user_type == "D") {
           
            $vehicle_ids = auth()->user()->vehicles()->pluck('vehicle_id')->toArray();
            $data['vehicels'] = auth()->user()->vehicles()->with(['maker', 'vehiclemodel'])->whereIn_service(1)->get();
        } else {
            if ($user->group_id == null || $user->user_type == "S") {
                $data['vehicels'] = VehicleModel::with(['maker', 'vehiclemodel'])->whereIn_service(1)->get();
                $vehicle_ids = $data['vehicels']->pluck('id')->toArray();
            } else {
                $data['vehicels'] = VehicleModel::with(['maker', 'vehiclemodel'])->whereIn_service(1)->where('group_id', $user->group_id)->get();
                $vehicle_ids = $data['vehicels']->pluck('id')->toArray();
            }
        }

        $data['types'] = ExpCats::get();
        $data['today'] = Expense::with(['vehicle.maker', 'vehicle.vehiclemodel', 'service', 'category'])->whereIn('vehicle_id', $vehicle_ids)->whereBetween('date', [$request->get('date1'), $request->get('date2')])->get();
        $data['service_items'] = ServiceItemsModel::get();
        $data['total'] = Expense::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'))->sum('amount');
        $data['vendors'] = Vendor::get();
        $data['date1'] = $request->date1;
        $data['date2'] = $request->date2;
        return view("expense.index", $data);
    }

    public function bulk_delete(Request $request)
    {
        Expense::whereIn('id', $request->ids)->delete();
        return redirect('admin/expense');
    }
}
