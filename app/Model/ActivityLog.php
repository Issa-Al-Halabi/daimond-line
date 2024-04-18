<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'logs';
    protected $fillable = 
    [
    'request_id','url','request_headers','request_body',
    'respose_data','message','user_id','driver_id'
    ];
}
