<?php

namespace App\Jobs;

use App\Events\TripStatusChangedEvent;
use App\Model\Bookings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelingTripJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $trip_id;
    public function __construct( $trip_id)
    {
        $this->trip_id = $trip_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $booking =  Bookings::where('id',$this->trip_id)->first();
      if($booking->status == 'pending' ){
         $booking->status = 'canceld';
         $booking->save();
         event(new TripStatusChangedEvent(['user_id'=>$booking->user_id,'id'=>$this->trip_id,'status'=>$booking->status ]));
        
      }else{
        return ; 
      }
      

      
    }
}
