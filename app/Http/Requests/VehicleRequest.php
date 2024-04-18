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

class VehicleRequest extends FormRequest
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
            // 'make_id' => 'required',
            // 'model_id' => 'required',
            'year' => 'required|numeric',
            // 'engine_type' => 'required',
            // 'horse_power' => 'integer',
            // 'color_id' => 'required',
            // 'lic_exp_date' => 'required|date|date_format:Y-m-d',
            // 'reg_exp_date' => 'required|date|date_format:Y-m-d',
            // 'license_plate' => 'required|unique:vehicles,license_plate,' . \Request::get("id") . ',id,deleted_at,NULL',
            // 'int_mileage' => 'required|alpha_num',
            // 'vehicle_image' => 'required|mimes:jpg,png,jpeg',
            'average' => 'numeric',
            'type_id' => 'required|integer',
            'device_number' => 'required|unique:vehicles'
        ];
    }
}
