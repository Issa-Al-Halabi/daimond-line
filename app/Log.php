<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $fillable = 
    [
    'request_id','url','request_headers','request_body',
    'respose_data','error_message','user_id','driver_id'
    ];
}
