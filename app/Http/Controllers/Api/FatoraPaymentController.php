<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentResponseStatus;
use App\Enums\PaymentStatus;
use App\Model\FatoraPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\FatoraPaymentRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FatoraPaymentController extends Controller
{

    function createPayment(FatoraPaymentRequest $request){
       
        // convert to array
        $data = json_decode(json_encode($request->all()),true);

        $response = $this->makePaymentRequest(url:'/create-payment', data : $data, method : "POST");
        
        if($response->ErrorCode == PaymentResponseStatus::success){
           FatoraPayment::create([
                "payment_id" =>$response->Data->paymentId ,
                "transaction_number" => null,
                "amount"=> $request->amount,
                "terminal_id"=> env("FATORA_TERMINAL_ID"),
                "notes"=> null,
                "status"=> PaymentStatus::pending,
                "creation_timestamp"=> Carbon::now(),
            ]);

            $res['error'] = false;
            $res['message'] = "The Payment has been created Successfully";
            $res['data'] =  $response->Data;
            return $res;
        }
        else{
            $res['error'] = true;
            $res['message'] = "The Payment has not created";
            return $res;
        }

    }

    function getPaymentStatus($paymentId){
        
        $response = $this->makePaymentRequest(url:'/get-payment-status'."/$paymentId",);
        
        return $response;
    }

    function cancelPayment(FatoraPaymentRequest $request){
        
        // convert to array
        $data = json_decode(json_encode($request->all()),true);
        
        $response = $this->makePaymentRequest(url:'/cancel-payment', data : $data, method : "POST");

        if($response->ErrorCode == PaymentResponseStatus::success){

            $fatoraPayment = FatoraPayment::where("payment_id" ,$request->payment_id)->first();
            if(! $fatoraPayment){
                $res['error'] = true;
                $res['message'] = "Payment Order Not Found";
                return $res;
            }

            $fatoraPayment->status = PaymentStatus::canceled;
            $fatoraPayment->save();
 
             $res['error'] = false;
             $res['message'] = "The Payment has been canceled Successfully";
             $res['data'] =  $response->Data;
             return $res;
         }
         else{
             $res['error'] = true;
             $res['message'] = $response->ErrorMessage;
             return $res;
         }
    }
    
    function makePaymentRequest($url , $data = null,$method = "GET"){
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env("PAYMENT_URL").$url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Basic ".base64_encode(env("FATORA_USERNAME").":".env("FATORA_PASSWORD")),
        ), 
        
        CURLOPT_POSTFIELDS => json_encode($data),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        $response = json_decode($response);
        return $response;
    }

}
