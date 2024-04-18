<?php

namespace App\Console\Commands;

use App\Model\Hyvikk;

use App\Model\Value;
use App\Events\TripStatusChangedEvent;

use App\Model\ServiceReminderModel;
use App\Model\User;
use App\Model\Bookings;
use App\Model\VehicleModel;
use DB;
use Illuminate\Console\Command;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;


class CancleTrip extends Command
{

  protected $signature = 'cancle:trip';

  protected $description = 'cancle trip after one minute ago';



  public function __construct()
  {
    parent::__construct();
  }


  public function handle()
  {
     $this->info('Trip canceled successfully.');
    //Artisan::call('queue:restart');
   // Artisan::call('queue:work');
    //$this->cancle_trip();

  }

  private function cancle_trip()
  {

    $trips = DB::table('bookings')->select('status', 'created_at', 'id', 'user_id')->where('status', 'pending')
      ->where('request_type', 'moment')->get();

    $time_out = Value::where('name', 'time_out')->first();

    if ($time_out->value < 10) {

      $minute = '0' . '' . $time_out->value;
    } else {
      $minute = $time_out->value;
    }

    $timeout = Carbon::createFromFormat('i', $minute)->format('H:i:s');


    foreach ($trips as $trip) {
      $sub_time = date('H:i:s', strtotime($trip->created_at));

      [$hours, $minutes] = explode(':',  $sub_time);

      $time = (int) $hours * 60 + (int) $minutes;

      $now_time = Carbon::now()->subMinutes($time);

      if (strtotime($now_time) > $timeout) {

        $user = DB::table('users')->where('id', $trip->user_id)->select('device_token')->first();
        Bookings::where('id', $trip->id)->update(["status" => "canceld"]);
        event(new TripStatusChangedEvent(['user_id' => $trip->user_id, 'status' => $trip->status]));
        $this->send_notification($user->device_token, 'تم إلغاء طلبك , يمكنك إعادة الطلب مرة ثانية', 'أهلا بك في دايموند لاين');
      }
    }
  }
}
