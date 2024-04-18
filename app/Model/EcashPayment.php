<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcashPayment extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "ecash-payment";

    protected $fillable = [
        'driver_id', 'is_success',
        'message', 'amount', 'token','transaction_number'
    ];
}
