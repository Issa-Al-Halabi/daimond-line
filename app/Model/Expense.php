<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    // use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'trip_id', 'status', 'price', 'vehicle_id', 'driver_id', 'amount', 'driver_amount', 'expense_type', 'comment', 'date', 'exp_id', 'type', 'vendor_id',
    ];
    protected $table = "expense";
    public function category()
    {
        return $this->hasOne("App\Model\ExpCats", "id", "expense_type")->withTrashed();
    }

    public function service()
    {
        return $this->hasOne("App\Model\ServiceItemsModel", "id", "expense_type")->withTrashed();
    }

    public function vehicle()
    {
        return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id");
    }

    public function vendor()
    {
        return $this->hasOne("App\Model\Vendor", "id", "vendor_id")->withTrashed();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
