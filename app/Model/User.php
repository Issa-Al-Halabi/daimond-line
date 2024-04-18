<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Model;

use App\Model\VehicleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodeine\Metable\Metable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Metable;
    use SoftDeletes;
    use Notifiable;
    use HasApiTokens;
    use HasRoles;
    use HasFactory;
    protected $dates = ['deleted_at'];
    protected $table = "users";
   	protected $metaTable = 'users_meta'; //optional.
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','device_token', 'user_type', 'passport_number', 'in_service', 'place_of_birth', 'group_id', 'api_token', 'father_name', 'mother_name', 'id_entry', 'date_of_birth', 'phone', 'profile_image', 'personal_identity', 'national_number', 'driving_certificate', 'car_mechanic', 'car_insurance', 'car_image'
    ];

    protected $hidden = ['password', 'remember_token', 'api_token'];

   protected function getMetaKeyName()
    {
       return 'user_id'; // The parent foreign key
   }

    public function user_data()
    {
        return $this->hasMany("App\Model\UserData", 'user_id', 'id')->withTrashed();
    }

    public function bookings()
    {
        return $this->hasMany('App\Model\Bookings', 'driver_id')->withTrashed();
    }

    public function vehicle_detail()
    {
        return $this->hasMany('App\Model\VehicleModel', 'user_id', 'id')->withTrashed();
    }

    public function driver_vehicle()
    {
        return $this->hasOne("App\Model\DriverVehicleModel", "driver_id", "id");
    }

    public function vehicles()
    {
        return $this->belongsToMany(VehicleModel::class, 'driver_vehicle', 'driver_id', 'vehicle_id')->using(DriverVehicleModel::class);
    }
    public function expense()
    {
        return $this->hasMany("App\Model\Expense", "driver_id", "id")->withTrashed();
    }

    // public function getUserMeta($key = '', $defaut = '')
    // {

    //     $key = trim($key);

    //     if ('' == $key) {
    //         return $defaut;
    //     }

    //     $metas = $this->metas->filter(function ($metas) use ($key) {
    //         return $metas->key === $key;
    //     });

    //     if ($metas->count() > 0) {
    //         return $metas->first()->value;
    //     }

    //     return $defaut;
    // }
}
