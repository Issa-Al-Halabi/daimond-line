<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers as CustomerRequest;
use App\Http\Requests\ImportRequest;
use App\Imports\CustomerImport;
use App\Model\DriverLocation;
use App\Model\WalletModel;
use Illuminate\Validation\Rule;

use App\Model\User;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Validator;

class CustomersController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['role:Admin']);
        $this->middleware('permission:Customer add', ['only' => ['create']]);
        $this->middleware('permission:Customer edit', ['only' => ['edit']]);
        $this->middleware('permission:Customer delete', ['only' => ['bulk_delete', 'destroy']]);
        $this->middleware('permission:Customer list');
        $this->middleware('permission:Customer import', ['only' => ['importCutomers']]);
    }

    public function importCutomers(ImportRequest $request)
    {

        $file = $request->excel;
        $destinationPath = './assets/samples/'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $file->move($destinationPath, $fileName);
      
        Excel::import(new CustomerImport, 'assets/samples/' . $fileName);

        return back();
    }

    public function index()
    {
        return view("customers.index");
    }

    public function fetch_data(Request $request)
    {
        $users = User::select('users.*')->with(['user_data'])->whereUser_type("driver")->orderBy('users.id', 'desc')->groupBy('users.id');
        if ($request->ajax()) {

            return DataTables::eloquent($users)
                ->addIndexColumn()
                ->addColumn('check', function ($user) {
                    return '<input type="checkbox" name="ids[]" value="' . $user->id . '" class="checkbox" id="chk' . $user->id . '" onclick=\'checkcheckbox();\'>';
                })
             
                ->editColumn('first_name', function ($user) {
                    
                    return $user->first_name;
                })
                ->addColumn('is_active', function ($user) {
                    return ($user->is_active == 'active') ? "active" : "pending";
                })
                ->addColumn('in_service', function ($user) {
                   
                  
                   return $user->in_service;
                })

                ->addColumn('phone', function ($user) {
                    return $user->phone;
                })
                
                ->addColumn('action', function ($user) {
                    return view('customers.list-actions', ['row' => $user]);
                })
                ->rawColumns(['action', 'check', 'name'])
                ->make(true);

        }
    }
   public function create()
    {
        $index['roles'] = Role::where('name', '=', 'Driver')->get();
        return view("customers.create", $index);
    }

  //enable driver

    public function enable($id)
    {
      
        $driver = User::find($id);

        $driver->is_active = 'active';
        $driver->save();
        return redirect()->route("customers.index");
    }
  //disable driver

    public function disable($id)
    {
      
        $driver = User::find($id);
        $driver->is_active = 'pending';
        $driver->save();
        return redirect()->route("customers.index");
    }
   

    public function store(Request $request)
    {

      $request->validate([
        'first_name'=>'required',
        'last_name'=>'required',
       
        'password'=>'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        
        'phone' => [
        Rule::unique('users')->where(function ($query) {
            $query->whereNull('deleted_at');
        }),
        'required',
        'numeric',
    ],
            

        ]);
     
        $role = Role::find($request->role_id)->toArray();

        if ($role['name'] == "Driver") {
            $user_type = 'driver';
        }
        if ($request->is_active == '1') {
            $status = 'active';
     
        } else {
            $status = 'pending';
            
        }
        $user = new User();
        if ($request->hasfile('profile_image') == true) {
            $file = $request->file('profile_image');
            $destinationPath = './uploads';
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName1 = Str::uuid() . '.' . $extension;
            $file->move($destinationPath, $fileName1);
            $user->profile_image = $fileName1;
        }

        $user->first_name = $request->get("first_name");
        $user->last_name = $request->get("last_name");
        $user->email = $request->get("email");
        $user->phone = $request->get("phone");
       

        $user->is_active = $status;
        $user->in_service = "off";
       
        $user->address = $request->get("address");
     $user->password = bcrypt($request->get("password"));
        $user->date_of_birth = $request->get("date_of_birth");
        $user->user_type = $user_type;
        $user->api_token = str_random(60);
        $user->save();
        $role = Role::find($request->role_id);
        $user->assignRole($role);
      
        $driver_location = new DriverLocation();
        $driver_location->driver_id = $user->id;
        $driver_location->save();
      
        $driver_wallet = new WalletModel();
        $driver_wallet->driver_id = $user->id;
        $driver_wallet->save();
 
        return redirect()->route("customers.index");
    }
  
     public function show($id)
    {
        $index['customer'] = User::find($id);
        return view('customers.show', $index);
    }
   public function edit($id)
    {
        $index['data'] = User::whereId($id)->first();
        $index['roles'] = Role::where('name', '=', 'Driver')->get();
        return view("customers.edit", $index);
    }
   
    public function update(Request $request)
    {

        $user = User::find($request->id);
      
      if ($request->hasfile('profile_image') == true) {
       
            $file = $request->file('profile_image');
            $destinationPath = './uploads';
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName1 = Str::uuid() . '.' . $extension;
            $file->move($destinationPath, $fileName1);
            $user->profile_image = $fileName1;
        }
    

        $user->email = $request->get('email');
      
        $user->first_name = $request->get("first_name");
        $user->last_name = $request->get("last_name");
       
        $user->address = $request->get("address");
        $user->phone = $request->get("phone");
         $user->password = bcrypt($request->get("password"));
        $user->gender = $request->get('gender');
        $user->save();
        $role = Role::find($request->id);
        $user->assignRole($role);
        return redirect()->route("customers.index");
    }

  
  
     public function destroy(Request $request)
    {

        $user = User::find($request->get('id'));
        
        $user->update([
            'email' => time() . "_deleted" . $user->email,
        ]);
        $user->delete();
      $wallet=WalletModel::whereIn('driver_id',$request->get('id'))->delete();
      $location=DriverLocation::whereIn('driver_id',$request->get('id'))->delete();
     

        return redirect()->route('customers.index');
    }
  
    public function bulk_delete(Request $request)
    {
        
      $users = User::whereIn('id', $request->ids)->delete();
      $wallet=WalletModel::whereIn('driver_id',$request->ids)->delete();
      $location=DriverLocation::whereIn('driver_id',$request->ids)->delete();
      
        return back();
    }


    public function ajax_store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'unique:users,email',
            'phone' => 'numeric',
        ]);

        if ($v->fails()) {
            $d = 0;
        } else {
            $id = User::create([
                "name" => $request->get("first_name") . " " . $request->get("last_name"),
                "email" => $request->get("email"),
                "password" => bcrypt("password"),
                "user_type" => "C",
                "api_token" => str_random(60),
            ])->id;
            $user = User::find($id);
            $user->first_name = $request->get("first_name");
            $user->last_name = $request->get("last_name");
            $user->address = $request->get("address");
            $user->mobno = $request->get("phone");
            $user->gender = $request->get('gender');
            $user->save();
            $user->givePermissionTo(['Bookings add', 'Bookings edit', 'Bookings list', 'Bookings delete']);
            $d = User::whereUser_type("C")->get(["id", "name as text"]);
        }

        return $d;
    }


}
