<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;


use Auth;
use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{

    public function authorize()
    {
        if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
            return true;
        } else {
            abort(404);
        }
    }

    public function rules()
    {
        if ($this->request->has("edit")) {
            return [

               
               
                 'phone' => [
        Rule::unique('users')->where(function ($query) {
            $query->whereNull('deleted_at');
        }),
     
        'numeric',
    ],
                'email' => 'required|email|unique:users,email,' . \Request::get("id"),
                 'password'=>'min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                
               
                'driver_image' => 'nullable|mimes:jpg,png,jpeg',
               
                
            ];
        } else {
            return [

              
                        'phone' => [
        Rule::unique('users')->where(function ($query) {
            $query->whereNull('deleted_at');
        }),
     
        'numeric',
    ],
                'email' => 'email|unique:users,email,' . \Request::get("id"),
                 'password'=>'min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
               
            ];
        }
    }
}
