<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 use Kodeine\Metable\Metable;

class Bookings extends Model
{
    use HasFactory;
   //use Metable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "bookings";
   
    protected $fillable = [
        'customer_id', 'vehicle_id', 'user_id','from','to','option_id','start_time','end_time' ,'category_id', 'direction', 'subcategory_id', 'date', 'time', 'pickup', 'dropoff', 'pickup_addr', 'dest_addr', 'travellers', 'status', 'comment', 'dropoff_time', 'driver_id', 'note', 'bags',
        'pickup_latitude', 'pickup_longitude', 'drop_latitude', 'request_type', 'drop_longitude', 'km', 'minutes', 'cost', 'pickup_location', 'type_id','order_time'
    ];

 //protected function getMetaKeyName()
 //   {
 //       return 'booking_id'; // The parent foreign key
 //   }

    public function vehicle()
    {
        return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id");
    }
    public function customer()
    {
        return $this->hasOne("App\Model\User", "id", "customer_id")->withTrashed();
    }

    public function driver()
    {
        return $this->hasOne("App\Model\User", "id", "driver_id")->withTrashed();
    }

    public function user()
    {
        return $this->hasOne("App\Model\User", "id", "user_id")->withTrashed();
    }
    public function tour()
    {
        return $this->hasOne("App\Model\Tour", "id", "trip_id");
    }
}
