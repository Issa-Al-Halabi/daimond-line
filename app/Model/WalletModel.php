<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class WalletModel extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];
    protected $table = "wallet";

    protected $fillable = [
        'driver_id', 'amount','new_amount','transfare_image','method','status'

    ];
}
