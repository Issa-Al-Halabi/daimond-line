<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle_Model extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'vehicle_model';
    protected $fillable = ['make_id', 'model'];

    public function maker()
    {
        return $this->hasOne("App\Model\VehicleMake", "id", "make_id")->withTrashed();
    }
}
