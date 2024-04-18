<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Complaints extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $table = "complaints";
    protected $fillable = ['user_id', 'description'];
}
