<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class CarOptions extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $table = "car_option";
    protected $fillable = ['type_id', 'name', 'price', 'is_enable'];
}
