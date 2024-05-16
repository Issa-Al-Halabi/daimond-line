<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\Api;

use App\Http\Traits\SendNotification;
use App\Model\Value;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Model\Bookings;
use App\Model\CarOptions;
use App\Model\Category;
use App\Model\Fuel;
use App\Model\Complaints;
use App\Jobs\CancelingTripJob;
use App\Model\ContactModel;
use App\Model\DriverLocation;
use App\Model\FareSettings;
use App\Model\Hyvikk;
use App\Model\MessageModel;
use App\Model\ReviewModel;
use App\Model\OTP;
use Carbon\Carbon;
use App\Model\SubCategory;
use App\Model\Tour;
use App\Model\User;
use App\Model\VehicleModel;
use App\Model\VehicleTypeModel;
use App\Rules\UniqueMobile;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Login;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Validator;
use DB;
use auth;


class UsersApi extends Controller
{
    use SendNotification;


    //Send otp code to mobile using mtn message
    public function MTN(Request $request)
    {

        $otp    = $request->otp;
        $mobile = $request->mobile;
        $string = 'رمز التفعيل الخاص بك  ' . $otp;
        $str = mb_strtoupper(bin2hex(mb_convert_encoding($string, 'UTF-16BE', 'UTF-8')));
        $url = "https://services.mtnsyr.com:7443/General/MTNSERVICES/ConcatenatedSender.aspx?User=dfffr194&Pass=dccvv191914&From=Diamond&Gsm=$mobile&Msg=$str&Lang=0";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    // Delete the user account from all associated tables

    public function DeleteRideAccount(Request $request)
    {


        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone' => 'required',
            'password' => 'required',

        ]);
        $errors = $validation->errors();


        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = " OOPS !";
            return $data;
        } else {
            $user = DB::table('users')->where('id', $request->user_id)->select('user_type', 'phone', 'password', 'id')->first();

            if ($user->phone == $request->phone && Hash::check($request->password, $user->password)) {
                try {
                    Bookings::where('user_id', $request->user_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
                try {
                    Complaints::where('user_id', $request->user_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }

                try {
                    ReviewModel::where('user_id', $request->user_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }


                try {
                    User::where('id', $request->user_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
                $data['error'] = false;
                $data['message'] = "The account has been deleted successfully";
                return $data;
            } else {
                $data['error'] = true;
                $data['message'] = "The information is not match";
                return $data;
            }
        }
    }

    // get Terms and Condition

    public function get_terms()
    {

        $terms = DB::table('fuel')->select('reference')->where('id', '3')->first();

        return $terms;
    }


    //Displaying cars near the user's location on the map.
    public function Map_Cars(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',

        ]);
        $errors = $validation->errors();

        if (count($errors) > 0) {

            $data['errors'] = true;
            $data['message'] = "Warning!";
            return $data;
        } else {
            $radius = 2;

            $nearest_car = DB::table('driver_location')->select(
                DB::raw('driver_location.id as id,driver_location.*,vehicles.vehicle_image, vehicles.color, vehicles.car_number, vehicles.car_model,users.*, ( 3959      * acos( 
             cos( radians(' . $request->pickup_latitude . ') ) *
             cos( radians( latitude) ) *
             cos( radians( longitude ) - 
             radians(' . $request->pickup_longitude . ') ) + 
             sin( radians(' . $request->pickup_latitude . ') ) *
             sin( radians( latitude ) ) ) ) as distance')
            )->having('distance', '<', $radius)

                ->join('users', 'users.id', 'driver_location.driver_id')
                ->where('users.is_active', 'active')->where('users.in_service', 'off')
                ->join('vehicles', 'vehicles.id', 'driver_location.vehicle_id')
                ->where('vehicles.in_service', '=', '1')
                ->get();

            if (count($nearest_car) == '0') {
                $data['error'] = true;
                $data['message'] = 'There are no cars';

                return $data;
            } else {

                $data['error'] = false;
                $data['message'] = 'Listed Successfuly';
                $data['data'] =  $nearest_car;
                return $data;
            }
        }
    }


    //Register user

    public function user1_registration(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'device_token' => 'required',
            'email' => 'unique:users|email|nullable',
            'phone' => [
                Rule::unique('users')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
                'required',
                'numeric'
            ],
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',

        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['message'] = "Unable to Register. Please, check the Details OR Try again Later";
            $data['error'] = true;
            return $data;
        }
        $user = new User();
        if ($request->hasfile('profile_image') == true) {
            $file = $request->file('profile_image');
            $destinationPath = './uploads';
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName1 = Str::uuid() . '.' . $extension;
            $file->move($destinationPath, $fileName1);
            $user->profile_image = $fileName1;
        }
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->father_name = $request->get('father_name');
        $user->mother_name = $request->get('mother_name');

        $user->place_of_birth = $request->get('place_of_birth');

        $user->date_of_birth = $request->get('date_of_birth');
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->user_type = 'user';
        $user->device_token = $request->get('device_token');
        $user->api_token = str_random(60);
        $user->deleted_at = null;
        $user->is_active = 'active';

        $user->save();
        $user->assignRole('user');


        $data['error'] = false;
        $data['message'] = "You have Registered Successfully!";
        $data['data'] = ['api_token' => $user->api_token, 'user' => $user];
        return $data;
    }



    /**
  User login with checking the user type If the user type passed from the request
  is equal to the stored user type,
  the process is successful
     **/

    public function user_login(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required',
            'password' => 'required',
            'device_token' => 'required',
            'user_type' => 'required',
        ]);

        $phone = $request->get("phone");
        $password = $request->get("password");
        $device_token = $request->get("device_token");
        $user_type = $request->get("user_type");

        $user = User::where('phone', $request->phone)->first();
        if ($user->is_active != 'active') {
            $data['error'] = true;
            $data['message'] = "this account is not active yet.";
            return $data;
        }
        if (Hash::check($request->password, $user->password)) {

            if ($user_type == 'driver' && ($user->user_type == 'driver' || $user->user_type == 'external_driver')) {
                // $user = Login::user();
                $user->update(["device_token" => $device_token]);
                $data['error'] = false;
                $data['message'] = "You have Signed in Successfully!";
                $data['data'] = ["api_token" => $user->api_token, "id" => $user->id, 'type' => $user->user_type, "device_token" => $user->device_token];
                return $data;
            } elseif ($user_type == 'user' && ($user->user_type == 'user' || $user->user_type == 'Organizations')) {
                //  $user = Login::user();
                $user->update(["device_token" => $device_token]);
                $data['error'] = false;
                $data['message'] = "You have Signed in Successfully!";
                $data['data'] = ["api_token" => $user->api_token, "id" => $user->id, 'type' => $user->user_type, "device_token" => $user->device_token];
                return $data;
            } else {
                $data['error'] = true;
                $data['message'] = "Invalid Login Credentials";
                return $data;
            }
        } else {

            $data['error'] = true;
            $data['message'] = "Invalid Login Credentials";
            return $data;
        }
    }
    /**
 get all goverments.

     **/

    public function goverment()
    {
        $goverment = SubCategory::where('category_id', '2')->get();
        if ($goverment->isEmpty()) {

            $data['error'] = true;
            $data['message'] = 'Not Found';

            return $data;
        } else {

            $data['error'] = false;
            $data['message'] = 'Listed Successfuly';
            $data['data'] =  $goverment;
            return $data;
        }
    }

    /**
  Allow the user to change the password
  If the user type passed from the request
  is equal to the stored user type,

     **/
    public function forgot_password(Request $request)
    {

        $phone = $request->phone;
        $user_type = $request->user_type;

        $user = User::where('phone', $phone)->first();

        if ($user_type == 'driver' && ($user->user_type == 'driver' || $user->user_type == 'external_driver')) {
            $user->password = bcrypt($request->password);
            $user->device_token = $request->get('device_token');
            $user->save();

            $data['error'] = false;
            $data['message'] = "password updated Successfully!.";
            $data['data'] = ["id" => $user->id, "api_token" => $user->api_token, "user_type" => $user->user_type, "device_token" => $user->device_token];

            return $data;
        } elseif ($user_type == 'user' && ($user->user_type == 'user' || $user->user_type == 'Organizations')) {

            $user->password = bcrypt($request->password);
            $user->device_token = $request->get('device_token');
            $user->save();

            $data['error'] = false;
            $data['message'] = "password updated Successfully!.";
            $data['data'] = ["id" => $user->id, "api_token" => $user->api_token, "user_type" => $user->user_type, "device_token" => $user->device_token];

            return $data;
        } else {

            $data['error'] = true;
            $data['message'] = "Warning!.";
            return $data;
        }
    }


    protected function validateEmail(Request $request)
    {

        $this->validate($request, ['email' => 'required|email']);
    }

    public function broker()
    {
        return Password::broker();
    }

    private function upload_file($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = Str::uuid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);
        $user = User::find($id);
        $user->setMeta([$field => $fileName1]);
        $user->save();
    }
    /**
  get user profile.
     **/
    public function get_profile(Request $request)
    {
        $user = DB::table('users')->find($request->user_id);

        $image =  asset('uploads/' . $user->profile_image);


        if ($user) {
            $data['error'] = false;
            $data['message'] = 'user found';
            $data['data'] = array('image' => $image, 'user' => $user);

            return $data;
        } else {
            $data['error'] = true;
            $data['message'] = 'user not found';

            return $data;
        }
    }

    /**
  update user profile information.
  
     **/
    public function edit_profile(Request $request)
    {
        $user = User::find($request->user_id);


        $validation = Validator::make($request->all(), [
            //'password'=>'min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);
        $errors = $validation->errors();

        if (count($errors) > 0) {
            $data['message'] = "Unable to Update Profile. Please, check the Details OR Try again Later";
            $data['error'] = true;

            return $data;
        } else {

            if ($request->hasfile('profile_image') == true) {
                $file = $request->file('profile_image');
                $destinationPath = './uploads';
                $extension = $request->file('profile_image')->getClientOriginalExtension();
                $fileName1 = Str::uuid() . '.' . $extension;
                $file->move($destinationPath, $fileName1);
                $user->profile_image = asset('/uploads/' . $fileName1);
            }

            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->mother_name = $request->get('mother_name');
            $user->father_name = $request->get('father_name');
            $user->phone = $request->get('phone');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get("password"));
            $user->place_of_birth = $request->get('place_of_birth');
            $user->date_of_birth = $request->get('date_of_birth');
            $user->save();

            $data['error'] = false;
            $data['message'] = "Profile has been Updated Successfully!";
            $data['data'] = $user;
            return $data;
        }
    }
    /**
    Filter goverment vehicles according to the required specifications and display their price
    The pricing varies according to the type of user(user or orgnizations)
     **/
    public function filter_vehicle(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'seats' => 'required',
            'bags' => 'required',
            'km' => 'required',
            'min' => 'required',
            'user_id' => 'required',
        ]);

        $user_type = User::where('id', $request->user_id)->select('user_type')->first();
        if (strtoupper($user_type->user_type) == strtoupper('user')) {

            $vehicles =  VehicleModel::select('vehicles.*', 'vehicle_types.vehicletype', 'fare_settings.base_km', 'fare_settings.base_time')
                ->where('vehicles.category_id', $request->category_id)
                ->whereIn('vehicles.subcategory_id', ["0", $request->subcategory_id])
                ->where('in_service', '=', '1')
                ->join('vehicle_types', 'vehicle_types.id', '=', 'vehicles.type_id')
                ->join('fare_settings', 'fare_settings.type_id', 'vehicle_types.id')
                ->where('fare_settings.category_id', 'outside damascus')
                ->where(DB::raw('lower(user_type)'), 'like', '%' . strtolower('user') . '%')
                ->where('vehicles.seats', '>=', $request->seats)->where('vehicles.bags', '>=', $request->bags)->where('isenable', '=', '1')
                ->get();
        } elseif (strtoupper($user_type->user_type) == strtoupper('organizations')) {


            $vehicles = DB::table('vehicles')->select('vehicles.*', 'vehicle_types.vehicletype', 'fare_settings.base_km', 'fare_settings.base_time')
                ->where('vehicles.category_id', $request->category_id)
                ->whereIn('vehicles.subcategory_id', ["0", $request->subcategory_id])
                ->where('in_service', '=', '1')
                ->join('vehicle_types', 'vehicle_types.id', '=', 'vehicles.type_id')
                ->join('fare_settings', 'fare_settings.type_id', 'vehicle_types.id')
                ->where('fare_settings.category_id', 'outside damascus')
                ->where(DB::raw('lower(user_type)'), 'like', '%' . strtolower('organizations') . '%')
                ->where('vehicles.seats', '>=', $request->seats)->where('vehicles.bags', '>=', $request->bags)->where('isenable', '=', '1')
                ->get();
        }

        foreach ($vehicles as $v) {

            $v->vehicle_image = asset('/uploads/' . $v->vehicle_image);
            $v->cost = ($v->base_km * $request->km) + ($v->base_time * $request->min);
        }
        if (count($vehicles) === 0) {
            $data['error'] = true;
            $data['message'] = 'There Is No Vehicle';
            return $data;
        } else {

            $data['error'] = false;
            $data['message'] = 'Listed Successfuly';
            $data['data'] = $vehicles;
            return $data;
        }
    }

    public function message_us(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'message' => 'required',
        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['success'] = 0;
            $data['message'] = "Oops, Something got Wrong. Please, Try again Later!";
            $data['data'] = "";
        } else {
            $user = User::find($request->user_id);
            MessageModel::create(['fcm_id' => $request->fcm_id, 'user_id' => $request->user_id, 'message' => $request->message, 'name' => $user->name, 'email' => $user->email]);
            $data['success'] = 1;
            $data['message'] = "Thank you ! We will get back to you Soon...";
            $data['data'] = "";
        }
        return $data;
    }

    /**
  booking trip outside damascus.
     **/
    public function trip_outcity(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'drop_latitude' => 'required',
            'drop_longitude' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'km' => 'required',
            'minutes' => 'required',
            'vehicle_id' => 'required',
            'user_id' => 'required',
            'time' => 'required',
            'date' => 'required',
            'status' => 'required',
            'bags' => 'required',
            'seats' => 'required',
            'direction' => 'required',
            'from' => 'required',
            'to' => 'required',

        ]);
        $errors = $validation->errors();

        $booking = new Bookings();
        $user_type = User::where('id', $request->user_id)->select('user_type')->first();
        $vehicle = VehicleModel::find($request->vehicle_id);

        if (strtoupper($user_type->user_type) == strtoupper('user')) {

            $fare = DB::table('fare_settings')->where('type_id',  $vehicle->type_id)->where('category_id', 'outside damascus')
                ->where(DB::raw('lower(user_type)'), 'like', '%' . strtolower('user') . '%')
                ->first();

            if ($request->direction == '1') {

                $direction = "one way trip";
                $cost = ($request->km * $fare->base_km) + ($request->minutes * $fare->base_time);
                $booking->direction = $direction;
                $booking->cost = $cost;
            } else {


                $direction = "round trip";
                $cost = ($request->km * $fare->base_km) + ($request->minutes * $fare->base_time);
                $booking->direction = $direction;
                $booking->cost = $cost * 2;
            }
        } elseif (strtoupper($user_type->user_type) == strtoupper('organizations')) {


            $fare = DB::table('fare_settings')->where('type_id',  $vehicle->type_id)->where('category_id', 'outside damascus')

                ->where(DB::raw('lower(user_type)'), 'like', '%' . strtolower('organizations') . '%')
                ->first();
            if ($request->direction == '1') {
                $direction = "one way trip";
                $cost = ($request->km  * $fare->base_km) + ($request->minutes  * $fare->base_time);
                $booking->direction = $direction;
                $booking->cost = $cost;
            } else {
                $direction = "round trip";
                $cost = ($request->km * $fare->base_km) * 2 + ($request->minutes * $fare->base_time) * 2;
                $booking->direction = $direction;
                $booking->cost = $cost;
            }
        }

        $booking->pickup_latitude = $request->pickup_latitude;
        $booking->pickup_longitude = $request->pickup_longitude;
        $booking->drop_latitude = $request->drop_latitude;
        $booking->drop_longitude = $request->drop_longitude;
        $booking->category_id = $request->category_id;
        $booking->subcategory_id = $request->subcategory_id;
        $booking->km = $request->km;
        $booking->user_id = $request->user_id;
        $booking->minutes = $request->minutes;
        $booking->vehicle_id = $request->vehicle_id;

        $booking->direction = $direction;

        $booking->travellers = $request->seats;
        $booking->bags = $request->bags;
        $booking->status = $request->status;
        $booking->time = $request->time;
        $booking->from = $request->from;
        $booking->to = $request->to;
        $booking->date = $request->date;
        $booking->save();
        $trip = Bookings::where('id', $booking->id)->get();


        if (count($errors) > 0) {
            $data['message'] = "Unable to Order Trip. Please, check the Details OR Try again Later";
            $data['error'] = true;
            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Your trip has been created";
            $data['data'] = $trip;
            return $data;
        }
    }
    /**
    Cancel the trip with  tour.
     **/
    public function cancle_trip(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'trip_id' => 'required',

        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = "Unable to cancel  your trip. Please, Try again Later!";
            return $data;
        } else {

            $trip = Bookings::where('id', $request->trip_id)->first();
            $trip_tour = Tour::where('trip_id', $trip->id)->first();
            if ($trip->status == "pending") {
                if ($trip_tour == null) {
                    $trip->update(['status' => 'canceld']);
                } else {
                    $trip->update(['status' => 'canceld']);
                    $trip_tour->update(['status' => 'canceld']);
                }
                $data['error'] = false;
                $data['message'] = "Your trip has been canceld";
                return $data;
            } else {
                $data['error'] = true;
                $data['message'] = "Your request has been approved, you cannot cancel it";
                return $data;
            }
        }
    }
    /**
  Cancle Tour.
     **/
    public function cancle_tour(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'tour_id' => 'required',

        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = "Unable to cancel your tour. Please, Try again Later!";
            return $data;
        } else {

            $tour = Tour::find($request->tour_id)->update(['status' => 'canceld']);

            $data['error'] = false;
            $data['message'] = "Your tour has been canceld";
            return $data;
        }
    }
    /**
  Archive of user trips.

     **/
    public function user_trip(Request $request)
    {

        $trips = DB::table('bookings')->select('bookings.*')
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere('status', 'end');
            })->where('deleted_at', null)->where('user_id', $request->user_id)
            ->orderBy('id', 'DESC')->get();

        foreach ($trips as $trip) {
            $id = $trip->id;
            if ($trip->category_id == '2') {
                $tour = Tour::where('trip_id', $id)->where('status', '!=', 'canceld')->get();
                if (count($tour) == '0') {
                    $trip->is_tour = 'No';
                } else {

                    $trip->is_tour = 'Yes';
                    $trip->tour_detail = $tour;
                }

                if ($trip->created_at > Carbon::now()->subHours(24)) {

                    $trip->can_cancle = 'yes';
                } else {
                    $trip->can_cancle = 'no';
                }
            }
        }

        if (count($trips) == '0') {

            $data['error'] = true;
            $data['message'] = 'There are no trips';

            return $data;
        } else {

            $data['error'] = false;
            $data['message'] = 'Listed Successfuly';
            $data['data'] =  $trips;
            return $data;
        }
    }
    /**
   order tour for a trip.
   
   The minimum  tour time  is an hour.

     **/
    public function tour(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'trip_id' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'status' => 'required'
            ]
        );

        $new_time = date('H:i:s', strtotime($request->end_time) - strtotime($request->start_time));
        [$hours, $minutes] = explode(':', $new_time);
        $time = (int) $hours * 60 + (int) $minutes;
        $errors = $validation->errors();
        if ($time < 60) {
            $data['error'] = true;
            $data['message'] = "Sorry, the minimum tour time is an hour";
            return $data;
        } else {
            $price = DB::table('fare_settings')->select('price')->where('price', '!=', null)->first();
            $cost = intval($price->price);
            $tour = new Tour();
            $fare = ($time * $cost) / 60;
            $tour->trip_id = $request->trip_id;
            $tour->start_time = $request->start_time;
            $tour->end_time = $request->end_time;
            $tour->status = $request->status;
            $tour->cost = $fare;
            $tour->save();
            $data['error'] = false;
            $data['message'] = "Your tour has been created";
            $data['data'] = $tour;
            return $data;
        }
    }
    public function get_tour(Request $request)
    {

        $tour = Tour::find($request->tour_id);

        if ($tour) {
            $data['error'] = false;
            $data['message'] = 'tour found';

            $data['data'] = $tour;
            return $data;
        } else {
            $data['error'] = true;
            $data['message'] = 'tour not found';
            return $data;
        }
    }

    /**
  update tour information.
     **/

    public function update_tour(Request $request)
    {
        $tour = Tour::find($request->tour_id);

        if ($tour == null) {
            $data['error'] = true;
            $data['message'] = "trip not found";

            return $data;
        } else {
            $tour->start_time = $request->get('start_time');
            $tour->end_time = $request->get('end_time');
            $tour->status = $request->get('status');

            $tour->save();

            $data['error'] = false;
            $data['message'] = "Tour has been Updated Successfully!";
            $data['data'] = $tour;
            return $data;
        }
    }
    //update driver location
    public function get_location(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
            'device_id' => 'required',
            'user_id' => 'required',
        ]);
        $vehicle = VehicleModel::where('device_number', $request->device_id)->first();

        $location = DB::table('driver_location')

            ->where('driver_id', $request->user_id)
            ->update([
                'latitude' => null,
                'longitude' => null,
                'device_id' => null,
                'vehicle_id' => null,

            ]);
        $location = DB::table('driver_location')

            ->where('driver_id', $request->user_id)
            ->update([
                'latitude' =>  $request->latitude,
                'longitude' => $request->longitude,
                'device_id' =>  $request->device_id,
                'vehicle_id' =>  $vehicle->id,
            ]);

        if ($location == 0) {
            $data['error'] = true;
            $data['message'] = "Location Didnt Update Successfully!";

            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Location has been Updated Successfully!";

            return $data;
        }
    }
    /**
  The user determines the destination of the trip,
  and the price appears on the Eco type,
  according to the type of user, whether it is a user or an organization.
     **/

    public function source_destination(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'drop_latitude' => 'required',
            'drop_longitude' => 'required',
            'km' => 'required',
            'minutes' => 'required',
            'pickup_address' => 'required',
            'dest_address' => 'required',
            'user_id' => 'required',
        ]);
        $user_type = User::where('id', $request->user_id)->select('user_type')->first();

        if (strtoupper($user_type->user_type) == strtoupper('user')) {

            $types = DB::table('vehicle_types')->select(
                'vehicle_types.id',
                'vehicle_types.vehicletype',
                'vehicle_types.icon',
                'fare_settings.base_km',
                'fare_settings.base_time',
                'fare_settings.cost',
                'fare_settings.limit_distance'
            )

                ->where('vehicle_types.vehicletype', 'eco')

                ->join('fare_settings', 'fare_settings.type_id', 'vehicle_types.id')
                ->where('fare_settings.category_id', 'inside city')
                ->where('fare_settings.deleted_at', null)
                ->where(DB::raw('lower(fare_settings.user_type)'), 'like', '%' . strtolower('user') . '%')
                ->get();
        } elseif (strtoupper($user_type->user_type) == strtoupper('Organizations')) {


            $types = DB::table('vehicle_types')->select('vehicle_types.id', 'vehicle_types.vehicletype', 'vehicle_types.icon', 'fare_settings.base_km',             'fare_settings.base_time', 'fare_settings.cost', 'fare_settings.limit_distance')

                ->where('vehicle_types.vehicletype', 'eco')

                ->join('fare_settings', 'fare_settings.type_id', 'vehicle_types.id')
                ->where('fare_settings.category_id', 'inside city')
                ->where('fare_settings.deleted_at', null)
                ->where(DB::raw('lower(fare_settings.user_type)'), 'like', '%' . strtolower('Organizations') . '%')
                ->get();
        }
        foreach ($types as $type) {

            $type->icon = asset('uploads/' . $type->icon);

            if ($request->km <= $type->limit_distance) {
                $type->price = $type->cost;
            } else {
                $type->price = intval(($request->km * $type->base_km) + ($request->minutes * $type->base_time));
            }
        }

        if ($types == null) {
            $data['error'] = true;
            $data['message'] = "";

            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Listed Successfully";
            $data['data'] = $types;
            return $data;
        }
    }
    /**
  The user determines the destination of the trip,
  and the price appears on the Eco type,
  according to the type of user, whether it is a user or an organization.
     **/

    public function source_destination_delay(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'drop_latitude' => 'required',
            'drop_longitude' => 'required',
            'km' => 'required',
            'minutes' => 'required',
            'pickup_address' => 'required',
            'dest_address' => 'required',
            'user_id' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);
        $user_type = User::where('id', $request->user_id)->select('user_type')->first();

        if (strtoupper($user_type->user_type) == strtoupper('user')) {
            $types = DB::table('vehicle_types')->select(
                'vehicle_types.id',
                'vehicle_types.vehicletype',
                'vehicle_types.icon',
                'fare_settings.base_km',
                'fare_settings.base_time',
                'fare_settings.cost',
                'fare_settings.limit_distance'
            )

                ->where('vehicle_types.vehicletype', 'eco')
                ->join('fare_settings', 'fare_settings.type_id', 'vehicle_types.id')
                ->where('fare_settings.category_id', 'inside city')
                ->where('fare_settings.deleted_at', null)
                ->where(DB::raw('lower(fare_settings.user_type)'), 'like', '%' . strtolower('user') . '%')
                ->get();
        } elseif (strtoupper($user_type->user_type) == strtoupper('Organizations')) {

            $types = DB::table('vehicle_types')->select(
                'vehicle_types.id',
                'vehicle_types.vehicletype',
                'vehicle_types.icon',
                'fare_settings.base_km',
                'fare_settings.cost',
                'fare_settings.limit_distance',
                'fare_settings.base_time'
            )

                ->where('vehicle_types.vehicletype', 'eco')
                ->join('fare_settings', 'fare_settings.type_id', 'vehicle_types.id')
                ->where('fare_settings.category_id', 'inside city')
                ->where('fare_settings.deleted_at', null)
                ->where(DB::raw('lower(fare_settings.user_type)'), 'like', '%' . strtolower('Organizations') . '%')
                ->get();
        }
        foreach ($types as $type) {
            $type->icon = asset('uploads/' . $type->icon);

            if ($request->km <= $type->limit_distance) {
                $type->price = $type->cost;
            } else {
                $type->price = intval($request->km * $type->base_km + $request->minutes * $type->base_time);
            }
        }
        if ($types == null) {
            $data['error'] = true;
            $data['message'] = "";

            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Listed Successfully";
            $data['data'] = $types;
            return $data;
        }
    }
    /**
    View features found in the ECO type.

     **/
    public function type_option(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',

        ]);
        $options = CarOptions::where('type_id', $request->id)->where('is_enable', '1')->get();
        if ($options->isempty()) {
            $data['error'] = true;
            $data['message'] = "There Is No Options";

            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Listed Successfully!";
            $data['data'] = $options;

            return $data;
        }
    }
    /**
   View the approved trip for the user to track the driver
   and view the vehicle informations.
     **/
    public function accept_trip(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);


        $insid_trips = Bookings::with('driver:id,first_name,last_name,phone,profile_image')
            ->with('vehicle:id,color,car_model,vehicle_image,device_number')
            ->where('user_id', $request->user_id)->whereIn('status', ['pending', 'accepted'])
            ->where('category_id', 1)
            ->where('request_type', 'delayed')
            ->get(['id', 'user_id', 'vehicle_id', 'driver_id', 'status', 'request_type', 'pickup_latitude', 'pickup_longitude', 'drop_latitude', 'drop_longitude', 'category_id', 'from', 'to', 'dest_addr', 'pickup_addr']);

        $outside_trips = Bookings::with('driver:id,first_name,last_name,phone,profile_image')
            ->with('vehicle:id,color,car_model,vehicle_image,device_number')
            ->where('user_id', $request->user_id)->whereIn('status', ['started', 'accepted'])
            ->where('category_id', 2)
            ->get(['id', 'user_id', 'vehicle_id', 'driver_id', 'status', 'request_type', 'pickup_latitude', 'pickup_longitude', 'drop_latitude', 'drop_longitude', 'category_id', 'from', 'to', 'dest_addr', 'pickup_addr']);


        foreach ($insid_trips as $trip) {
            if (empty($trip->driver)) {
                $trip->makeHidden('driver');
                $trip->makeHidden('vehicle');
            } else {
                $trip->driver->profile_image = asset('uploads/' . $trip->driver->profile_image);
            }

            //  $trip->vehicle->vehicle_image = asset('uploads/' . $trip->vehicle->vehicle_image);
        }
        foreach ($outside_trips as $outside_trip) {
            if (empty($outside_trip->driver)) {
                $outside_trip->makeHidden('driver');
                $outside_trip->makeHidden('vehicle');
            } else {
                $outside_trip->driver->profile_image = asset('uploads/' . $outside_trip->driver->profile_image);
            }

            //  $trip->vehicle->vehicle_image = asset('uploads/' . $trip->vehicle->vehicle_image);
        }

        if ($insid_trips->isEmpty() && $outside_trips->isEmpty()) {

            $data['error'] = true;
            $data['message'] = "There are no requests yet";
            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Listed Succesfully ";
            $data['data'] = ['insid_trips' => $insid_trips, 'outside_trips' => $outside_trips];
            return $data;
        }
    }
    /**
 book trip and sending a notification to the drivers
 closest to the user's location.
     **/
    public function book_now(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
            'category_id' => 'required',
            'km' => 'required',
            'minutes' => 'required',
            'user_id' => 'required',
            'status' => 'required',
            'pickup_addr' => 'required',
            'dest_addr' => 'required',
            'type_id' => 'required',
            'cost' => 'required',
            'request_type' => 'required',
            'drop_latitude' => 'required',
            'drop_longitude' => 'required',

        ]);


        $errors = $validation->errors();

        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = "Unable To Order Trip ";
            return $data;
        } else {
            $radius = 2;

            $nearest_driver = DB::table('driver_location')->select(
                DB::raw('driver_location.id as id,driver_location.*,vehicles.vehicle_image, vehicles.color, vehicles.car_number, vehicles.car_model,users.*, vehicle_types.vehicletype, ( 3959 * acos( 
             cos( radians(' . $request->latitude . ') ) *
             cos( radians( latitude) ) *
             cos( radians( longitude ) - 
             radians(' . $request->longitude . ') ) + 
             sin( radians(' . $request->latitude . ') ) *
             sin( radians( latitude ) ) ) ) as distance')
            )->having('distance', '<', $radius)

                ->join('users', 'users.id', 'driver_location.driver_id')->where('users.is_active', 'active')->where('users.in_service', 'off')


                ->join('vehicles', 'vehicles.id', 'driver_location.vehicle_id')


                ->where('vehicles.in_service', '=', '1')
                ->where('vehicles.type_id', $request->type_id)
                ->join('vehicle_types', 'vehicle_types.id', 'vehicles.type_id')
                ->get();



            if (isset($request->option_id)) {

                if (count($request->option_id) > 0) {
                    $options_ids = json_encode($request->option_id);
                } else {
                    $options_ids = null;
                }
            }




            $booking = new Bookings();

            $booking->pickup_addr = $request->pickup_addr;
            $booking->pickup_latitude = $request->latitude;
            $booking->pickup_longitude = $request->longitude;
            $booking->drop_latitude = $request->drop_latitude;
            $booking->drop_longitude = $request->drop_longitude;
            $booking->dest_addr = $request->dest_addr;
            $booking->type_id = $request->type_id;
            //$booking->option_id = $options_ids;
            $booking->cost = $request->cost;
            $booking->category_id = $request->category_id;
            $booking->km = $request->km;
            $booking->minutes = $request->minutes;
            $booking->user_id = $request->user_id;
            $booking->status = $request->status;
            $booking->order_time = $request->order_time;
            $booking->request_type = $request->request_type;
            $booking->save();
            $time_out = Value::where('name', 'time_out')->first()->value;
            //Artisan::call('queue:work');
            dispatch(new CancelingTripJob($booking->id))->delay(Carbon::now()->addMinutes($time_out));
            $str1 = " لديك طلب لحظي من";
            $str2 = "الى";
            $body = $str1 . ' ' . $request->pickup_addr . ' ' . $str2 . ' ' . $request->dest_addr;

            foreach ($nearest_driver as $driver) {
                $driver_token = DB::table('users')->where('id', $driver->driver_id)->select('device_token')->first();
                if ($driver_token) {
                    $this->send_notification($driver_token->device_token, 'Dimond Line', $body);
                }
            }

            //$bookings=Bookings::where('user_id',$request->user_id)->where('category_id','1')
            //->where('status','pending')->where('request_type','moment')->get();


            $data['error'] = false;
            $data['message'] = "Looking For A nearby Car ";
            $data['data'] = ["id" => $booking->id];
            return $data;
        }
    }

    public function book_now_delayed(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
            'category_id' => 'required',
            'km' => 'required',
            'minutes' => 'required',
            'user_id' => 'required',
            'status' => 'required',
            'pickup_addr' => 'required',
            'dest_addr' => 'required',
            'drop_latitude' => 'required',
            'drop_longitude' => 'required',
            'type_id' => 'required',
            'cost' => 'required',
            'request_type' => 'required',
            'date' => 'required',
            'time' => 'required',
            'drop_latitude' => 'required',
            'drop_longitude' => 'required',
            // 'option_id' => 'required',
        ]);
        $errors = $validation->errors();

        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = "Unable To Order Trip ";
            return $data;
        } else {
            $radius = 2;

            $nearest_driver = DB::table('driver_location')->select(
                DB::raw('driver_location.id as id,driver_location.*,vehicles.vehicle_image, vehicles.color, vehicles.car_number, vehicles.car_model,users.*, vehicle_types.vehicletype, ( 3959 * acos( 
             cos( radians(' . $request->latitude . ') ) *
             cos( radians( latitude) ) *
             cos( radians( longitude ) - 
             radians(' . $request->longitude . ') ) + 
             sin( radians(' . $request->latitude . ') ) *
             sin( radians( latitude ) ) ) ) as distance')
            )->having('distance', '<', $radius)
                ->join('vehicles', 'vehicles.id', 'driver_location.vehicle_id')
                ->where('vehicles.category_id', '=', '1')
                ->where('vehicles.in_service', '=', '1')
                ->where('vehicles.type_id', $request->type_id)
                ->join('vehicle_types', 'vehicle_types.id', 'vehicles.type_id')
                ->join('users', 'users.id', 'driver_location.driver_id')->where('users.is_active', 'active')->where('users.in_service', 'off')
                ->get();

            if (isset($request->option_id)) {

                if (count($request->option_id) > 0) {
                    $options_ids = json_encode($request->option_id);
                } else {
                    $options_ids = null;
                }
            }




            $booking = new Bookings();

            $booking->pickup_latitude = $request->latitude;
            $booking->pickup_longitude = $request->longitude;
            $booking->pickup_addr = $request->pickup_addr;
            $booking->dest_addr = $request->dest_addr;
            $booking->drop_latitude = $request->drop_latitude;
            $booking->drop_longitude = $request->drop_longitude;
            $booking->type_id = $request->type_id;
            $booking->option_id = $options_ids;
            $booking->cost = $request->cost;
            $booking->category_id = $request->category_id;
            $booking->km = $request->km;
            $booking->minutes = $request->minutes;
            $booking->user_id = $request->user_id;
            $booking->status = $request->status;
            $booking->date = $request->date;
            $booking->time = $request->time;
            $booking->request_type = $request->request_type;
            $booking->save();
            $str1 = "لديك طلب مؤجل داخل المدينة من";
            $str2 = "\r\nإلى";
            $str3 = "\r\n بتاريخ";
            $str4 = " عند الساعة";
            $body = $str1 . ' ' . $request->pickup_addr . ' ' . $str2 . ' ' . $request->dest_addr
                . '' . $str3 . ' ' . $request->date . ' ' . $str4 . '' . $request->time;

            foreach ($nearest_driver as $driver) {
                $driver_token = DB::table('users')->where('id', $driver->driver_id)->select('device_token')->first();
                if ($driver_token) {
                    $this->send_notification($driver_token->device_token, 'Dimond Line', $body);
                }
            }

            $data['error'] = false;
            $data['message'] = "Looking For A nearby Car ";
            $data['data'] = ["id" => $booking->id];
            return $data;
        }
    }
    public function order_trip_now(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'drop_latitude' => 'required',
            'drop_longitude' => 'required',
            'pickup_location' => 'required',
            'vehicle_id' => 'required',
            'category_id' => 'required',
            'km' => 'required',
            'minutes' => 'required',
            'user_id' => 'required',
            'status' => 'required',
        ]);
        $driver = DB::table('driver_vehicle')->where('vehicle_id', $request->vehicle_id)->pluck('driver_id')->toarray();

        $user = User::whereIn('id', $driver)->where('is_active', 'active')->where('in_service', 'off')->first();
        $vehicle = VehicleModel::find($request->vehicle_id);
        $fare = DB::table('fare_settings')->where('type_id',  $vehicle->type_id)->where('category_id', 'inside city')->first();
        $cost = $fare->base_km * $request->km + $fare->base_time * $request->minutes;
        $booking = new Bookings();
        $booking->pickup_latitude = $request->pickup_latitude;
        $booking->pickup_longitude = $request->pickup_longitude;
        $booking->drop_latitude = $request->drop_latitude;
        $booking->drop_longitude = $request->drop_longitude;
        $booking->pickup_location = $request->pickup_location;
        $booking->vehicle_id = $request->vehicle_id;
        $booking->category_id = $request->category_id;
        $booking->km = $request->km;
        $booking->minutes = $request->minutes;
        $booking->user_id = $request->user_id;
        $booking->driver_id = $user->id;
        $booking->cost = $cost;
        $booking->status = $request->status;
        $booking->save();

        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['message'] = "Unable to Order Trip. Please, check the Details OR Try again Later";
            $data['error'] = true;

            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Your trip has been created";

            return $data;
        }
    }
    public function review(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'ratings' => 'required|numeric|min:0|max:5',

            'trip_id' => 'required',
            'user_id' => 'required',

        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = "Unable to Save your Reviews. Please, Try again Later!";
            $data['data'] = "";
        } else {

            $user_type = User::where('id', $request->get('user_id'))->select('user_type')->first();

            if ($user_type->user_type == "user" || $user_type->user_type == "orgnizations") {

                $type = "driver";
            } elseif ($user_type->user_type == "driver" || $user_type->user_type == "external_driver") {
                $type = "user";
            }

            $review = ReviewModel::create([

                'user_id' => $request->get('user_id'),
                'type' => $type,
                'booking_id' => $request->get('trip_id'),
                'ratings' => $request->get('ratings'),
                'review_text' => $request->get('review_text'),
            ]);

            $data['error'] = false;
            $data['message'] = "Thank you. Your Review helps us Improve our Services.";
        }
        return $data;
    }


    public function contact_us()
    {

        $contact = ContactModel::get();

        if (count($contact) > 0) {
            $data['error'] = false;
            $data['message'] = "Listed Successfully!";
            $data['data'] = $contact;

            return $data;
        } else {
            $data['error'] = true;
            $data['message'] = "There Is No Informaion";
            return $data;
        }
    }


    public function SignOut(Request $request)
    {

        $user = User::where('id', $request->user_id)->first();



        if ($user !== null) {
            $user->device_token = null;
            $user->save();

            $data['error'] = false;
            $data['message'] = "SignOut Successfully";
            return $data;
        } else {
            $data['error'] = true;
            $data['message'] = "There Is Something Wrong";
            return $data;
        }
    }

    function user_trips()
    {
        $user_id = auth()->user()->id;
        $user_trips = Bookings::with('vehicle:id,vehicle_image,color,device_number,car_model')
            ->with('driver:id,first_name,last_name,phone,profile_image')
            ->where('user_id', $user_id)
            ->where('category_id', 1)
            ->whereNotIn('status', ['ended', 'canceld'])
            ->get(['id', 'status', 'request_type', 'pickup_latitude', 'pickup_longitude', 'drop_latitude', 'drop_longitude', 'vehicle_id', 'driver_id']);

        return response()->json([
            'success' => true,
            'data' => $user_trips
        ]);
    }


    public function book_later(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'source_address' => 'required',
            'dest_address' => 'required',
        ]);
        $errors = $validation->errors();
        if (count($errors) > 0 || $request->get('booking_type') != 1) {
            $data['success'] = 0;
            $data['message'] = "Unable to Process your Ride Request. Please, Try again Later!";
            $data['data'] = "";
        } else {
            $booking = Bookings::create([
                'customer_id' => $request->get('user_id'),
                'pickup_addr' => $request->get('source_address'),
                'dest_addr' => $request->get('dest_address'),
                'travellers' => $request->get('no_of_persons'),
            ]);

            $book = Bookings::find($booking->id);
            $book->fcm_id = $request->get('fcm_id');
            $book->source_lat = $request->get('source_lat');
            $book->source_long = $request->get('source_long');
            $book->dest_lat = $request->get('dest_lat');
            $book->dest_long = $request->get('dest_long');
            $book->journey_date = $request->get('journey_date');
            $book->journey_time = $request->get('journey_time');
            $book->booking_type = 1;
            $book->accept_status = 0; //0=yet to accept, 1= accept
            $book->ride_status = null;
            $book->vehicle_typeid = $request->vehicle_typeid;
            $book->save();
            $vehicle_typeid = $request->vehicle_typeid;
            $this->book_later_notification($book->id, $vehicle_typeid);
            $data['success'] = 1;
            $data['message'] = "Your Request has been Submitted Successfully.";
            $data['data'] = array('booking_id' => $booking->id);
            // browser notification to driver,admin,customer

        }
        return $data;
    }

    public function update_destination(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));
        $validation = Validator::make($request->all(), [
            'dest_address' => 'required',
        ]);
        $errors = $validation->errors();
        if (count($errors) > 0 || $booking == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Process your Ride Request. Please, Try again Later !";
            $data['data'] = "";
        } else {
            $d_lat = $booking->getMeta('dest_lat');
            $d_long = $booking->getMeta('dest_long');
            $old = $booking->dest_addr;
            $booking->dest_addr = $request->get('dest_address');
            $booking->dest_lat = $request->get('dest_lat');
            $booking->dest_long = $request->get('dest_long');
            $booking->save();
            $this->update_dest_notification($booking->id, $old, $d_lat, $d_long);
            $this->ride_ongoing_notification($booking->id);
            $data['success'] = 1;
            $data['message'] = "Your Destination has  been Updated Successfully.";
            $data['data'] = ['rideinfo' => array(
                "user_id" => $request->get('user_id'),
                "booking_id" => $request->get('booking_id'),
                "dest_address" => $request->get('dest_address'),
                "dest_lat" => $request->get('dest_lat'),
                "dest_long" => $request->get('dest_long'),
            )];
        }
        return $data;
    }

    public function ride_ongoing_notification($id)
    {
        $booking = Bookings::find($id);
        $data['success'] = 1;
        $data['key'] = "ride_ongoing_notification";
        $data['message'] = 'Data Received.';
        $data['title'] = "Heading Towards [ " . $booking->dest_addr . " ]";
        $data['description'] = "Ongoing Ride From [ " . $booking->pickup_addr . " ]";
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array(
            'user_id' => $booking->customer_id,
            'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
            'booking_id' => $id,
            'source_address' => $booking->pickup_addr,
            'dest_address' => $booking->dest_addr,
            'start_lat' => $booking->getMeta('start_lat'),
            'start_long' => $booking->getMeta('start_long'),
            'approx_timetoreach' => $booking->getMeta('approx_timetoreach'),
            'user_name' => $booking->customer->name,
            'user_profile' => $booking->customer->getMeta('profile_pic'),
        );
        if ($booking->customer->getMeta('fcm_id') != null) {
            // PushNotification::app('appNameAndroid')
            //     ->to($booking->customer->getMeta('fcm_id'))
            //     ->send($data);

            $push = new PushNotification('fcm');
            $push->setMessage($data)
                ->setApiKey(env('server_key'))
                ->setDevicesToken([$booking->customer->getMeta('fcm_id')])
                ->send();
        }
        if ($booking->driver->getMeta('fcm_id') != null) {
            // PushNotification::app('appNameAndroid')
            //     ->to($booking->driver->getMeta('fcm_id'))
            //     ->send($data);

            $push = new PushNotification('fcm');
            $push->setMessage($data)
                ->setApiKey(env('server_key'))
                ->setDevicesToken([$booking->driver->getMeta('fcm_id')])
                ->send();
        }
    }

    public function update_dest_notification($id, $old, $d_lat, $d_long)
    {
        $booking = Bookings::find($id);
        $data['success'] = 1;
        $data['key'] = "update_destination_notification";
        $data['message'] = 'Data Received.';
        $data['title'] = "Destination Updated for the Ongoing Ride";
        $data['description'] = "Refresh the Route.";
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array('rideinfo' => array(
            'user_id' => $booking->customer_id,
            'booking_id' => $booking->id,
            'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
            'user_name' => $booking->customer->name,
            'user_profile' => $booking->customer->getMeta('profile_pic'),
            'source_address' => $booking->pickup_addr,
            'dest_address' => $old,
            'source_lat' => $booking->getMeta('source_lat'),
            'source_long' => $booking->getMeta('source_long'),
            'dest_lat' => $d_lat,
            'dest_long' => $d_long,
            'new_dest_address' => $booking->dest_addr,
            'approx_timetoreach' => $booking->getMeta('approx_timetoreach'),
            'new_dest_lat' => $booking->getMeta('dest_lat'),
            'new_dest_long' => $booking->getMeta('dest_long')
        ));
        if ($booking->driver->getMeta('fcm_id') != null) {
            // PushNotification::app('appNameAndroid')
            //     ->to($booking->driver->getMeta('fcm_id'))
            //     ->send($data);

            $push = new PushNotification('fcm');
            $push->setMessage($data)
                ->setApiKey(env('server_key'))
                ->setDevicesToken([$booking->driver->getMeta('fcm_id')])
                ->send();
        }
    }


    public function ride_history(Request $request)
    {
        $bookings = Bookings::where('customer_id', $request->get('customer_id'))->get();

        if ($bookings->toArray() != null) {
            $data['success'] = 1;
            $data['message'] = "Data Received.";
            if (Hyvikk::get('dis_format') == 'meter') {
                $unit = 'm';
            }
            if (Hyvikk::get('dis_format') == 'km') {
                $unit = 'km';
            }

            foreach ($bookings as $book) {
                if ($book->getMeta('total_kms') != null) {
                    $total_kms = $book->getMeta('total_kms') . " " . $unit;
                } else {
                    $total_kms = "";
                }
                $details[] = array('booking_id' => $book->id, 'user_id' => $book->customer_id, 'book_date' => date('Y-m-d', strtotime($book->created_at)), 'book_time' => date('H:i:s', strtotime($book->created_at)), 'source_address' => $book->pickup_addr, 'source_time' => date('Y-m-d H:i:s', strtotime($book->getMeta('ridestart_timestamp'))), 'dest_address' => $book->dest_addr, 'dest_time' => date('Y-m-d H:i:s', strtotime($book->getMeta('rideend_timestamp'))), 'driving_time' => $book->getMeta('driving_time'), 'total_kms' => $total_kms, 'amount' => $book->getMeta('total'), 'ride_status' => $book->getMeta('ride_status'));
            }
            $data['data'] = array('rides' => $details);
        } else {
            $data['success'] = 0;
            $data['message'] = "Unable to Receive Rides History. Please, Try again Later!";
            $data['data'] = "";
        }

        return $data;
    }

    public function user_single_ride_info(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));
        if (Hyvikk::get('dis_format') == 'meter') {
            $unit = 'm';
        }
        if (Hyvikk::get('dis_format') == 'km') {
            $unit = 'km';
        }

        if ($booking == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Receive Ride Details. Please, Try again Later !";
            $data['data'] = "";
        } else {
            $rideinfo = array(
                'user_id' => $booking->customer_id, 'booking_id' => $booking->id, 'source_address' => $booking->pickup_addr, 'dest_address' => $booking->dest_addr, 'source_time' => date('H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                'dest_time' => date('H:i:s', strtotime($booking->getMeta('rideend_timestamp'))),
                'book_date' => date('Y-m-d', strtotime($booking->created_at)),
                'book_time' => date('H:i:s', strtotime($booking->created_at)),
                'driving_time' => $booking->getMeta('driving_time'),
                'total_kms' => $booking->getMeta('total_kms') . " " . $unit,
                'amount' => $booking->getMeta('total'),
                'ride_status' => $booking->getMeta('ride_status'),
            );
            $d = User::find($booking->driver_id);
            $reviews = ReviewModel::where('booking_id', $request->get('booking_id'))->first();
            $avg = ReviewModel::where('driver_id', $booking->driver_id)->avg('ratings');

            $driver = array(
                'driver_id' => $booking->driver_id,
                'driver_name' => $d->name,
                'profile_pic' => $d->getMeta('driver_image'),
                'ratings' => round($avg, 2),
            );

            if ($reviews == null) {
                $review = new \stdClass;
            } else {

                $review = array('user_id' => $reviews->user_id, 'booking_id' => $reviews->booking_id, 'ratings' => $reviews->ratings, 'review_text' => $reviews->review_text, 'date' => date('Y-m-d', strtotime($reviews->created_at)));
            }
            $data['success'] = 1;
            $data['message'] = "Data Received.";
            $data['data'] = array('rideinfo' => $rideinfo, 'driver_details' => $driver, 'ride_review' => $review, 'fare_breakdown' => array(
                'base_fare' => Hyvikk::fare(strtolower(str_replace(' ', '', $booking->vehicle->types->vehicletype)) . '_base_fare'),
                'ride_amount' => $booking->getMeta('total'), 'extra_charges' => 0
            ));
        }
        return $data;
    }

    public function get_reviews(Request $request)
    {
        $reviews = ReviewModel::where('driver_id', $request->get('driver_id'))->where('booking_id', '!=', $request->get('booking_id'))->get();

        if ($reviews->toArray() != null) {
            $data['success'] = 1;
            $data['message'] = "Data Received.";
            foreach ($reviews as $r) {
                $review[] = array('user_id' => $r->user->id, 'user_name' => $r->user->name, 'profile_pic' => $r->user->getMeta('profile_pic'), 'booking_id' => $r->booking_id, 'ratings' => $r->ratings, 'review_text' => $r->review_text, 'date' => date('Y-m-d', strtotime($r->created_at)));
            }
            $data['data'] = ['driver_reviews' => $review];
        } else {
            $data['success'] = 0;
            $data['message'] = "Unable to Receive Driver's Reviews. Please, Try again Later!";
            $data['data'] = "";
        }
        return $data;
    }

    public function user_logout(Request $request)
    {

        $user = User::find($request->get('user_id'));
        $user->login_status = 0;
        $user->is_available = 0;
        $user->save();
        $user->token()->revoke();
        if ($user->login_status == 0) {
            $data['success'] = 1;
            $data['message'] = "You have Logged out Successfully.";
            $data['data'] = "";
        } else {
            $data['success'] = 0;
            $data['message'] = "Unable to Logout. Please, Try again Later!";
            $data['data'] = "";
        }

        return $data;
    }

    public function book_now_notification($id, $type_id)
    {

        $booking = Bookings::find($id);
        $data['success'] = 1;
        $data['key'] = "book_now_notification";
        $data['message'] = 'Data Received.';
        $data['title'] = "New Ride Request (Book Now)";
        $data['description'] = "Do you want to Accept it ?";
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array('riderequest_info' => array(
            'user_id' => $booking->customer_id,
            'booking_id' => $booking->id,
            'source_address' => $booking->pickup_addr,
            'dest_address' => $booking->dest_addr,
            'book_date' => date('Y-m-d'),
            'book_time' => date('H:i:s'),
            'journey_date' => date('d-m-Y'),
            'journey_time' => date('H:i:s'),
            'accept_status' => $booking->accept_status
        ));
        if ($type_id == null) {
            $vehicles = VehicleModel::get()->pluck('id')->toArray();
        } else {
            $vehicles = VehicleModel::where('type_id', $type_id)->get()->pluck('id')->toArray();
        }
        $drivers = User::where('user_type', 'D')->get();

        // $test = PushNotification::app('appNameAndroid')
        //     ->to("fCsWgScV2qU:APA91bGeT1OKws4zk-1u09v83XFrnmEaIidPRl4-sTTOBbPvHXrq6lkRBLCfQFMml5v3gB1zbS0PDttKwEhvWC1fUQVhWhutVxKeVaxvPofD6XgMQn9UPJCKFnrB8h3amL0bhfFh4s98")
        //     ->send($data);
        // dd($test);

        foreach ($drivers as $d) {
            if (in_array($d->vehicle_id, $vehicles)) {

                if ($d->getMeta('fcm_id') != null && $d->getMeta('is_available') == 1 && $d->getMeta('is_on') != 1) {

                    // PushNotification::app('appNameAndroid')
                    //     ->to($d->getMeta('fcm_id'))
                    //     ->send($data);

                    $push = new PushNotification('fcm');
                    $push->setMessage($data)
                        ->setApiKey(env('server_key'))
                        ->setDevicesToken([$d->getMeta('fcm_id')])
                        ->send();
                }
            }
        }
    }
}
