<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Value extends Model
{
   // use SoftDeletes;
    protected $table = 'value';
    protected $fillable = ['id', 'name','value','time'];
}
