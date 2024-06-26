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
use Illuminate\Foundation\Http\FormRequest;

class ExpenseCatRequest extends FormRequest
{
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

            'name' => 'required|unique:expense_cat,name,' . \Request::get("id") . ',id,deleted_at,NULL',
        ];
    }
}
