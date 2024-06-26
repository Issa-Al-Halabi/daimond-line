<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.

Design and developed by Hyvikk Solutions <https://hyvikk.com/>

*/

namespace App\Http\Controllers\Api;

use App\Enums\BookingStatus;
use App\Http\Traits\SendNotification;
use App\Events\TripStatusChangedEvent;
use App\Http\Controllers\Controller;
use App\Model\BookingIncome;
use Illuminate\Support\Facades\Hash;
use App\Model\BookingPaymentsModel;
use App\Model\Bookings;
use App\Model\FareSettings;
use App\Model\Hyvikk;
use App\Model\IncomeModel;
use App\Model\ReasonsModel;
use App\Model\Tour;
use App\Model\Value;
use App\Model\Complaints;
use App\Model\ReviewModel;
use App\Model\WalletModel;
use App\Model\Expense;
use App\Model\DriverLocation;
use App\Model\EcashPayment;
use App\Model\CarOptions;
use App\Model\User;
use App\Model\VehicleTypeModel;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DriversApi extends Controller
{

    use SendNotification;


    // join us api for external driver
    public function join_us(Request $request)
    {


        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email'    => 'nullable|email|unique:users',

            //'device_token' => 'required',
            'phone' => [
                Rule::unique('users')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),

                'required',
                'numeric',
            ],
            'password' => 'required|min:8',
            'car_mechanic' => 'required|mimes:jpeg,png,jpg,gif|max:2000|max_filename_length:50',
            'car_insurance' => 'required|mimes:jpeg,png,jpg,gif||max:2000|max_filename_length:50',
        ]);
        $errors = $validation->errors();


        if ($validation->passes()) {
            $user = new User();

            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            if ($request->hasfile('car_mechanic') == true) {
                $file = $request->file('car_mechanic');
                $destinationPath = './uploads';
                $extension = $request->file('car_mechanic')->getClientOriginalExtension();
                $fileName1 = Str::uuid() . '.' . $extension;
                $file->move($destinationPath, $fileName1);
                $user->car_mechanic = $fileName1;
            }
            if ($request->hasfile('car_insurance') == true) {
                $file = $request->file('car_insurance');
                $destinationPath = './uploads';
                $extension = $request->file('car_insurance')->getClientOriginalExtension();
                $fileName1 = Str::uuid() . '.' . $extension;
                $file->move($destinationPath, $fileName1);
                $user->car_insurance = $fileName1;
            }
            $user->phone = $request->get('phone');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->user_type = 'external_driver';
            $user->is_active = 'pending';
            $user->in_service = 'off';
            $user->device_token = $request->get('device_token');
            $user->api_token = str_random(60);
            $user->deleted_at = null;
            //   print('zena-');
            $user->save();
            $user->assignRole('driver');
            //  print('zena-');
            $driver_location = new DriverLocation();
            $driver_location->driver_id = $user->id;
            //    print('zena-');
            $driver_location->save();
            //    print('zena-');
            $driver_wallet = new WalletModel();
            $driver_wallet->driver_id = $user->id;
            $driver_wallet->save();

            // print('zena-');


            $data['error'] = false;
            $data['message'] = "You have Registered Successfully!";

            $data['data'] = ['api_token' => $user->api_token, 'user' => $user];

            return  $data;

            //return response()->json(['message' => 'Validation passed'], 200); 

        } else {
            $data['message'] =  current(current($errors))[0];

            $data['error'] = true;

            // return $data;
            return response()->json(['errors' => $validation->errors()], 200);
        }
    }
    //-------------------------------------------------
    public function DeleteDriverAccount(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'driver_id' => 'required',
            'phone' => 'required',
            'password' => 'required',

        ]);
        $driver = User::where('id', $request->driver_id)->select('user_type', 'phone', 'password', 'id')->first();



        if ($driver->phone == $request->phone && Hash::check($request->password, $driver->password)) {
            if ($driver->user_type == 'external_driver') {

                foreach ($driver->vehicles as $vehicles) {

                    $vehicles->delete();
                }
                $driver->vehicles()->detach();
                try {

                    DB::table('driver_location')->where('driver_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
                try {
                    ReviewModel::where('user_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }

                try {
                    WalletModel::where('driver_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
                try {
                    User::where('id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
                try {
                    Bookings::where('driver_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
            } else {
                try {
                    ReviewModel::where('user_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }

                try {

                    DB::table('expense')->where('driver_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
                try {

                    DB::table('driver_location')->where('driver_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }

                try {
                    WalletModel::where('driver_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
                try {
                    User::where('id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
                try {
                    Bookings::where('driver_id', $request->driver_id)->delete();
                } catch (Exception $e) {
                    return  "Not success";
                }
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
    //-------------------------------------------------
    public function complete_register(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'user_id' => 'required',
            'car_image' => 'required|mimes:jpeg,png,jpg,gif||max:2000|max_filename_length:50',
            'personal_identity' => 'required|mimes:jpeg,png,jpg,gif||max:2000|max_filename_length:50',
            'driving_certificate' => 'required|mimes:jpeg,png,jpg,gif||max:2000|max_filename_length:50',


        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['message'] = current(current($errors))[0];
            $data['error'] = true;
            $data['data'] = $errors;

            return $data;
        } else {
            $user = User::find($request->user_id);

            if ($request->hasfile('car_image') == true) {
                $file = $request->file('car_image');
                $destinationPath = './uploads';
                $extension = $request->file('car_image')->getClientOriginalExtension();
                $fileName1 = Str::uuid() . '.' . $extension;
                $file->move($destinationPath, $fileName1);
                $user->car_image = $fileName1;
            }
            if ($request->hasfile('personal_identity') == true) {
                $file = $request->file('personal_identity');
                $destinationPath = './uploads';
                $extension = $request->file('personal_identity')->getClientOriginalExtension();
                $fileName1 = Str::uuid() . '.' . $extension;
                $file->move($destinationPath, $fileName1);
                $user->personal_identity = $fileName1;
            }
            if ($request->hasfile('driving_certificate') == true) {
                $file = $request->file('driving_certificate');
                $destinationPath = './uploads';
                $extension = $request->file('driving_certificate')->getClientOriginalExtension();
                $fileName1 = Str::uuid() . '.' . $extension;
                $file->move($destinationPath, $fileName1);
                $user->driving_certificate = $fileName1;
            }
            $user->save();

            $data['message'] = " Completed Register Successfuly";
            $data['error'] = false;
            return $data;
        }
    }
    //----------------------------------------------------------
    /**
  check the driver's status to start receiving requests.
     **/
    public function driver_status(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'driver_id' => 'required',

        ]);
        $driver = DB::table('users')->where('id', $request->driver_id)->select('is_active', 'id', 'device_token')->first();

        if ($driver->is_active == 'pending') {
            $data['error'] = true;
            $data['message'] = "Wait for your request to be approved";
            return $data;
        } elseif ($driver->is_active == 'active') {

            $vechicle = DB::table('driver_vehicle')->where('driver_id', $request->driver_id)->select('vehicle_id')
                ->join('vehicles', 'vehicles.id', 'driver_vehicle.vehicle_id')->where('in_service', '1')->first();

            $device_number = DB::table('vehicles')->where('id',  $vechicle->vehicle_id)->select('device_number')->first();


            $data['error'] = false;
            $data['message'] = "Listed Successfully";
            $data['data'] = ["status" => $driver->is_active, "device_number" => $device_number->device_number];

            return $data;
        }
    }
    /**
  Show the nearby internal and external     s to the driver
  in case he did not receive other requests.
     **/
    public function driver_trip(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'driver_id' => 'required',

        ]);
        $driver_status = DB::table('users')->where('id', $request->driver_id)->select('in_service')->first();

        $driver_location = DB::table('driver_location')->where('driver_id', $request->driver_id)->Select('latitude', 'longitude', 'vehicle_id')->first();

        $driver_vechicle_type = DB::table('vehicles')->where('id', $driver_location->vehicle_id)->select('type_id')->first();

        $radius = 2;

        // driver trip with user details
        if ($driver_status->in_service == "off") {


            $nearest_trip = DB::table('bookings')->select(
                DB::raw('bookings.*,users.first_name,users.last_name,users.phone,users.profile_image,users.id as user_id ,( 3959 * acos( 
             cos( radians(' . $driver_location->latitude . ') ) *
             cos( radians( pickup_latitude) ) *
             cos( radians( pickup_longitude ) - 
             radians(' . $driver_location->longitude . ') ) + 
             sin( radians(' . $driver_location->latitude . ') ) *
             sin( radians( pickup_latitude ) ) ) ) as distance')
            )->having('distance', '<', $radius)

                ->join('users', 'users.id', 'bookings.user_id')
                ->where('category_id', '1')
                ->where('status', BookingStatus::pending)
                ->orderBy('id', 'DESC')
                ->get();

            $trip_outcity = DB::table('bookings')->select('bookings.*', 'users.first_name', 'users.last_name', 'users.phone', 'users.profile_image')->where('driver_id', $request->driver_id)->where('bookings.category_id', '2')->where('status', BookingStatus::pending)
                ->where('driver_id', $request->driver_id)->where('bookings.category_id', '2')
                ->join('subcategories', 'subcategories.id', 'bookings.subcategory_id')
                ->join('users', 'users.id', 'bookings.user_id')
                ->orderBy('id', 'DESC')
                ->get();


            foreach ($trip_outcity as $trip) {
                $trip->km = intval($trip->km);
                $trip->minutes = intval($trip->minutes);
                $trip->profile_image = asset('uploads/' . $trip->profile_image);
                $tour = Tour::where('trip_id', $trip->id)->where('status', BookingStatus::pending)->get();
                $expenses = Expense::where('trip_id', $trip->id)->where('expense_type', 'constant')->get();

                foreach ($expenses as $expense) {
                    $expense->type = json_decode($expense->type);
                    $expense->price = json_decode($expense->price);
                }

                if (count($tour) == '0') {
                    $trip->is_tour = 'No';
                } else {

                    $trip->is_tour = 'Yes';
                    $trip->tour_detail = $tour;
                }
                if (count($expenses) == '0') {
                    $trip->has_expense = 'No';
                } else {

                    $trip->has_expense = 'Yes';
                    $trip->expense_detail = $expenses;
                }
            }
            foreach ($nearest_trip as $trip) {
                $trip->km = intval($trip->km);
                $trip->minutes = intval($trip->minutes);
                $trip->profile_image = asset('uploads/' . $trip->profile_image);


                if ($trip->option_id != null) {

                    $trip->options = "Yes";
                    $trip->option_id = explode(',', $trip->option_id);
                } else {

                    $trip->options = "No";
                }
            }
        } else {
            $nearest_trip = [];
            $trip_outcity = [];
        }


        $all_trips = DB::table('bookings')->select('bookings.*')->where('driver_id', $request->driver_id)->where('status', BookingStatus::ended)
            ->orderBy('id', 'DESC')
            ->get();

        if (count($nearest_trip) < 0 && count($trip_outcity) < 0 &&  count($all_trips) < 0) {
            $data['error'] = true;
            $data['message'] = " There is no trips";
            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Listed Successfully";
            $data['data'] =  ["nearest_trips" => $nearest_trip, "trips_outcity" => $trip_outcity, "all_trips" => $all_trips];

            return $data;
        }
    }

    public function Trip_OutCity(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'driver_id' => 'required',

        ]);
        $trip_outcity = DB::table('bookings')->select('bookings.*', 'users.first_name', 'users.last_name', 'users.phone')->where('driver_id', $request->driver_id)->where('bookings.category_id', '2')
            ->where(function ($query) {
                $query->where('status', BookingStatus::started)
                    ->orwhere('status', BookingStatus::arrived)
                    ->orWhere('status', BookingStatus::accepted);
            })->join('subcategories', 'subcategories.id', 'bookings.subcategory_id')
            ->join('users', 'users.id', 'bookings.user_id')
            ->orderBy('id', 'DESC')
            ->get();


        $trip_inside = DB::table('bookings')->select('bookings.*', 'users.first_name', 'users.last_name', 'users.phone')->where('driver_id', $request->driver_id)

            ->where('bookings.category_id', '1')->where(function ($query) {
                $query->where('status', BookingStatus::arrived)
                    ->orWhere('status', BookingStatus::accepted);
            })->join('users', 'users.id', 'bookings.user_id')
            ->orderBy('id', 'DESC')
            ->get();

        if ($trip_outcity->isEmpty() && $trip_inside->isEmpty()) {
            $data['error'] = true;
            $data['message'] = " There is no started or accepted trips";
            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Listed Successfully";
            $data['data'] =  ["trips_outcity" => $trip_outcity, "trip_inside" => $trip_inside];

            return $data;
        }
    }
    public function accept_trip(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'driver_id' => 'required',
            'trip_id' => 'required',
            'device_number' => 'required',

        ]);
        $vehicel_id = DB::table('vehicles')->where('device_number', $request->device_number)->first();

        $trip = Bookings::where('id', $request->trip_id)->first();


        if ($trip->status == BookingStatus::canceld) {
            $data['error'] = true;
            $data['message'] = "Trip has been Canceld";
            return $data;
        }
        if ($trip->status == BookingStatus::accepted) {
            $data['error'] = true;
            $data['message'] = "Trip Already Accepted";
            return $data;
        } else {

            $trip->status = BookingStatus::accepted;
            $trip->driver_id = $request->driver_id;
            $trip->vehicle_id = $vehicel_id->id;
            $trip->save();

            $user_trip = DB::table('bookings')->where('id', $request->trip_id)->select('user_id')->first();
            $user = DB::table('users')->where('id', $user_trip->user_id)->select('device_token')->first();
            $data['error'] = false;
            $data['message'] = " The trip has been accepted";
            $driver = User::where('id', $trip->driver_id)->first();

            $all_data =  [
                'id' => $trip->id,
                'user_id' => $trip->user_id,
                'status' => $trip->status,
                'pickup_latitude' => $trip->pickup_latitude,
                'pickup_longitude' => $trip->pickup_longitude,
                'drop_latitude' => $trip->drop_latitude,
                'drop_longitude' => $trip->drop_longitude,
                'driver_first_name' => $driver->first_name,
                'driver_last_name' => $driver->last_name,
                'driver_phone' => $driver->phone,
                'driver_profile_image' => asset('uploads/' . $driver->profile_image),
                'vehicel_image' =>   asset('uploads/' . $vehicel_id->vehicle_image),
                'vehicel_device_number' => $vehicel_id->device_number,
                'vehicel_car_model' => $vehicel_id->car_model,
                'vehicel_color' => $vehicel_id->color,
            ];
            // event(new TripStatusChangedEvent($all_data));

            $this->send_notification(
                $user->device_token,
                'Diamond-Line',
                __("booking.trip_accepted"),
                $all_data,
            );
            return $data;
        }
    }
    /**
  The driver sends a notification when he arrive to 
  the user's location.

     **/

    public function driver_notification(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'driver_id' => 'required',
            'trip_id' => 'required',
        ]);

        $trip = Bookings::with(['driver', 'vehicle'])->where('id', $request->trip_id)->first();

        $user = DB::table('users')->where('id', $trip->user_id)->select('device_token')->first();
        $trip->update(['status' => BookingStatus::arrived]);

        $all_data =  [
            'id' => $trip->id,
            'user_id' => $trip->user_id,
            'status' => $trip->status,
            'pickup_latitude' => $trip->pickup_latitude,
            'pickup_longitude' => $trip->pickup_longitude,
            'drop_latitude' => $trip->drop_latitude,
            'drop_longitude' => $trip->drop_longitude,
            'driver_first_name' => $trip->driver->first_name,
            'driver_last_name' => $trip->driver->last_name,
            'driver_phone' => $trip->driver->phone,
            'driver_profile_image' => asset('uploads/' . $trip->driver->profile_image),
            'vehicel_image' =>   asset('uploads/' . $trip->vehicle->vehicle_image),
            'vehicel_device_number' => $trip->vehicle->device_number,
            'vehicel_car_model' => $trip->vehicle->car_model,
            'vehicel_color' => $trip->vehicle->color,
        ];
        // event(new TripStatusChangedEvent($all_data));
        $this->send_notification(
            $user->device_token,
            'Diamond-Line',
            __("booking.trip_arrived"),
            $all_data,
        );
    }
    /**
  
When the driver starts a trip, the trip status changes
.and the driver state becomes on('in service').
     **/

    public function trip_started(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'trip_id' => 'required',
            'start_time' => 'required',
            'driver_id' => 'required'

        ]);
        $driver_status = User::where('id', $request->driver_id)->update([
            "in_service" => "on"
        ]);

        $trip = Bookings::with(['driver', 'vehicle'])->where('id', $request->trip_id)->first();

        if ($trip->status == BookingStatus::started) {
            return $data = ['error' => true, 'message' => " The trip alrady has started"];
        }

        $trip->start_time = $request->start_time;
        $trip->status = BookingStatus::started;
        $trip->save();
        $data['error'] = false;
        $data['message'] = " The trip has been started";


        $all_data =  [
            'id' => $trip->id,
            'user_id' => $trip->user_id,
            'status' => $trip->status,
            'pickup_latitude' => $trip->pickup_latitude,
            'pickup_longitude' => $trip->pickup_longitude,
            'drop_latitude' => $trip->drop_latitude,
            'drop_longitude' => $trip->drop_longitude,
            'driver_first_name' => $trip->driver->first_name,
            'driver_last_name' => $trip->driver->last_name,
            'driver_phone' => $trip->driver->phone,
            'driver_profile_image' => asset('uploads/' . $trip->driver->profile_image),
            'vehicel_image' =>   asset('uploads/' . $trip->vehicle->vehicle_image),
            'vehicel_device_number' => $trip->vehicle->device_number,
            'vehicel_car_model' => $trip->vehicle->car_model,
            'vehicel_color' => $trip->vehicle->color,
        ];
        // event(new TripStatusChangedEvent($all_data));
        $this->send_notification(
            $trip->user->device_token,
            'Diamond-Line',
            __("booking.trip_started"),
            $all_data,
        );

        return $data;
    }

    public function trip_ended_waiting_for_payment(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'trip_id' => 'required',
            'end_time' => 'required',
            'km' => 'required',
        ]);

        $trip = Bookings::with(['driver', 'vehicle'])->where('id', $request->trip_id)->first();
        if ($trip->status == BookingStatus::wait_for_payment) {
            return $data = ['error' => true, 'message' => "The trip already waiting for payment"];
        }


        $user_type = DB::table('users')->where('id', $trip->user_id)->select('user_type')->first();
        $driver_type = DB::table('users')->where('id', $trip->driver_id)->select('user_type', 'device_token')->first();
        $vehicle_type = DB::table('vehicles')->where('id', $trip->vehicle_id)->select('type_id')->first();

        // to add admin_fare percentage
        $admin_fare_value = Value::where('name', 'admin_fare')->first();
        $admin_fare_value = isset($admin_fare_value) ? $admin_fare_value->value : 0;

        [$hours, $minutes] = explode(':', $trip->start_time);
        [$hours1, $minutes1] = explode(':', $request->end_time);
        $start = (int) $hours * 60 + (int) $minutes;
        $end = (int) $hours1 * 60 + (int) $minutes1;

        $total_minutes = $end - $start;

        if ($total_minutes < 0) {
            $start = $start - 1440;
            $total_minutes = $end - $start;
        }

        //inside city trip
        if ($trip->request_type == "moment" || $trip->request_type == "delayed") {

            // inside city
            $fare = DB::table('fare_settings')->where('type_id',  $trip->type_id)->where('category_id', 'inside city')
                ->where(DB::raw('lower(user_type)'), 'like', '%' . strtoupper($user_type->user_type) . '%')
                ->first();
        } else {
            // outside damascus
            $fare = DB::table('fare_settings')->where('type_id',  $vehicle_type->type_id)->where('category_id', 'outside damascus')
                ->where(DB::raw('lower(user_type)'), 'like', '%' . strtoupper($user_type->user_type) . '%')
                ->first();
        }

        if ($trip->request_type == "") {
            $cost = ($request->km * $fare->base_km) + ($total_minutes * $fare->base_time);
        }
        // limit_distance at which the fare is fixed
        else if ($request->km <= $fare->limit_distance) {

            $cost = $fare->cost;
        }
        //After this limit distance the cost is calculated in this way
        else {
            $cost = ($request->km * $fare->base_km) + ($total_minutes * $fare->base_time);
        }

        $cost = $cost + ($cost * $admin_fare_value) / 100;

        $trip->cost = intval($cost);
        $trip->end_time = $request->end_time;
        $trip->status = BookingStatus::wait_for_payment;
        $trip->km = $request->km;
        $trip->save();

        //driver type
        //admin fare of the internal driver
        if ($driver_type->user_type == "driver") {
            $internal_external_fare = Value::where('name', 'internal_fare')->first();
        }
        //external driver
        elseif ($driver_type->user_type == "external_driver") {
            $internal_external_fare = Value::where('name', 'external_fare')->first();
        } else {
            $internal_external_fare = 10;
        }

        //admin fare
        $admin_fare = ($cost * $internal_external_fare->value) / 100;


        $data['error'] = false;
        $data['message'] = "The trip is waiting for payment";
        $data['data'] = ["new_cost" => $trip->cost, "admin_fare" => $admin_fare];


        $all_data =  [
            'id' => $trip->id,
            'user_id' => $trip->user_id,
            'status' => $trip->status,
            'pickup_latitude' => $trip->pickup_latitude,
            'pickup_longitude' => $trip->pickup_longitude,
            'drop_latitude' => $trip->drop_latitude,
            'drop_longitude' => $trip->drop_longitude,
            'driver_first_name' => $trip->driver->first_name,
            'driver_last_name' => $trip->driver->last_name,
            'driver_phone' => $trip->driver->phone,
            'driver_profile_image' => asset('uploads/' . $trip->driver->profile_image),
            'vehicel_image' =>   asset('uploads/' . $trip->vehicle->vehicle_image),
            'vehicel_device_number' => $trip->vehicle->device_number,
            'vehicel_car_model' => $trip->vehicle->car_model,
            'vehicel_color' => $trip->vehicle->color,
            'cost' => (string)$trip->cost
        ];
        // event(new TripStatusChangedEvent($all_data));

        $this->send_notification(
            $trip->user->device_token,
            'Diamond-Line',
            __("booking.trip_wait_for_payment"),
            $all_data,
        );

        return $data;
    }
    public function user_choosed_payment_method(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'trip_id' => 'required',
            'payment_method' => 'required',
        ]);
        $trip = Bookings::with(['driver'])->where('id', $request->trip_id)->first();

        if ($request->payment_method == "cash") {
            $trip->status = BookingStatus::ended;
            $trip->save();
            $message = __("payment.payment_method_cash");
        } else if ($request->payment_method == "e_payment") {
            $message = __("payment.payment_method_e_payment");
        } else {
            $message = __("payment.payment_method") .  $request->payment_method;
        }

        $this->send_notification(
            $trip->driver->device_token,
            'Diamond-Line',
            $message,
            [
                "trip_id" =>  $request->trip_id,
                "payment_method" => $request->payment_method,
                "message" => $message,
            ],
        );
        return $request;
    }
    public function trip_ended(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'trip_id' => 'required',
            'end_time' => 'required',
            'km' => 'required',
        ]);

        //  Calculation of new minutes

        $trip_started = Bookings::with(['driver', 'vehicle'])->where('id', $request->trip_id)->first();
        $vehicle_type = DB::table('vehicles')->where('id', $trip_started->vehicle_id)->select('type_id')->first();

        [$hours, $minutes] = explode(':', $trip_started->start_time);
        [$hours1, $minutes1] = explode(':', $request->end_time);
        $start = (int) $hours * 60 + (int) $minutes;
        $end = (int) $hours1 * 60 + (int) $minutes1;

        $total_minutes = $end - $start;

        if ($total_minutes < 0) {
            $start = $start - 1440;
            $total_minutes = $end - $start;
        }

        //  Calculation of new cost  Because the cost varies according to the user type
        $user_type = DB::table('users')->where('id', $trip_started->user_id)->select('user_type')->first();
        $driver_type = DB::table('users')->where('id', $trip_started->driver_id)->select('user_type', 'device_token')->first();

        $driver_wallet = WalletModel::where('driver_id', $trip_started->driver_id)->first();

        $admin_wallet = Value::where('name', 'admin_wallet')->first();

        //Minimum driver wallet
        $minimum_wallet = Value::where('name', 'minimum_wallet')->first();
        //admin fare of the internal driver

        $internal = Value::where('name', 'internal_fare')->first();
        //admin fare of the external driver
        $external = Value::where('name', 'external_fare')->first();

        // to add admin_fare percentage
        $admin_fare_value = Value::where('name', 'admin_fare')->first();
        $admin_fare_value = isset($admin_fare_value) ? $admin_fare_value->value : 0;

        //inside city trip
        if ($trip_started->request_type == "moment" || $trip_started->request_type == "delayed") {

            // inside city
            $fare = DB::table('fare_settings')->where('type_id',  $trip_started->type_id)->where('category_id', 'inside city')
                ->where(DB::raw('lower(user_type)'), 'like', '%' . strtoupper($user_type->user_type) . '%')
                ->first();
        } else {
            // outside damascus
            $fare = DB::table('fare_settings')->where('type_id',  $vehicle_type->type_id)->where('category_id', 'outside damascus')
                ->where(DB::raw('lower(user_type)'), 'like', '%' . strtoupper($user_type->user_type) . '%')
                ->first();
        }

        if ($trip_started->request_type == "") {
            $cost = ($request->km * $fare->base_km) + ($total_minutes * $fare->base_time);
        }
        // limit_distance at which the fare is fixed
        else if ($request->km <= $fare->limit_distance) {

            $cost = $fare->cost;
        }
        //After this limit distance the cost is calculated in this way
        else {
            $cost = ($request->km * $fare->base_km) + ($total_minutes * $fare->base_time);
        }

        $cost = $cost + ($cost * $admin_fare_value) / 100;

        $trip_started->cost = intval($cost);
        $trip_started->km = $request->km;
        $trip_started->status = BookingStatus::ended;
        $trip_started->end_time = $request->end_time;
        $trip_started->save();

        User::where("id", $trip_started->driver_id)->update([
            "in_service" => "off"
        ]);

        //driver type
        //admin fare of the internal driver
        if ($driver_type->user_type == "driver") {
            $internal_external_fare = Value::where('name', 'internal_fare')->first();
        }
        //external driver
        elseif ($driver_type->user_type == "external_driver") {
            $internal_external_fare = Value::where('name', 'external_fare')->first();
        } else {
            $internal_external_fare = 10;
        }
        //admin fare
        $admin_fare = ($cost * $internal_external_fare->value) / 100;


        //new admin wallet
        $new_admin_wallet = $admin_wallet->value + $admin_fare;

        //update admin wallet
        Value::where('name', 'admin_wallet')->update(['value' => $new_admin_wallet]);


        //driver fare
        $new_driver_wallet =  $driver_wallet->amount - $cost + $admin_fare;

        //update driver wallet

        WalletModel::where('driver_id', $trip_started->driver_id)->update(['amount' => $new_driver_wallet]);

        //send notification if the driver's wallet is equal to the minimum wallet

        if ($new_driver_wallet == $minimum_wallet->value) {

            $body = "شارف رصيدك على الانتهاء يرجى شحن المحفظة";
            $this->send_notification($driver_type->device_token, 'Dimond Line', $body);
        }
        //send notification if the driver's wallet is less than to the minimum wallet
        //change driver status to out of service.
        elseif ($new_driver_wallet < $minimum_wallet->value) {
            $body = "يرجى شحن المحفظة";
            $this->send_notification($driver_type->device_token, 'Dimond Line', $body);
            User::where('id', $trip_started->driver_id)->update(['in_service' => 'out of service']);
        }

        if ($driver_type->user_type == "driver") {
            //admin fare
            $admin_fare = ($trip_started->cost * $internal->value) / 100;
        } elseif ($driver_type->user_type == "external_driver") {
            //admin fare
            $admin_fare = ($trip_started->cost * $external->value) / 100;
        }

        if ($trip_started) {
            $data['error'] = false;
            $data['message'] = " The trip has been ended";
            $data['data'] = ["new_cost" => $trip_started->cost, "admin_fare" => $admin_fare];

            $all_data =  [
                'id' => $trip_started->id,
                'user_id' => $trip_started->user_id,
                'status' => $trip_started->status,
                'pickup_latitude' => $trip_started->pickup_latitude,
                'pickup_longitude' => $trip_started->pickup_longitude,
                'drop_latitude' => $trip_started->drop_latitude,
                'drop_longitude' => $trip_started->drop_longitude,
                'driver_first_name' => $trip_started->driver->first_name,
                'driver_last_name' => $trip_started->driver->last_name,
                'driver_phone' => $trip_started->driver->phone,
                'driver_profile_image' => asset('uploads/' . $trip_started->driver->profile_image),
                'vehicel_image' =>   asset('uploads/' . $trip_started->vehicle->vehicle_image),
                'vehicel_device_number' => $trip_started->vehicle->device_number,
                'vehicel_car_model' => $trip_started->vehicle->car_model,
                'vehicel_color' => $trip_started->vehicle->color,
                'cost' => (string)$trip_started->cost
            ];
            // event(new TripStatusChangedEvent($all_data));
            $this->send_notification(
                $trip_started->user->device_token,
                'Diamond-Line',
                __("booking.trip_ended"),
                $all_data,
            );
            return $data;
        } else {
            $data['error'] = true;
            $data['message'] = "The trip hasnt been ended";
            return $data;
        }
    }

    //changable  expenses for trip from internal driver


    public function started_inside_trips()
    {
        $driver_id = auth()->user()->id;
        $bookings = Bookings::where('driver_id', $driver_id)->where('status', BookingStatus::started)
            ->get(['id', 'status', 'pickup_latitude', 'pickup_longitude', 'drop_latitude', 'drop_longitude']);

        if (!empty($bookings)) {
            return response()->json([
                'error' => false,
                'data' =>   $bookings
            ]);
        } else {
            return response()->json([
                'error' => true,
                'data' => []
            ]);
        }
    }

    public function trip_expense(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'type' => 'required',
            'price' => 'required',
            'trip_id' => 'required',
            'driver_id' => 'required',

        ]);
        $errors = $validation->errors();
        $type = json_encode($request->type);
        $price = json_encode($request->price);
        $trip_expense =  new Expense();
        $trip_expense->trip_id = $request->trip_id;
        $trip_expense->driver_id = $request->driver_id;
        $trip_expense->type = $type;
        $trip_expense->expense_type = "changeable";

        $trip_expense->price = $price;
        $trip_expense->save();

        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = "Expenses havent sent";

            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Expenses have been sent";

            return $data;
        }
    }
    //drivers charge wallet using transfer method.
    public function charge_wallet(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'method' => 'required',

            'driver_id' => 'required',
            'transfare_image' => 'required',
            'new_amount' => 'required',
        ]);

        $errors = $validation->errors();

        if (count($errors) > 0) {
            $data['message'] = "Unable to charge wallet. Please, check the Details OR Try again Later";
            $data['error'] =   true;

            return $data;
        } else {

            $wallet =  WalletModel::where('driver_id', $request->driver_id)->first();
            $minimum_wallet = Value::where('name', 'minimum_wallet')->first();

            if ($request->hasfile('transfare_image') == true) {
                $file = $request->file('transfare_image');
                $destinationPath = './uploads';
                $extension = $request->file('transfare_image')->getClientOriginalExtension();
                $fileName1 = Str::uuid() . '.' . $extension;
                $file->move($destinationPath, $fileName1);
                $wallet->transfare_image = $fileName1;
            }
            $wallet->method = $request->method;

            $wallet->driver_id = $request->driver_id;
            $wallet->new_amount = $request->new_amount;
            $wallet->status = '0';


            $wallet->save();

            $data['error'] = false;
            $data['message'] = "The balance has been charged successfully";
            $data['data'] = $wallet;
            return $data;
        }
    }

    public function check_status(Request $request)
    {


        $validation = Validator::make($request->all(), [
            'order_id' => 'required',

        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['error'] = true;


            return $data;
        } else {
            $order_status = EcashPayment::where('id', $request->order_id)->first();

            $data['error'] = false;
            $data['message'] = "Success";
            $data['data'] = $order_status;

            return $data;
        }
    }
    public function delete_order(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'order_id' => 'required',

        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['error'] = true;


            return $data;
        } else {
            $order = EcashPayment::where('id', $request->order_id)->delete();

            $data['error'] = false;
            $data['message'] = " Deleted Successfully";


            return $data;
        }
    }
    public function send_complaint(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'description' => 'required',
        ]);
        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = "The complaint has not been sent";

            return $data;
        } else {

            $complaint = new Complaints();
            $complaint->user_id = $request->user_id;
            $complaint->description = $request->description;
            $complaint->save();
            $data['error'] = false;
            $data['message'] = "Complaint has been sent successfully";
            return $data;
        }
    }


    public function create_orderid(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'driver_id' => 'required',
            'amount' => 'required',
        ]);

        $errors = $validation->errors();
        if (count($errors) > 0) {
            $data['error'] = true;
            $data['message'] = "Please, check the Details OR Try again Later";

            return $data;
        } else {

            $payment = new EcashPayment();
            $payment->driver_id = $request->driver_id;
            $payment->amount = $request->amount;
            $payment->save();
            if ($payment) {
                $data['error'] = false;
                $data['message'] = "Information created Sucessfully";
                $data["data"] =  $payment;

                return $data;
            } else {
                $data['error'] = true;
                $data['message'] = "Please, check the Details OR Try again Later";

                return $data;
            }
        }
    }

    function ecash_payment(Request $request)
    {

        $data = json_decode(request()->getContent(), true);

        $isSuccess = $data['IsSuccess'];

        $message = $data['Message'];

        $orderRef = $data['OrderRef'];

        $transactionNo = $data['TransactionNo'];

        $amount = $data['Amount'];

        $token = $data['Token'];

        $MerchantId = 'T26REL';
        $MerchantSecret = '6VFDA69N466ZZ8KAOVO081J2VG0CKG8BSRLUN3NIFAB13PU3S50P6VGBRPG3A3A8';


        $myToken = md5($MerchantId . $MerchantSecret . $transactionNo . $amount . $orderRef);
        $myToken1 = strtoupper($myToken);

        if ($myToken1 !== $token) {

            $data['error'] = true;
            return $data;
        } else {

            $order_ref = EcashPayment::where('id', $orderRef)->first();
            $order_ref->is_success = $isSuccess;
            $order_ref->message = $message;
            $order_ref->amount = $amount;
            $order_ref->token = $token;
            $order_ref->transaction_number = $transactionNo;
            $order_ref->save();



            if ($order_ref->is_success == 1) {

                $wallet =  WalletModel::where('driver_id', $order_ref->driver_id)->first();


                $wallet->method = 'E-cash';
                $wallet->transfare_image = "null";

                $wallet->new_amount = $order_ref->amount;
                $wallet->status = '0';
                $wallet->save();
            }
            $data['error'] = false;
            $data['data'] = $order_ref;

            return $data;
        }
    }
    //get driver wallet.
    public function driver_wallet(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'driver_id' => 'required',

        ]);
        $driver_wallet = DB::table('wallet')->where('driver_id', $request->driver_id)->first();

        if (empty($driver_wallet)) {
            $data['error'] = true;
            $data['message'] = " Please Charging Your  Wallet";

            return $data;
        } else {
            $data['error'] = false;
            $data['message'] = "Listed Successfullly";
            $data['data'] = $driver_wallet;
            return $data;
        }
    }

    public function change_availability(Request $request)
    {
        $driver = User::find($request->get('user_id'));
        if ($driver != null) {
            $driver->is_available = $request->get('availability');
            $driver->save();
            if ($request->get('availability') == '0') {
                $status = 'Offline';
            }
            if ($request->get('availability') == '1') {
                $status = 'Online';
            }
            $data['success'] = 1;
            $data['message'] = "You are now " . $status;
            $data['data'] = "";
        } else {
            $data['success'] = 0;
            $data['message'] = "Unable to Change Availability. Please, Try again Later!";
            $data['data'] = "";
        }

        return $data;
    }

    public function ride_requests()
    {
        $bookings = Bookings::meta()->where('bookings_meta.key', '=', 'ride_status')->where('bookings_meta.value', '=', null)->get();
        // dd($bookings);
        // $bookings = Bookings::get();

        if ($bookings->toArray() == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Receive Ride Requests. Please, Try again Later!";
            $data['data'] = "";
        } else {
            $details1 = array();
            $details2 = array();
            foreach ($bookings as $book) {
                if ($book->booking_type == 0) {

                    if (strtotime($book->journey_date . " " . $book->journey_time) >= strtotime("-5 minutes")) {

                        $details1[] = array(
                            'booking_id' => $book['id'],
                            'book_date' => date('Y-m-d', strtotime($book['created_at'])),
                            'book_time' => date('H:i:s', strtotime($book['created_at'])),
                            'source_address' => $book['pickup_addr'],
                            'dest_address' => $book['dest_addr'],
                            'journey_date' => $book->getMeta('journey_date'),
                            'journey_time' => $book->getMeta('journey_time')
                        );
                    }
                }
                if ($book->booking_type == 1) {
                    if (strtotime($book->journey_date . " " . $book->journey_time) >= strtotime("now")) {
                        $details2[] = array(
                            'booking_id' => $book['id'],
                            'book_date' => date('Y-m-d', strtotime($book['created_at'])),
                            'book_time' => date('H:i:s', strtotime($book['created_at'])),
                            'source_address' => $book['pickup_addr'],
                            'dest_address' => $book['dest_addr'],
                            'journey_date' => $book->getMeta('journey_date'),
                            'journey_time' => $book->getMeta('journey_time')
                        );
                    }
                }
            }

            $details = array_merge($details1, $details2);
            $data['success'] = 1;
            $data['message'] = "Data Received.";
            $data['data'] = array('ride_requests' => $details);
        }
        return $data;
    }

    //for driver
    public function single_ride_request(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));

        if ($booking == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Receive Ride Request Info. Please, Try again Later !";
            $data['data'] = "";
        } else {
            if ($booking->getMeta('accept_status') == '1') {
                $user_details = array('user_id' => $booking->customer_id, 'user_name' => $booking->customer->name, 'mobno' => $booking->customer->getMeta('mobno'), 'profile_pic' => $booking->customer->getMeta('profile_pic'));
            } else {
                $user_details = array();
            }
            $data['success'] = 1;
            $data['message'] = "Data Received.";
            $data['data'] = array(
                'riderequest_info' => array(
                    'booking_id' => $booking->id,
                    'source_address' => $booking->pickup_addr,
                    'dest_address' => $booking->dest_addr,
                    'book_date' => date('Y-m-d', strtotime($booking->created_at)),
                    'book_time' => date('H:i:s', strtotime($booking->created_at)),
                    'journey_date' => $booking->getMeta('journey_date'),
                    'journey_time' => $booking->getMeta('journey_time'),
                    'accept_status' => $booking->getMeta('accept_status'),
                    'approx_timetoreach' => $booking->getMeta('approx_timetoreach')
                ),
                'user_details' => $user_details
            );
        }
        return $data;
    }

    public function reject_ride_request(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));

        //for book later
        if ($request->get('book_type') == 1) {
            $count = User::meta()
                ->where(function ($query) {
                    $query->where('users_meta.key', '=', 'is_available')
                        ->where('users_meta.value', '=', 1)
                        ->where('users_meta.deleted_at', '=', null);
                })->count();
            if ($count == 0) {
                $this->reject_ride_notification($booking->customer_id);
            }
        }

        //for book now
        if ($request->get('book_type') == 0) {
            $drivers = User::meta()
                ->where(function ($query) {
                    $query->where('users_meta.key', '=', 'is_available')
                        ->where('users_meta.value', '=', 1)
                        ->where('users_meta.deleted_at', '=', null);
                })->get();
            $count = 0;
            foreach ($drivers as $driver) {

                if ($driver->is_on == null || $driver->is_on == 0) {

                    $count++;
                }
            }
            if ($count == 0) {
                $this->reject_ride_notification($booking->customer_id);
            }
        }
        if ($booking == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Reject Ride Request. Please, Try again Later !";
            $data['data'] = "";
        } else {
            $data['success'] = 1;
            $data['message'] = "You have Rejected a Ride Request.";
            $data['data'] = array('booking_id' => $booking->id);
        }
        return $data;
    }

    public function reject_ride_notification($id)
    {
        $customer = User::find($id);

        $data['success'] = 1;
        $data['key'] = "driver_unavailable_notification";
        $data['message'] = 'Data Received.';
        $data['title'] = "No Drivers Available. Please, Try Again Later!";
        $data['description'] = "";
        $data['timestamp'] = date('Y-m-d H:i:s');
        if ($customer->getMeta('fcm_id') != null) {
            // PushNotification::app('appNameAndroid')
            //     ->to($customer->getMeta('fcm_id'))
            //     ->send($data);

            $push = new PushNotification('fcm');
            $push->setMessage($data)
                ->setApiKey(env('server_key'))
                ->setDevicesToken([$customer->getMeta('fcm_id')])
                ->send();
        }
    }

    public function accept_ride_request(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));

        if ($booking == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Receive Ride Request Info. Please, Try again Later !";
            $data['data'] = "";
        } else {
            $u = User::find($request->get('user_id'));
            $booking->accept_status = 1;
            $booking->driver_id = $request->get('user_id');
            if ($u->getMeta('vehicle_id') != null) {
                $booking->vehicle_id = $u->getMeta('vehicle_id');

                $booking->ride_status = "Upcoming";
                $booking->approx_timetoreach = $request->get('approx_timetoreach');
                $booking->save();
                $user_details = array('user_id' => $booking->customer_id, 'user_name' => $booking->customer->name, 'mobno' => $booking->customer->getMeta('mobno'), 'profile_pic' => $booking->customer->getMeta('profile_pic'));
                $this->accept_ride_notification($booking->id, $request->lat, $request->long);
                $data['success'] = 1;
                $data['message'] = "You have Accepted the Ride  Request. Pick up the Customer on Time !";
                $data['data'] = array(
                    'riderequest_info' => array(
                        'booking_id' => $booking->id,
                        'source_address' => $booking->pickup_addr,
                        'dest_address' => $booking->dest_addr,
                        'book_date' => date('Y-m-d', strtotime($booking->created_at)),
                        'book_time' => date('H:i:s', strtotime($booking->created_at)),
                        'journey_date' => $booking->getMeta('journey_date'),
                        'journey_time' => $booking->getMeta('journey_time'),
                        'accept_status' => $booking->getMeta('accept_status'),
                        'approx_timetoreach' => $booking->getMeta('approx_timetoreach')
                    ),
                    'user_details' => $user_details
                );
            } else {
                $data['success'] = 0;
                $data['message'] = "You can not Accept Ride Requests. Please, Contact App Admin !";
                $data['data'] = "";
            }
        }
        return $data;
    }

    public function accept_ride_notification($id, $lat, $long)
    {
        $booking = Bookings::find($id);
        $rating = ReviewModel::where('booking_id', $id)->first();
        $avg = ReviewModel::where('driver_id', $booking->driver_id)->avg('ratings');

        if ($rating != null) {
            $r = $rating->ratings;
        } else {
            $r = "";
        }
        if ($booking->vehicle_id == null) {
            $vehicle_number = "";
            $vehicle_name = "";
        } else {
            $vehicle_number = $booking->vehicle->license_plate;
            $vehicle_name = $booking->vehicle->maker->make . $booking->vehicle->vehiclemodel->model;
        }
        $data['success'] = 1;
        $data['key'] = "accept_ride_notification";
        $data['message'] = 'Data Received.';
        $data['title'] = "Your Ride Request has been Accepted.";
        $data['description'] = $booking->pickup_addr . "-" . $booking->dest_addr . ": Driver Name " . $booking->driver->name;
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array(
            'riderequest_info' => array(
                'user_id' => $booking->customer_id,
                'booking_id' => $id,
                'source_address' => $booking->pickup_addr,
                'dest_address' => $booking->dest_addr,
                'book_date' => date('d-m-Y', strtotime($booking->created_at)),
                'book_time' => date('H:i:s', strtotime($booking->created_at)),
                'journey_date' => $booking->getMeta('journey_date'),
                'journey_time' => $booking->getMeta('journey_time'),
                'accept_status' => $booking->getMeta('accept_status'),

            ),
            'driver_details' => array(
                'driver_id' => $booking->driver_id,
                'driver_name' => $booking->driver->name,
                'profile_pic' => $booking->driver->getMeta('driver_image'),
                'vehicle_number' => $vehicle_number,
                'vehicle_name' => $vehicle_name,
                'ratings' => round($avg, 2),
                'mobile_number' => $booking->driver->getMeta('phone'),
                'lat' => $lat,
                'long' => $long,
            ),
        );
        if ($booking->customer->getMeta('fcm_id') != null) {
            // PushNotification::app('appNameAndroid')
            //     ->to($booking->customer->getMeta('fcm_id'))
            // // ->to('fCsWgScV2qU:APA91bGeT1OKws4zk-1u09v83XFrnmEaIidPRl4-sTTOBbPvHXrq6lkRBLCfQFMml5v3gB1zbS0PDttKwEhvWC1fUQVhWhutVxKeVaxvPofD6XgMQn9UPJCKFnrB8h3amL0bhfFh4s98')
            //     ->send($data);

            $push = new PushNotification('fcm');
            $push->setMessage($data)
                ->setApiKey(env('server_key'))
                ->setDevicesToken([$booking->customer->getMeta('fcm_id')])
                ->send();
        }
    }

    public function cancel_ride_request(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));
        $reason = $request->get('reason');
        if ($booking == null || $reason == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Cancel Ride. Please, Try again Later !";
            $data['data'] = "";
        } else {

            $booking->ride_status = "Cancelled";
            $booking->reason = $reason;
            $booking->save();
            $this->cancel_ride_notification($booking->id);
            $data['success'] = 1;
            $data['message'] = "Your Ride has been Cancelled Successfully.";
            $data['data'] = array('booking_id' => $booking->id);
        }
        return $data;
    }

    public function cancel_ride_notification($id)
    {
        $booking = Bookings::find($id);

        $data['success'] = 1;
        $data['key'] = "cancel_ride_notification";
        $data['message'] = 'Oops, Your Ride has been Cancelled by the Driver.';
        $data['title'] = "Ride Cancelled - " . $id;
        $data['description'] = $booking->pickup_addr . " - " . $booking->dest_addr . ". Reason is " . $booking->reason;
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array(
            'booking _id' => $id,
            'source_address' => $booking->pickup_addr,
            'dest_address' => $booking->dest_addr,
            'book_date' => date('d-m-Y', strtotime($booking->created_at)),
            'book_time' => date('H:i:s', strtotime($booking->created_at)),
            'journey_date' => $booking->getMeta('journey_date'),
            'journey_time' => $booking->getMeta('journey_time'),
            'ride_status' => $booking->ride_status,
            'reason' => $booking->reason,
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
    }

    public function driver_rides(Request $request)
    {
        $bookings = Bookings::where('driver_id', $request->get('driver_id'))->get();

        if ($bookings == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Receive Rides. Please, Try again Later !";
            $data['data'] = "";
        } else {
            $u_rides = array();
            $c_rides = array();
            $cancel = array();
            if (Hyvikk::get('dis_format') == 'meter') {
                $unit = 'm';
            }
            if (Hyvikk::get('dis_format') == 'km') {
                $unit = 'km';
            }
            foreach ($bookings as $u) {
                if ($u->getMeta('ride_status') == "Upcoming") {
                    $u_rides[] = array(
                        'booking_id' => $u->id,
                        'book_date' => date('Y-m-d', strtotime($u->created_at)),
                        'book_time' => date('H:i:s', strtotime($u->created_at)),
                        'source_address' => $u->pickup_addr,
                        'dest_address' => $u->dest_addr,
                        'journey_date' => $u->getMeta('journey_date'),
                        'journey_time' => $u->getMeta('journey_time'),
                        'ride_status' => $u->getMeta('ride_status'),
                    );
                }
                if ($u->getMeta('ride_status') == "Completed") {
                    $c_rides[] = array(
                        'booking_id' => $u->id,
                        'book_date' => date('Y-m-d', strtotime($u->created_at)),
                        'book_time' => date('H:i:s', strtotime($u->created_at)),
                        'source_address' => $u->pickup_addr,
                        'source_time' => date('Y-m-d H:i:s', strtotime($u->getMeta('ridestart_timestamp'))),
                        'dest_address' => $u->dest_addr,
                        'dest_time' => date('Y-m-d H:i:s', strtotime($u->getMeta('rideend_timestamp'))),
                        'driving_time' => $u->getMeta('driving_time'),
                        'total_kms' => $u->getMeta('total_kms') . " " . $unit,
                        'amount' => $u->getMeta('tax_total'),
                        'journey_date' => $u->getMeta('journey_date'),
                        'journey_time' => $u->getMeta('journey_time'),
                        'ride_status' => $u->getMeta('ride_status'),
                    );
                }
                if ($u->getMeta('ride_status') == "Cancelled") {
                    $cancel[] = array(
                        'booking_id' => $u->id,
                        'book_date' => date('Y-m-d', strtotime($u->created_at)),
                        'book_time' => date('H:i:s', strtotime($u->created_at)),
                        'source_address' => $u->pickup_addr,
                        'dest_address' => $u->dest_addr,
                        'journey_date' => $u->getMeta('journey_date'),
                        'journey_time' => $u->getMeta('journey_time'),
                        'ride_status' => $u->getMeta('ride_status'),
                    );
                }
            }

            $data['success'] = 1;
            $data['message'] = "Data Received.";
            $data['data'] = array(
                'upcoming_rides' => $u_rides,
                'completed_rides' => $c_rides,
                'cancelled_rides' => $cancel,
            );
        }
        return $data;
    }

    public function single_ride_info(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));
        if ($booking == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Receive Ride Info. Please, Try again Later !";
            $data['data'] = "";
        } else {
            if (Hyvikk::get('dis_format') == 'meter') {
                $unit = 'm';
            }
            if (Hyvikk::get('dis_format') == 'km') {
                $unit = 'km';
            }
            $ride_reviews = array('user_id' => '', 'ratings' => '', 'review_text' => '', 'date' => '');
            $user_details = array('user_id' => $booking->customer_id, 'user_name' => $booking->customer->name, 'mobno' => $booking->customer->getMeta('mobno'), 'profile_pic' => $booking->customer->getMeta('profile_pic'));
            $ride_info = array();
            if ($booking->getMeta('ride_status') == "Upcoming") {
                $ride_info = array(
                    'booking_id' => $booking->id,
                    'source_address' => $booking->pickup_addr,
                    'dest_address' => $booking->dest_addr,
                    'book_timestamp' => date('Y-m-d H:i:s', strtotime($booking->created_at)),
                    'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                    'ride_status' => $booking->getMeta('ride_status'),
                    'journey_date' => $booking->getMeta('journey_date'),
                    'journey_time' => $booking->getMeta('journey_time'),
                );
            }

            if ($booking->getMeta('ride_status') == "Completed") {
                $ride_info = array(
                    'booking_id' => $booking->id,
                    'source_address' => $booking->pickup_addr,
                    'dest_address' => $booking->dest_addr,
                    'source_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                    'dest_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('rideend_timestamp'))),
                    'book_timestamp' => date('Y-m-d H:i:s', strtotime($booking->created_at)),
                    'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                    'driving_time' => $booking->getMeta('driving_time'),
                    'total_kms' => $booking->getMeta('total_kms') . " " . $unit,
                    'amount' => $booking->getMeta('tax_total'),
                    'ride_status' => $booking->getMeta('ride_status'),
                    'journey_date' => $booking->getMeta('journey_date'),
                    'journey_time' => $booking->getMeta('journey_time'),
                );

                $r1 = ReviewModel::where('booking_id', $request->get('booking_id'))->first();
                if ($r1 != null) {
                    $ride_reviews = array('user_id' => $r1->user_id, 'ratings' => $r1->ratings, 'review_text' => $r1->review_text, 'date' => date('d-m-Y', strtotime($r1->created_at)));
                }
            }

            if ($booking->getMeta('ride_status') == "Cancelled") {
                $ride_info = array(
                    'booking_id' => $booking->id,
                    'source_address' => $booking->pickup_addr,
                    'dest_address' => $booking->dest_addr,
                    'book_timestamp' => date('Y-m-d H:i:s', strtotime($booking->created_at)),
                    'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                    'reason' => $booking->getMeta('reason'),
                    'ride_status' => $booking->getMeta('ride_status'),
                    'journey_date' => $booking->getMeta('journey_date'),
                    'journey_time' => $booking->getMeta('journey_time'),
                );
            }

            $vehicle_type = VehicleTypeModel::find($booking->getMeta('vehicle_typeid'));

            $data['success'] = 1;
            $data['message'] = "Data Received.";
            $data['data'] = array(
                'rideinfo' => $ride_info, 'user_details' => $user_details, 'ride_review' => $ride_reviews,
                'fare_breakdown' => array('base_fare' => Hyvikk::fare(strtolower(str_replace(' ', '', ($booking->vehicle_id != null ? $booking->vehicle->types->vehicletype : ($vehicle_type->vehicletype ?? "")))) . '_base_fare'), 'ride_amount' => $booking->getMeta('tax_total'), 'extra_charges' => 0, 'payment_mode' => 'CASH')
            ); //done

        }

        return $data;
    }

    public function start_ride(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));
        if ($booking == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Start Ride. Please, Try again Later !";
            $data['data'] = "";
        } else {

            $booking->start_address = $request->get('start_address');
            $booking->start_lat = $request->get('start_lat');
            $booking->start_long = $request->get('start_long');
            $booking->pickup = date('Y-m-d H:i:s');
            $booking->ridestart_timestamp = date('Y-m-d H:i:s');
            $booking->save();
            $driver = User::find($booking->driver_id);
            $driver->is_on = 1;
            $driver->save();

            $this->ride_started_notification($booking->id);
            $this->ride_ongoing_notification($booking->id);
            $data['success'] = 1;
            $data['message'] = "Ride Started";
            $data['data'] = array('booking_id' => $booking->id, 'ridestart_timestamp' => $booking->getMeta('ridestart_timestamp'));
        }
        return $data;
    }

    public function ride_started_notification($id)
    {
        $booking = Bookings::find($id);
        $data['success'] = 1;
        $data['key'] = "ride_started_notification";
        $data['message'] = 'Data Received.';
        $data['title'] = "Ride Started";
        $data['description'] = $booking->pickup_addr . "-" . $booking->dest_addr . ": Driver Name " . $booking->driver->name;
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array('ride_info' => array(
            'user_id' => $booking->customer_id,
            'booking_id' => $id,
            'source_address' => $booking->pickup_addr,
            'dest_address' => $booking->dest_addr,
            'start_lat' => $booking->getMeta('start_lat'),
            'start_long' => $booking->getMeta('start_long'),
            'ridestart_timestamp' => $booking->getMeta('ridestart_timestamp'),
        ));

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
            'booking_id' => $id,
            'source_address' => $booking->pickup_addr,
            'dest_address' => $booking->dest_addr,
            'start_lat' => $booking->getMeta('start_lat'),
            'start_long' => $booking->getMeta('start_long'),
            'approx_timetoreach' => $booking->getMeta('approx_timetoreach'),
            'user_name' => $booking->customer->name,
            'user_profile' => $booking->customer->getMeta('profile_pic'),
            'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
        );

        // PushNotification::app('appNameAndroid')
        //     ->to($booking->customer->getMeta('fcm_id'))
        //     ->send($data);
        //not send to cutomer
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

    public function destination_reached(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));

        if ($booking == null) {
            $data['success'] = 0;
            $data['message'] = "Unable to Fetch Ride Info. Please, Try again Later !";
            $data['data'] = "";
        } else {

            $driver = User::find($booking->driver_id);
            $driver->is_on = 0;
            $driver->save();

            if (Hyvikk::get('dis_format') == 'meter') {
                $unit = 'm';
            }
            if (Hyvikk::get('dis_format') == 'km') {
                $unit = 'km';
            }
            $booking->end_address = $request->get('end_address');
            $booking->end_lat = $request->get('end_lat');
            $booking->end_long = $request->get('end_long');
            $booking->dropoff = date('Y-m-d H:i:s');
            $booking->rideend_timestamp = date('Y-m-d H:i:s');
            $booking->ride_status = "Completed";
            $booking->driving_time = $request->get('driving_time');
            $booking->total_kms = $request->get('total_kms');
            $vehicle_type = VehicleTypeModel::find($booking->getMeta('vehicle_typeid'));

            $km_base = Hyvikk::fare(strtolower(str_replace(' ', '', ($booking->vehicle_id != null ? $booking->vehicle->types->vehicletype : ($vehicle_type->vehicletype ?? "")))) . '_base_km');
            if ($request->get('total_kms') <= $km_base) {
                $total_fare = Hyvikk::fare(strtolower(str_replace(' ', '', ($booking->vehicle_id != null ? $booking->vehicle->types->vehicletype : ($vehicle_type->vehicletype ?? "")))) . '_base_fare');
            } else {
                $total_fare = Hyvikk::fare(strtolower(str_replace(' ', '', ($booking->vehicle_id != null ? $booking->vehicle->types->vehicletype : ($vehicle_type->vehicletype ?? "")))) . '_base_fare') + (($request->get('total_kms') - $km_base) * Hyvikk::fare(strtolower(str_replace(' ', '', ($booking->vehicle_id != null ? $booking->vehicle->types->vehicletype : ($vehicle_type->vehicletype ?? "")))) . '_std_fare'));
            }
            // calculate tax charges
            $count = 0;
            if (Hyvikk::get('tax_charge') != "null") {
                $taxes = json_decode(Hyvikk::get('tax_charge'), true);
                foreach ($taxes as $key => $val) {
                    $count = $count + $val;
                }
            }
            $booking->tax_total = (($total_fare * $count) / 100) + $total_fare;
            $booking->total_tax_percent = $count;
            $booking->total_tax_charge_rs = ($total_fare * $count) / 100;
            $booking->total = $total_fare;
            $booking->date = date('Y-m-d');
            $booking->waiting_time = 0;
            $booking->mileage = $request->get('total_kms');
            $booking->save();
            $ride_review = ReviewModel::where('booking_id', $booking->id)->first();

            if ($ride_review == null) {
                $reviews = array('user_id' => '', 'ratings' => '', 'review_text' => '', 'date' => '');
            } else {

                $reviews = array('user_id' => $ride_review->user_id, 'ratings' => $ride_review->ratings, 'review_text' => $ride_review->review_text, 'date' => date('Y-m-d', strtotime($ride_review->created_at)));
            }

            $rideinfo = array(
                'booking_id' => $booking->id,
                'source_address' => $booking->getMeta('start_address'),
                'dest_address' => $booking->dest_addr,
                'source_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                'dest_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('rideend_timestamp'))),
                'book_timestamp' => date('Y-m-d H:i:s', strtotime($booking->created_at)),
                'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                'driving_time' => $booking->getMeta('driving_time'),
                'total_kms' => $booking->getMeta('total_kms') . " " . $unit,
                'amount' => $booking->getMeta('tax_total'),
                'ride_status' => $booking->getMeta('ride_status'),
            );

            $user = User::find($booking->customer_id);

            $user_details = array('user_id' => $user->id, 'user_name' => $user->name, 'profile_pic' => $user->getMeta('profile_pic'));

            $this->dest_reach_or_ride_complete($booking->id);
            $data['success'] = 1;
            $data['message'] = "Ride Completed";
            $data['data'] = array('rideinfo' => $rideinfo, 'user_details' => $user_details, 'ride_review' => $reviews, 'fare_breakdown' => array('base_fare' => Hyvikk::fare(strtolower(str_replace(' ', '', ($booking->vehicle_id != null ? $booking->vehicle->types->vehicletype : ($vehicle_type->vehicletype ?? "")))) . '_base_fare'), 'ride_amount' => $booking->getMeta('tax_total'), 'extra_charges' => 0)); //done
        }
        return $data;
    }

    public function dest_reach_or_ride_complete($id)
    {
        $booking = Bookings::find($id);
        $rating = ReviewModel::where('booking_id', $id)->first();
        if ($rating != null) {
            $r = $rating->ratings;
        } else {
            $r = null;
        }
        if (Hyvikk::get('dis_format') == 'meter') {
            $unit = 'm';
        }
        if (Hyvikk::get('dis_format') == 'km') {
            $unit = 'km';
        }

        $vehicle_type = VehicleTypeModel::find($booking->getMeta('vehicle_typeid'));

        $data['success'] = 1;
        $data['key'] = "ride_completed_notification";
        $data['message'] = 'Data Received.';
        $data['title'] = "Ride Completed ";
        $data['description'] = "You have Reached your Destination, Thank you !";
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array(
            'rideinfo' => array(
                'booking_id' => $booking->id,
                'source_address' => $booking->pickup_addr,
                'dest_address' => $booking->dest_addr,
                'source_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                'dest_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('rideend_timestamp'))),
                'book_timestamp' => date('Y-m-d', strtotime($booking->created_at)),
                'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                'driving_time' => $booking->getMeta('driving_time'),
                'total_kms' => $booking->getMeta('total_kms') . " " . $unit,
                'amount' => $booking->getMeta('tax_total'),
                'ride_status' => $booking->getMeta('ride_status')
            ),
            'user_details' => array(
                'user_id' => $booking->customer_id,
                'user_name' => $booking->customer->name,
                'profile_pic' => $booking->customer->getMeta('profile_pic')
            ),
            'fare_breakdown' => array(
                'base_fare' => Hyvikk::fare(strtolower(str_replace(' ', '', ($booking->vehicle_id != null ? $booking->vehicle->types->vehicletype : ($vehicle_type->vehicletype ?? "")))) . '_base_fare'), //done
                'ride_amount' => $booking->getMeta('tax_total'),
                'extra_charges' => 0
            ),
            'driver_details' => array(
                'driver_id' => $booking->driver_id,
                'driver_name' => $booking->driver->name,
                'profile_pic' => $booking->driver->getMeta('driver_image'),
                'ratings' => $r
            )
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
    }

    public function confirm_payment(Request $request)
    {
        $booking = Bookings::find($request->get('booking_id'));
        $booking->status = 1;
        $booking->payment = 1;
        $booking->receipt = 1;
        $booking->payment_method = "cash";
        $booking->save();
        BookingPaymentsModel::create(['method' => 'cash', 'booking_id' => $booking->id, 'amount' => $booking->tax_total, 'payment_details' => null, 'transaction_id' => null, 'payment_status' => "succeeded"]);
        $tax_percent = 0;
        if (Hyvikk::get('tax_charge') != "null") {
            $taxes = json_decode(Hyvikk::get('tax_charge'), true);
            foreach ($taxes as $key => $val) {
                $tax_percent = $tax_percent + $val;
            }
        }

        $tax_charge_rs = ($booking->total * $tax_percent) / 100;

        if ($booking != null && $booking->status == 1) {

            $id = IncomeModel::create([
                "vehicle_id" => $booking->vehicle_id,
                "amount" => $booking->getMeta('tax_total'),
                "user_id" => $booking->customer_id,
                "date" => date('Y-m-d'),
                "mileage" => $booking->mileage,
                "income_cat" => 1,
                "income_id" => $booking->id,
                "tax_percent" => $tax_percent,
                "tax_charge_rs" => $tax_charge_rs,
            ])->id;
            $income = BookingIncome::create(['booking_id' => $request->get('booking_id'), 'income_id' => $id]);
            $this->payment_notification($booking->id);
            $data['success'] = 1;
            $data['message'] = "Payment Received.";
            $data['data'] = array('booking_id' => $request->get('booking_id'), 'payment_status' => $booking->status, 'payment_mode' => 'CASH');
        } else {
            $data['success'] = 0;
            $data['message'] = "Unable to Process your Request. Please, Try again Later !";
            $data['data'] = "";
        }

        return $data;
    }

    public function payment_notification($id)
    {

        $booking = Bookings::find($id);
        $data['success'] = 1;
        $data['key'] = "confirm_payment_notification";
        $data['message'] = 'Payment Received.';
        $data['title'] = "Payment Received CASH, id: " . $id;
        $data['description'] = $booking->pickup_addr . "-" . $booking->dest_addr;
        $data['timestamp'] = date('Y-m-d H:i:s');
        $review = ReviewModel::where('booking_id', $id)->first();
        if ($review != null) {
            $r = array('user_id' => $review->user_id, 'booking_id' => $review->booking_id, 'ratings' => $review->ratings, 'review_text' => $review->review_text, 'date' => date('Y-m-d', strtotime($review->created_at)));
        } else {
            $r = new \stdClass;
        }
        if (Hyvikk::get('dis_format') == 'meter') {
            $unit = 'm';
        }
        if (Hyvikk::get('dis_format') == 'km') {
            $unit = 'km';
        }
        $vehicle_type = VehicleTypeModel::find($booking->getMeta('vehicle_typeid'));

        $data['data'] = array(
            'rideinfo' => array(
                'user_id' => $booking->customer_id,
                'booking_id' => $id, 'source_address' => $booking->pickup_addr,
                'dest_address' => $booking->dest_addr,
                'source_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                'dest_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('rideend_timestamp'))),
                'book_timestamp' => date('d-m-Y', strtotime($booking->created_at)),
                'ridestart_timestamp' => date('Y-m-d H:i:s', strtotime($booking->getMeta('ridestart_timestamp'))),
                'driving_time' => $booking->getMeta('driving_time'),
                'total_kms' => $booking->getMeta('total_kms') . " " . $unit,
                'amount' => $booking->getMeta('tax_total'),
                'ride_status' => $booking->getMeta('ride_status'),
                'payment_status' => $booking->status,
                'payment_mode' => 'CASH',
            ),
            'driver_details' => array(
                'driver_id' => $booking->driver_id,
                'driver_name' => $booking->driver->name,
                'profile_pic' => $booking->driver->getMeta('driver_image'),
            ),
            'fare_breakdown' => array(
                'base_fare' => Hyvikk::fare(strtolower(str_replace(' ', '', ($booking->vehicle_id != null ? $booking->vehicle->types->vehicletype : ($vehicle_type->vehicletype ?? "")))) . '_base_fare'), //done
                'ride_amount' => $booking->getMeta('tax_total'),
                'extra_charges' => '0',
            ),
            'review' => $r,
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
    }

    public function active_drivers()
    {

        $drivers = User::meta()->where('users_meta.key', '=', 'is_available')->where('users_meta.value', '=', 1)->get();
        if ($drivers->toArray() == null) {

            $data['data'] = array('driver_details' => array());
        } else {
            foreach ($drivers as $d) {
                $id[] = $d->id;
            }

            $data['data'] = array('driver_details' => $id);
        }
        $data['success'] = 1;
        $data['message'] = "Data Received.";
        return $data;
    }

    public function get_settings()
    {
        $data['success'] = 1;
        $data['message'] = "Data Received.";
        $reasons = ReasonsModel::get();
        $vehicle_types = VehicleTypeModel::select('id', 'vehicletype', 'displayname', 'icon', 'seats')->where('isenable', 1)->get();

        $vehicle_type_data = array();
        $setings = FareSettings::all();
        foreach ($vehicle_types as $vehicle_type) {
            if ($vehicle_type->icon != null) {
                $url = $vehicle_type->icon;
            } else {
                $url = null;
            }
            $type = strtolower(str_replace(" ", "", $vehicle_type->vehicletype));
            $vehicle_type_data[] = array(
                'id' => $vehicle_type->id,
                'vehicletype' => $vehicle_type->vehicletype,
                'displayname' => $vehicle_type->displayname,
                'icon' => $url,
                'no_seats' => $vehicle_type->seats,
                'base_fare' => Hyvikk::fare($type . '_base_fare'), //done
                'base_km' => Hyvikk::fare($type . '_base_km'),
                'std_fare' => Hyvikk::fare($type . '_std_fare'),
                'base_waiting_time' => Hyvikk::fare($type . '_base_time'),
                'weekend_base_fare' => Hyvikk::fare($type . '_weekend_base_fare'),
                'weekend_base_km' => Hyvikk::fare($type . '_weekend_base_km'),
                'weekend_wait_time' => Hyvikk::fare($type . '_weekend_wait_time'),
                'weekend_std_fare' => Hyvikk::fare($type . '_weekend_std_fare'),
                'night_base_fare' => Hyvikk::fare($type . '_night_base_fare'),
                'night_base_km' => Hyvikk::fare($type . '_night_base_km'),
                'night_wait_time' => Hyvikk::fare($type . '_night_wait_time'),
                'night_std_fare' => Hyvikk::fare($type . '_night_std_fare'),
            );
        }
        $no_seats = VehicleTypeModel::pluck('seats')->toArray();
        $max_capacity = max($no_seats);
        $reason = array();
        foreach ($reasons as $r) {
            $reason[] = $r->reason;
        }
        array_unshift($reason, "What's The Reason ?");
        $data['data'] = array(
            'base_fare' => 500,
            'base_km' => 10,
            'std_fare' => 20,
            'base_waiting_time' => 2,
            'weekend_base_fare' => 500,
            'weekend_base_km' => 10,
            'weekend_wait_time' => 2,
            'weekend_std_fare' => 20,
            'night_base_fare' => 500,
            'night_base_km' => 10,
            'night_wait_time' => 2,
            'night_std_fare' => 20,
            'reasons' => $reason,
            'distance_format' => Hyvikk::get('dis_format'),
            'max_trip_time' => Hyvikk::api('max_trip'),
            'currency_symbol' => Hyvikk::get('currency'),
            'vehicle_types' => $vehicle_type_data,
            'max_capacity_vehicle' => $max_capacity,
        );
        return $data;
    }

    public function get_code()
    {
        return array(
            array(
                "name" => "Afghanistan",
                "dial_code" => "+93",
                "code" => "AF",
            ),
            array(
                "name" => "Aland Islands",
                "dial_code" => "+358",
                "code" => "AX",
            ),
            array(
                "name" => "Albania",
                "dial_code" => "+355",
                "code" => "AL",
            ),
            array(
                "name" => "Algeria",
                "dial_code" => "+213",
                "code" => "DZ",
            ),
            array(
                "name" => "AmericanSamoa",
                "dial_code" => "+1 684",
                "code" => "AS",
            ),
            array(
                "name" => "Andorra",
                "dial_code" => "+376",
                "code" => "AD",
            ),
            array(
                "name" => "Angola",
                "dial_code" => "+244",
                "code" => "AO",
            ),
            array(
                "name" => "Anguilla",
                "dial_code" => "+1 264",
                "code" => "AI",
            ),
            array(
                "name" => "Antarctica",
                "dial_code" => "+672",
                "code" => "AQ",
            ),
            array(
                "name" => "Antigua and Barbuda",
                "dial_code" => "+1268",
                "code" => "AG",
            ),
            array(
                "name" => "Argentina",
                "dial_code" => "+54",
                "code" => "AR",
            ),
            array(
                "name" => "Armenia",
                "dial_code" => "+374",
                "code" => "AM",
            ),
            array(
                "name" => "Aruba",
                "dial_code" => "+297",
                "code" => "AW",
            ),
            array(
                "name" => "Australia",
                "dial_code" => "+61",
                "code" => "AU",
            ),
            array(
                "name" => "Austria",
                "dial_code" => "+43",
                "code" => "AT",
            ),
            array(
                "name" => "Azerbaijan",
                "dial_code" => "+994",
                "code" => "AZ",
            ),
            array(
                "name" => "Bahamas",
                "dial_code" => "+1 242",
                "code" => "BS",
            ),
            array(
                "name" => "Bahrain",
                "dial_code" => "+973",
                "code" => "BH",
            ),
            array(
                "name" => "Bangladesh",
                "dial_code" => "+880",
                "code" => "BD",
            ),
            array(
                "name" => "Barbados",
                "dial_code" => "+1 246",
                "code" => "BB",
            ),
            array(
                "name" => "Belarus",
                "dial_code" => "+375",
                "code" => "BY",
            ),
            array(
                "name" => "Belgium",
                "dial_code" => "+32",
                "code" => "BE",
            ),
            array(
                "name" => "Belize",
                "dial_code" => "+501",
                "code" => "BZ",
            ),
            array(
                "name" => "Benin",
                "dial_code" => "+229",
                "code" => "BJ",
            ),
            array(
                "name" => "Bermuda",
                "dial_code" => "+1 441",
                "code" => "BM",
            ),
            array(
                "name" => "Bhutan",
                "dial_code" => "+975",
                "code" => "BT",
            ),
            array(
                "name" => "Bolivia, Plurinational State of",
                "dial_code" => "+591",
                "code" => "BO",
            ),
            array(
                "name" => "Bosnia and Herzegovina",
                "dial_code" => "+387",
                "code" => "BA",
            ),
            array(
                "name" => "Botswana",
                "dial_code" => "+267",
                "code" => "BW",
            ),
            array(
                "name" => "Brazil",
                "dial_code" => "+55",
                "code" => "BR",
            ),
            array(
                "name" => "British Indian Ocean Territory",
                "dial_code" => "+246",
                "code" => "IO",
            ),
            array(
                "name" => "Brunei Darussalam",
                "dial_code" => "+673",
                "code" => "BN",
            ),
            array(
                "name" => "Bulgaria",
                "dial_code" => "+359",
                "code" => "BG",
            ),
            array(
                "name" => "Burkina Faso",
                "dial_code" => "+226",
                "code" => "BF",
            ),
            array(
                "name" => "Burundi",
                "dial_code" => "+257",
                "code" => "BI",
            ),
            array(
                "name" => "Cambodia",
                "dial_code" => "+855",
                "code" => "KH",
            ),
            array(
                "name" => "Cameroon",
                "dial_code" => "+237",
                "code" => "CM",
            ),
            array(
                "name" => "Canada",
                "dial_code" => "+1",
                "code" => "CA",
            ),
            array(
                "name" => "Cape Verde",
                "dial_code" => "+238",
                "code" => "CV",
            ),
            array(
                "name" => "Cayman Islands",
                "dial_code" => "+ 345",
                "code" => "KY",
            ),
            array(
                "name" => "Central African Republic",
                "dial_code" => "+236",
                "code" => "CF",
            ),
            array(
                "name" => "Chad",
                "dial_code" => "+235",
                "code" => "TD",
            ),
            array(
                "name" => "Chile",
                "dial_code" => "+56",
                "code" => "CL",
            ),
            array(
                "name" => "China",
                "dial_code" => "+86",
                "code" => "CN",
            ),
            array(
                "name" => "Christmas Island",
                "dial_code" => "+61",
                "code" => "CX",
            ),
            array(
                "name" => "Cocos (Keeling) Islands",
                "dial_code" => "+61",
                "code" => "CC",
            ),
            array(
                "name" => "Colombia",
                "dial_code" => "+57",
                "code" => "CO",
            ),
            array(
                "name" => "Comoros",
                "dial_code" => "+269",
                "code" => "KM",
            ),
            array(
                "name" => "Congo",
                "dial_code" => "+242",
                "code" => "CG",
            ),
            array(
                "name" => "Congo, The Democratic Republic of the Congo",
                "dial_code" => "+243",
                "code" => "CD",
            ),
            array(
                "name" => "Cook Islands",
                "dial_code" => "+682",
                "code" => "CK",
            ),
            array(
                "name" => "Costa Rica",
                "dial_code" => "+506",
                "code" => "CR",
            ),
            array(
                "name" => "Cote d'Ivoire",
                "dial_code" => "+225",
                "code" => "CI",
            ),
            array(
                "name" => "Croatia",
                "dial_code" => "+385",
                "code" => "HR",
            ),
            array(
                "name" => "Cuba",
                "dial_code" => "+53",
                "code" => "CU",
            ),
            array(
                "name" => "Cyprus",
                "dial_code" => "+357",
                "code" => "CY",
            ),
            array(
                "name" => "Czech Republic",
                "dial_code" => "+420",
                "code" => "CZ",
            ),
            array(
                "name" => "Denmark",
                "dial_code" => "+45",
                "code" => "DK",
            ),
            array(
                "name" => "Djibouti",
                "dial_code" => "+253",
                "code" => "DJ",
            ),
            array(
                "name" => "Dominica",
                "dial_code" => "+1 767",
                "code" => "DM",
            ),
            array(
                "name" => "Dominican Republic",
                "dial_code" => "+1 849",
                "code" => "DO",
            ),
            array(
                "name" => "Ecuador",
                "dial_code" => "+593",
                "code" => "EC",
            ),
            array(
                "name" => "Egypt",
                "dial_code" => "+20",
                "code" => "EG",
            ),
            array(
                "name" => "El Salvador",
                "dial_code" => "+503",
                "code" => "SV",
            ),
            array(
                "name" => "Equatorial Guinea",
                "dial_code" => "+240",
                "code" => "GQ",
            ),
            array(
                "name" => "Eritrea",
                "dial_code" => "+291",
                "code" => "ER",
            ),
            array(
                "name" => "Estonia",
                "dial_code" => "+372",
                "code" => "EE",
            ),
            array(
                "name" => "Ethiopia",
                "dial_code" => "+251",
                "code" => "ET",
            ),
            array(
                "name" => "Falkland Islands (Malvinas)",
                "dial_code" => "+500",
                "code" => "FK",
            ),
            array(
                "name" => "Faroe Islands",
                "dial_code" => "+298",
                "code" => "FO",
            ),
            array(
                "name" => "Fiji",
                "dial_code" => "+679",
                "code" => "FJ",
            ),
            array(
                "name" => "Finland",
                "dial_code" => "+358",
                "code" => "FI",
            ),
            array(
                "name" => "France",
                "dial_code" => "+33",
                "code" => "FR",
            ),
            array(
                "name" => "French Guiana",
                "dial_code" => "+594",
                "code" => "GF",
            ),
            array(
                "name" => "French Polynesia",
                "dial_code" => "+689",
                "code" => "PF",
            ),
            array(
                "name" => "Gabon",
                "dial_code" => "+241",
                "code" => "GA",
            ),
            array(
                "name" => "Gambia",
                "dial_code" => "+220",
                "code" => "GM",
            ),
            array(
                "name" => "Georgia",
                "dial_code" => "+995",
                "code" => "GE",
            ),
            array(
                "name" => "Germany",
                "dial_code" => "+49",
                "code" => "DE",
            ),
            array(
                "name" => "Ghana",
                "dial_code" => "+233",
                "code" => "GH",
            ),
            array(
                "name" => "Gibraltar",
                "dial_code" => "+350",
                "code" => "GI",
            ),
            array(
                "name" => "Greece",
                "dial_code" => "+30",
                "code" => "GR",
            ),
            array(
                "name" => "Greenland",
                "dial_code" => "+299",
                "code" => "GL",
            ),
            array(
                "name" => "Grenada",
                "dial_code" => "+1 473",
                "code" => "GD",
            ),
            array(
                "name" => "Guadeloupe",
                "dial_code" => "+590",
                "code" => "GP",
            ),
            array(
                "name" => "Guam",
                "dial_code" => "+1 671",
                "code" => "GU",
            ),
            array(
                "name" => "Guatemala",
                "dial_code" => "+502",
                "code" => "GT",
            ),
            array(
                "name" => "Guernsey",
                "dial_code" => "+44",
                "code" => "GG",
            ),
            array(
                "name" => "Guinea",
                "dial_code" => "+224",
                "code" => "GN",
            ),
            array(
                "name" => "Guinea-Bissau",
                "dial_code" => "+245",
                "code" => "GW",
            ),
            array(
                "name" => "Guyana",
                "dial_code" => "+595",
                "code" => "GY",
            ),
            array(
                "name" => "Haiti",
                "dial_code" => "+509",
                "code" => "HT",
            ),
            array(
                "name" => "Holy See (Vatican City State)",
                "dial_code" => "+379",
                "code" => "VA",
            ),
            array(
                "name" => "Honduras",
                "dial_code" => "+504",
                "code" => "HN",
            ),
            array(
                "name" => "Hong Kong",
                "dial_code" => "+852",
                "code" => "HK",
            ),
            array(
                "name" => "Hungary",
                "dial_code" => "+36",
                "code" => "HU",
            ),
            array(
                "name" => "Iceland",
                "dial_code" => "+354",
                "code" => "IS",
            ),
            array(
                "name" => "India",
                "dial_code" => "+91",
                "code" => "IN",
            ),
            array(
                "name" => "Indonesia",
                "dial_code" => "+62",
                "code" => "ID",
            ),
            array(
                "name" => "Iran, Islamic Republic of Persian Gulf",
                "dial_code" => "+98",
                "code" => "IR",
            ),
            array(
                "name" => "Iraq",
                "dial_code" => "+964",
                "code" => "IQ",
            ),
            array(
                "name" => "Ireland",
                "dial_code" => "+353",
                "code" => "IE",
            ),
            array(
                "name" => "Isle of Man",
                "dial_code" => "+44",
                "code" => "IM",
            ),
            array(
                "name" => "Israel",
                "dial_code" => "+972",
                "code" => "IL",
            ),
            array(
                "name" => "Italy",
                "dial_code" => "+39",
                "code" => "IT",
            ),
            array(
                "name" => "Jamaica",
                "dial_code" => "+1 876",
                "code" => "JM",
            ),
            array(
                "name" => "Japan",
                "dial_code" => "+81",
                "code" => "JP",
            ),
            array(
                "name" => "Jersey",
                "dial_code" => "+44",
                "code" => "JE",
            ),
            array(
                "name" => "Jordan",
                "dial_code" => "+962",
                "code" => "JO",
            ),
            array(
                "name" => "Kazakhstan",
                "dial_code" => "+7 7",
                "code" => "KZ",
            ),
            array(
                "name" => "Kenya",
                "dial_code" => "+254",
                "code" => "KE",
            ),
            array(
                "name" => "Kiribati",
                "dial_code" => "+686",
                "code" => "KI",
            ),
            array(
                "name" => "Korea, Democratic People's Republic of Korea",
                "dial_code" => "+850",
                "code" => "KP",
            ),
            array(
                "name" => "Korea, Republic of South Korea",
                "dial_code" => "+82",
                "code" => "KR",
            ),
            array(
                "name" => "Kuwait",
                "dial_code" => "+965",
                "code" => "KW",
            ),
            array(
                "name" => "Kyrgyzstan",
                "dial_code" => "+996",
                "code" => "KG",
            ),
            array(
                "name" => "Laos",
                "dial_code" => "+856",
                "code" => "LA",
            ),
            array(
                "name" => "Latvia",
                "dial_code" => "+371",
                "code" => "LV",
            ),
            array(
                "name" => "Lebanon",
                "dial_code" => "+961",
                "code" => "LB",
            ),
            array(
                "name" => "Lesotho",
                "dial_code" => "+266",
                "code" => "LS",
            ),
            array(
                "name" => "Liberia",
                "dial_code" => "+231",
                "code" => "LR",
            ),
            array(
                "name" => "Libyan Arab Jamahiriya",
                "dial_code" => "+218",
                "code" => "LY",
            ),
            array(
                "name" => "Liechtenstein",
                "dial_code" => "+423",
                "code" => "LI",
            ),
            array(
                "name" => "Lithuania",
                "dial_code" => "+370",
                "code" => "LT",
            ),
            array(
                "name" => "Luxembourg",
                "dial_code" => "+352",
                "code" => "LU",
            ),
            array(
                "name" => "Macao",
                "dial_code" => "+853",
                "code" => "MO",
            ),
            array(
                "name" => "Macedonia",
                "dial_code" => "+389",
                "code" => "MK",
            ),
            array(
                "name" => "Madagascar",
                "dial_code" => "+261",
                "code" => "MG",
            ),
            array(
                "name" => "Malawi",
                "dial_code" => "+265",
                "code" => "MW",
            ),
            array(
                "name" => "Malaysia",
                "dial_code" => "+60",
                "code" => "MY",
            ),
            array(
                "name" => "Maldives",
                "dial_code" => "+960",
                "code" => "MV",
            ),
            array(
                "name" => "Mali",
                "dial_code" => "+223",
                "code" => "ML",
            ),
            array(
                "name" => "Malta",
                "dial_code" => "+356",
                "code" => "MT",
            ),
            array(
                "name" => "Marshall Islands",
                "dial_code" => "+692",
                "code" => "MH",
            ),
            array(
                "name" => "Martinique",
                "dial_code" => "+596",
                "code" => "MQ",
            ),
            array(
                "name" => "Mauritania",
                "dial_code" => "+222",
                "code" => "MR",
            ),
            array(
                "name" => "Mauritius",
                "dial_code" => "+230",
                "code" => "MU",
            ),
            array(
                "name" => "Mayotte",
                "dial_code" => "+262",
                "code" => "YT",
            ),
            array(
                "name" => "Mexico",
                "dial_code" => "+52",
                "code" => "MX",
            ),
            array(
                "name" => "Micronesia, Federated States of Micronesia",
                "dial_code" => "+691",
                "code" => "FM",
            ),
            array(
                "name" => "Moldova",
                "dial_code" => "+373",
                "code" => "MD",
            ),
            array(
                "name" => "Monaco",
                "dial_code" => "+377",
                "code" => "MC",
            ),
            array(
                "name" => "Mongolia",
                "dial_code" => "+976",
                "code" => "MN",
            ),
            array(
                "name" => "Montenegro",
                "dial_code" => "+382",
                "code" => "ME",
            ),
            array(
                "name" => "Montserrat",
                "dial_code" => "+1664",
                "code" => "MS",
            ),
            array(
                "name" => "Morocco",
                "dial_code" => "+212",
                "code" => "MA",
            ),
            array(
                "name" => "Mozambique",
                "dial_code" => "+258",
                "code" => "MZ",
            ),
            array(
                "name" => "Myanmar",
                "dial_code" => "+95",
                "code" => "MM",
            ),
            array(
                "name" => "Namibia",
                "dial_code" => "+264",
                "code" => "NA",
            ),
            array(
                "name" => "Nauru",
                "dial_code" => "+674",
                "code" => "NR",
            ),
            array(
                "name" => "Nepal",
                "dial_code" => "+977",
                "code" => "NP",
            ),
            array(
                "name" => "Netherlands",
                "dial_code" => "+31",
                "code" => "NL",
            ),
            array(
                "name" => "Netherlands Antilles",
                "dial_code" => "+599",
                "code" => "AN",
            ),
            array(
                "name" => "New Caledonia",
                "dial_code" => "+687",
                "code" => "NC",
            ),
            array(
                "name" => "New Zealand",
                "dial_code" => "+64",
                "code" => "NZ",
            ),
            array(
                "name" => "Nicaragua",
                "dial_code" => "+505",
                "code" => "NI",
            ),
            array(
                "name" => "Niger",
                "dial_code" => "+227",
                "code" => "NE",
            ),
            array(
                "name" => "Nigeria",
                "dial_code" => "+234",
                "code" => "NG",
            ),
            array(
                "name" => "Niue",
                "dial_code" => "+683",
                "code" => "NU",
            ),
            array(
                "name" => "Norfolk Island",
                "dial_code" => "+672",
                "code" => "NF",
            ),
            array(
                "name" => "Northern Mariana Islands",
                "dial_code" => "+1 670",
                "code" => "MP",
            ),
            array(
                "name" => "Norway",
                "dial_code" => "+47",
                "code" => "NO",
            ),
            array(
                "name" => "Oman",
                "dial_code" => "+968",
                "code" => "OM",
            ),
            array(
                "name" => "Pakistan",
                "dial_code" => "+92",
                "code" => "PK",
            ),
            array(
                "name" => "Palau",
                "dial_code" => "+680",
                "code" => "PW",
            ),
            array(
                "name" => "Palestinian Territory, Occupied",
                "dial_code" => "+970",
                "code" => "PS",
            ),
            array(
                "name" => "Panama",
                "dial_code" => "+507",
                "code" => "PA",
            ),
            array(
                "name" => "Papua New Guinea",
                "dial_code" => "+675",
                "code" => "PG",
            ),
            array(
                "name" => "Paraguay",
                "dial_code" => "+595",
                "code" => "PY",
            ),
            array(
                "name" => "Peru",
                "dial_code" => "+51",
                "code" => "PE",
            ),
            array(
                "name" => "Philippines",
                "dial_code" => "+63",
                "code" => "PH",
            ),
            array(
                "name" => "Pitcairn",
                "dial_code" => "+872",
                "code" => "PN",
            ),
            array(
                "name" => "Poland",
                "dial_code" => "+48",
                "code" => "PL",
            ),
            array(
                "name" => "Portugal",
                "dial_code" => "+351",
                "code" => "PT",
            ),
            array(
                "name" => "Puerto Rico",
                "dial_code" => "+1 939",
                "code" => "PR",
            ),
            array(
                "name" => "Qatar",
                "dial_code" => "+974",
                "code" => "QA",
            ),
            array(
                "name" => "Romania",
                "dial_code" => "+40",
                "code" => "RO",
            ),
            array(
                "name" => "Russia",
                "dial_code" => "+7",
                "code" => "RU",
            ),
            array(
                "name" => "Rwanda",
                "dial_code" => "+250",
                "code" => "RW",
            ),
            array(
                "name" => "Reunion",
                "dial_code" => "+262",
                "code" => "RE",
            ),
            array(
                "name" => "Saint Barthelemy",
                "dial_code" => "+590",
                "code" => "BL",
            ),
            array(
                "name" => "Saint Helena, Ascension and Tristan Da Cunha",
                "dial_code" => "+290",
                "code" => "SH",
            ),
            array(
                "name" => "Saint Kitts and Nevis",
                "dial_code" => "+1 869",
                "code" => "KN",
            ),
            array(
                "name" => "Saint Lucia",
                "dial_code" => "+1 758",
                "code" => "LC",
            ),
            array(
                "name" => "Saint Martin",
                "dial_code" => "+590",
                "code" => "MF",
            ),
            array(
                "name" => "Saint Pierre and Miquelon",
                "dial_code" => "+508",
                "code" => "PM",
            ),
            array(
                "name" => "Saint Vincent and the Grenadines",
                "dial_code" => "+1 784",
                "code" => "VC",
            ),
            array(
                "name" => "Samoa",
                "dial_code" => "+685",
                "code" => "WS",
            ),
            array(
                "name" => "San Marino",
                "dial_code" => "+378",
                "code" => "SM",
            ),
            array(
                "name" => "Sao Tome and Principe",
                "dial_code" => "+239",
                "code" => "ST",
            ),
            array(
                "name" => "Saudi Arabia",
                "dial_code" => "+966",
                "code" => "SA",
            ),
            array(
                "name" => "Senegal",
                "dial_code" => "+221",
                "code" => "SN",
            ),
            array(
                "name" => "Serbia",
                "dial_code" => "+381",
                "code" => "RS",
            ),
            array(
                "name" => "Seychelles",
                "dial_code" => "+248",
                "code" => "SC",
            ),
            array(
                "name" => "Sierra Leone",
                "dial_code" => "+232",
                "code" => "SL",
            ),
            array(
                "name" => "Singapore",
                "dial_code" => "+65",
                "code" => "SG",
            ),
            array(
                "name" => "Slovakia",
                "dial_code" => "+421",
                "code" => "SK",
            ),
            array(
                "name" => "Slovenia",
                "dial_code" => "+386",
                "code" => "SI",
            ),
            array(
                "name" => "Solomon Islands",
                "dial_code" => "+677",
                "code" => "SB",
            ),
            array(
                "name" => "Somalia",
                "dial_code" => "+252",
                "code" => "SO",
            ),
            array(
                "name" => "South Africa",
                "dial_code" => "+27",
                "code" => "ZA",
            ),
            array(
                "name" => "South Georgia and the South Sandwich Islands",
                "dial_code" => "+500",
                "code" => "GS",
            ),
            array(
                "name" => "Spain",
                "dial_code" => "+34",
                "code" => "ES",
            ),
            array(
                "name" => "Sri Lanka",
                "dial_code" => "+94",
                "code" => "LK",
            ),
            array(
                "name" => "Sudan",
                "dial_code" => "+249",
                "code" => "SD",
            ),
            array(
                "name" => "Suriname",
                "dial_code" => "+597",
                "code" => "SR",
            ),
            array(
                "name" => "Svalbard and Jan Mayen",
                "dial_code" => "+47",
                "code" => "SJ",
            ),
            array(
                "name" => "Swaziland",
                "dial_code" => "+268",
                "code" => "SZ",
            ),
            array(
                "name" => "Sweden",
                "dial_code" => "+46",
                "code" => "SE",
            ),
            array(
                "name" => "Switzerland",
                "dial_code" => "+41",
                "code" => "CH",
            ),
            array(
                "name" => "Syrian Arab Republic",
                "dial_code" => "+963",
                "code" => "SY",
            ),
            array(
                "name" => "Taiwan",
                "dial_code" => "+886",
                "code" => "TW",
            ),
            array(
                "name" => "Tajikistan",
                "dial_code" => "+992",
                "code" => "TJ",
            ),
            array(
                "name" => "Tanzania, United Republic of Tanzania",
                "dial_code" => "+255",
                "code" => "TZ",
            ),
            array(
                "name" => "Thailand",
                "dial_code" => "+66",
                "code" => "TH",
            ),
            array(
                "name" => "Timor-Leste",
                "dial_code" => "+670",
                "code" => "TL",
            ),
            array(
                "name" => "Togo",
                "dial_code" => "+228",
                "code" => "TG",
            ),
            array(
                "name" => "Tokelau",
                "dial_code" => "+690",
                "code" => "TK",
            ),
            array(
                "name" => "Tonga",
                "dial_code" => "+676",
                "code" => "TO",
            ),
            array(
                "name" => "Trinidad and Tobago",
                "dial_code" => "+1 868",
                "code" => "TT",
            ),
            array(
                "name" => "Tunisia",
                "dial_code" => "+216",
                "code" => "TN",
            ),
            array(
                "name" => "Turkey",
                "dial_code" => "+90",
                "code" => "TR",
            ),
            array(
                "name" => "Turkmenistan",
                "dial_code" => "+993",
                "code" => "TM",
            ),
            array(
                "name" => "Turks and Caicos Islands",
                "dial_code" => "+1 649",
                "code" => "TC",
            ),
            array(
                "name" => "Tuvalu",
                "dial_code" => "+688",
                "code" => "TV",
            ),
            array(
                "name" => "Uganda",
                "dial_code" => "+256",
                "code" => "UG",
            ),
            array(
                "name" => "Ukraine",
                "dial_code" => "+380",
                "code" => "UA",
            ),
            array(
                "name" => "United Arab Emirates",
                "dial_code" => "+971",
                "code" => "AE",
            ),
            array(
                "name" => "United Kingdom",
                "dial_code" => "+44",
                "code" => "GB",
            ),
            array(
                "name" => "United States",
                "dial_code" => "+1",
                "code" => "US",
            ),
            array(
                "name" => "Uruguay",
                "dial_code" => "+598",
                "code" => "UY",
            ),
            array(
                "name" => "Uzbekistan",
                "dial_code" => "+998",
                "code" => "UZ",
            ),
            array(
                "name" => "Vanuatu",
                "dial_code" => "+678",
                "code" => "VU",
            ),
            array(
                "name" => "Venezuela, Bolivarian Republic of Venezuela",
                "dial_code" => "+58",
                "code" => "VE",
            ),
            array(
                "name" => "Vietnam",
                "dial_code" => "+84",
                "code" => "VN",
            ),
            array(
                "name" => "Virgin Islands, British",
                "dial_code" => "+1 284",
                "code" => "VG",
            ),
            array(
                "name" => "Virgin Islands, U.S.",
                "dial_code" => "+1 340",
                "code" => "VI",
            ),
            array(
                "name" => "Wallis and Futuna",
                "dial_code" => "+681",
                "code" => "WF",
            ),
            array(
                "name" => "Yemen",
                "dial_code" => "+967",
                "code" => "YE",
            ),
            array(
                "name" => "Zambia",
                "dial_code" => "+260",
                "code" => "ZM",
            ),
            array(
                "name" => "Zimbabwe",
                "dial_code" => "+263",
                "code" => "ZW",
            ),
            array(
                "name" => "Dominican Republic",
                "dial_code" => "+1 809",
                "code" => "DO",
            ),
            array(
                "name" => "Dominican Republic",
                "dial_code" => "+1 829",
                "code" => "DO",
            ),
        );
    }
}
