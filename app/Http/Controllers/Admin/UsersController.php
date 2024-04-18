<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserRequest;
use App\Model\Hyvikk;
use App\Model\User;
use App\Model\VehicleGroupModel;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Redirect;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct()
    {

        // $this->middleware(['role:Admin']);
        $this->middleware('permission:Users add', ['only' => ['create']]);
        $this->middleware('permission:Users edit', ['only' => ['edit']]);
        $this->middleware('permission:Users delete', ['only' => ['bulk_delete', 'destroy']]);
        $this->middleware('permission:Users list');
    }
   public function create()
    {
        $index['groups'] = VehicleGroupModel::all();
        $index['roles'] = Role::where('name','!=' ,'driver')->get();
       
        return view("users.create", $index);
    }
    public function index()
    {

        return view("users.index");
    }

    public function fetch_data(Request $request)
    {
        $users = User::select('users.*') ->where(function($query) {
        $query->where('user_type','!=','driver')
      ->where('user_type','!=','external_driver');})->with(['metas'])->orderBy('id', 'DESC');
        if ($request->ajax()) {

            $date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';
            return DataTables::eloquent($users)
                ->addIndexColumn()
                ->addColumn('check', function ($user) {
                    $tag = '';
                  
                    $tag = '<input type="checkbox" name="ids[]" value="' . $user->id . '" class="checkbox" id="chk' . $user->id . '" onclick=\'checkcheckbox();\'>';
                    // }
                    return $tag;
                })
                ->addColumn('profile_image', function ($user) {
                    $src = ($user->profile_image != null) ? asset('uploads/' . $user->profile_image) : asset('assets/images/no-user.jpg');

                    return '<img src="' . $src . '" height="70px" width="70px">';
                })
           
                ->editColumn('user_type', function ($user) {
                    return $user->user_type;
                })
                ->addColumn('action', function ($user) {
                    return view('users.list-actions', ['row' => $user]);
                })
                ->rawColumns(['profile_image', 'created_at', 'action', 'check'])
                ->make(true);
           

        }
    }
    public function store(UserRequest $request)
    {

        $role = Role::where('name', $request->role_id)->first();
       
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
        $user->father_name = $request->get("father_name");
        $user->mother_name = $request->get("mother_name");
       
        $user->place_of_birth = $request->get("place_of_birth");
        $user->date_of_birth = $request->get("date_of_birth");
        $user->email = $request->get("email");
        $user->phone = $request->get("phone");
        $user->passport_number = $request->get("passport_number");

        $user->address = $request->get("address");
        $user->password = bcrypt($request->get("password"));
        $user->user_type = $role->name;
        $user->api_token = str_random(60);
        $user->save();
        $user->assignRole($role->id);
        return Redirect::route("users.index");
    }
    public function edit($id)
    {
        $user = User::find($id);

        $groups = VehicleGroupModel::all();
        $roles= Role::where('name','!=' ,'driver')->get();
        return view("users.edit", compact("user",  "roles"));
    }
  
      public function update(Request $request)
    {
   
        $user = User::whereId($request->get("id"))->first();
       
        $role = Role::where('name', $request->role_id)->first();

       
        if ($request->hasfile('profile_image') == true) {
            $file = $request->file('profile_image');
            $destinationPath = './uploads';
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName1 = Str::uuid() . '.' . $extension;
            $file->move($destinationPath, $fileName1);
            $user->profile_image = $fileName1;
        }
        $user->first_name = $request->get("first_name");
        $user->mother_name = $request->get("mother_name");
        $user->last_name = $request->get("last_name");
        $user->father_name = $request->get("father_name");
        $user->place_of_birth = $request->get("place_of_birth");
        $user->date_of_birth = $request->get("date_of_birth");
        $user->email = $request->get("email");
        $user->phone = $request->get("phone");
       
        $user->address = $request->get("address");
         $user->password = bcrypt($request->get("password"));
        $user->user_type = $role->name;
        $user->api_token = str_random(60);
        $user->save();
        $user->assignRole($role->id);
        return Redirect::route("users.index");
    }
   public function show($id)
    {
        $index['user'] = User::find($id);
        return view('users.show', $index);
    }

    public function destroy(Request $request)
    {

        $user = User::find($request->get('id'));
       
         if (file_exists('./uploads/' . $user->profile_image) && !is_dir('./uploads/' . $user->profile_image)) {
             unlink('./uploads/' . $user->profile_image);
        }
       $user->delete();

        return redirect()->route('users.index');
    }

    private function upload_file($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = Str::uuid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);
        $user = User::find($id);
        $user->setMeta([$field => $fileName1]);
        $user->save();
    }

    public function bulk_delete(Request $request)
    {
        
        $users = User::whereIn('id', $request->ids)->get();
       foreach ($users as $user) {
        
         if (file_exists('./uploads/' . $user->profile_image) && !is_dir('./uploads/' . $user->profile_image)) {
               unlink('./uploads/' . $user->profile_image);
             }
         $user->delete();
        
        
    }
      return back();
}
   
}
