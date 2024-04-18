<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FareSettings extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'key_name', 'key_value', 'type_id', 'user_type','cost','limit_distance', 'category_id', 'subcategory_id', 'direction', 'price', 'base_km', 'base_km', 'weekend_base_km', 'weekend_wait_time', 'night_base_km', 'night_wait_time'
    ];
    protected $table = "fare_settings";

    public static function get($key)
    {
        return ApiSettings::whereName($key)->first()->key_value;
    }
}
