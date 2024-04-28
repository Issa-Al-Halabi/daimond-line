<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FatoraPayment extends Model
{
    use HasFactory;
    protected $table = "fatora_payments";

    protected $fillable = [
        "bookings_id",
        "merchant",
        "payment_id",
        "transaction_number",
        "amount",
        "terminal_id",
        "notes",
        "status",
        "creation_timestamp",
     ];

     public function bookings()
     {
         return $this->belongsTo(Bookings::class);
     }
}
