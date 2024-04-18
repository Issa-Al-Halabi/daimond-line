<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $table = "tours";
    protected $fillable = [
        'trip_id', 'start_time', 'end_time', 'status', 'cost'
    ];
}
