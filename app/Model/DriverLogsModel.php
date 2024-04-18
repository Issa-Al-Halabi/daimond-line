<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DriverLogsModel extends Model
{

    protected $table = 'driver_logs';
    protected $fillable = ['driver_id', 'vehicle_id', 'date'];

    public function vehicle()
    {
        return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id");
    }

    public function driver()
    {
        return $this->hasOne("App\Model\User", "id", "driver_id")->withTrashed();
    }
}
