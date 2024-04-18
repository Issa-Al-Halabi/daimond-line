<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\OTP;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Mail\Mailable;
use App\Services\OTPService;
use App\Traits\FirebaseAuthTrait;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Validator;

//traits

class OTPController extends Controller
{
    public function signUp(Request $request)
    {
if($request->request_from=="sign_up"){
        $validation = Validator::make(
            $request->all(),
            [
              
                    'phone' => [
        Rule::unique('users')->where(function ($query) {
            $query->whereNull('deleted_at');
        }),
        'required',
        'numeric',
    ],
            ]

        );
          $errors = $validation->errors();
        if (count($errors) > 0) {
            return response()->json([

                "error" => true,
               
                "message" => 'Invalid phone',
            ]);
        }

        $code = rand(111111, 999999);
        $otp = OTP::create(
            ["phone" => $request->phone, "code" => $code]
        )->makeHidden(['updated_at', 'created_at', 'id']);
        if ($otp) {
            return response()->json([
                "error" => false,
                "data" => $otp,
                "message" => "success"
            ]);
        } else {
            return response()->json([
                "error" => true,
              
                "message" => "error in otp creating"
            ]);
        }
        
}
elseif($request->request_from=="forget_password")
{
           $validation = Validator::make(
            $request->all(),
            [
                //'phone' => 'unique:users,phone|required',
                    'phone' => 'required|exists:users,phone',
                    'user_type'=>'required',
       ]

        ); 
        
          $user = User::where('phone', $request->phone)->first();
  $user_type=$request->user_type;
         $errors = $validation->errors();
        if (count($errors) > 0) {
            return response()->json([

                "error" => true,
               
                "message" => 'Invalid phone',
            ]);
        }
   elseif ($user_type == 'driver' && ($user->user_type == 'driver' || $user->user_type == 'external_driver')) {
   

        $code = rand(111111, 999999);
        $otp = OTP::create(
            ["phone" => $request->phone, "code" => $code]
        )->makeHidden(['updated_at', 'created_at', 'id']);
        if ($otp) {
            return response()->json([
                "error" => false,
                "data" => $otp,
                "message" => "success"
            ]);
        } 
        else {
            return response()->json([
                "error" => true,
              
                "message" => "error in otp creating"
            ]);
        }
   }
   
   elseif ($user_type == 'user' && ($user->user_type == 'user' || $user->user_type == 'Organizations')) {

        $code = rand(111111, 999999);
        $otp = OTP::create(
            ["phone" => $request->phone, "code" => $code]
        )->makeHidden(['updated_at', 'created_at', 'id']);
        if ($otp) {
            return response()->json([
                "error" => false,
                "data" => $otp,
                "message" => "success"
            ]);
        } 
        else {
            return response()->json([
                "error" => true,
              
                "message" => "error in otp creating"
            ]);
        }
   }
   else{
       return response()->json([
                "error" => true,
                "message" => "Invalid phone"
            ]);
   }
}
       
    }
    public function signUp1(Request $request)
    {

        $validation = Validator::make(
            $request->all(),
            [
                'email' => 'unique:users|required|email',
            ]

        );
        $errors = $validation->errors();
        if (count($errors) > 0) {
            return response()->json([

                "error" => true,
                // "data" => [],
                "message" => 'Invalid email',
            ]);
        }

        $code = rand(111111, 999999);
        $otp = OTP::create(
            ["email" => $request->email, "code" => $code]

        )->makeHidden(['updated_at', 'created_at', 'id']);

        if ($otp) {
            $msg = 'welcome to our Application.Your otp number is' . '' . $otp->code;

            Mail::to($request->email)->send(new  MyTestMail($msg));


            return response()->json([
                "error" => false,
                "data" => $otp,
                "message" => "success",

            ]);
        } else {
            $msg = 'error in otp creating';

            Mail::to($request->email)->send(new  MyTestMail($msg));

            return response()->json([
                "error" => true,
                // "data" => [],
                "message" => "error in otp creating"
            ]);
        }
    }

    public function verifyOTP1(Request $request)
    {
        $otp = Otp::where(
            [
                "email" => $request->email,
                "code" => $request->code,
            ]
        )->first();


        //invlaid
        if (empty($otp)) {
            //
            return response()->json([
                "message" => __('Invalid OTP'),
                'error' => true
            ], 400);
        }

        //
        $otp->delete();
        if (empty($request->is_login)) {
            return response()->json([
                "message" => __('OTP verification successful'),
                'erorr' => false
            ], 200);
        }
    }
    public function verifyOTP(Request $request)
    {
        $otp = Otp::where(
            [
                "phone" => $request->phone,
                "code" => $request->code,
            ]
        )->first();


        //invlaid
        if (empty($otp)) {
            //
            return response()->json([
                "message" => __('Invalid OTP'),
                'error' => true
            ], 400);
        }

        //
       // $otp->delete();
        if (empty($request->is_login)) {
            return response()->json([
                "message" => __('OTP verification successful'),
                'erorr' => false
            ], 200);
        }
    }
}
