<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;

        // if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
        //     return true;
        // } else {
        //     abort(404);
        // }
    }

    public function rules()
    {
        return [
            //'module' => 'required',
           
            'email' => 'email|unique:users,email,' . \Request::get("id"),
            'profile_image' => 'nullable|mimes:jpg,png,jpeg',
        ];
           
    }

    public function messages()
    {
        return [
            'module.required' => 'You must have to select Permission',

        ];
    }
}
