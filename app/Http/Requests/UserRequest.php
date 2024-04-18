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

class UserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    //     if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
    //         return true;
    //     } else {
    //         abort(404);
    //     }
    }

    public function rules()
    {
        return [
            //'module' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . \Request::get("id"),
           
            'profile_image' => 'nullable|mimes:jpg,png,jpeg',
          'password'=>'required|min:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
             'phone' => [
        Rule::unique('users')->where(function ($query) {
            $query->whereNull('deleted_at');
        }),
        'required',
        'numeric',
    ],
        ];
    }
    public function messages()
    {
        return [
            'module.required' => 'You must have to select Permission',

        ];
    }
}
