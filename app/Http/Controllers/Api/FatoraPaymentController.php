<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentResponseStatus;
use App\Enums\PaymentStatus;
use App\Model\FatoraPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\FatoraPaymentRequest;
use App\Http\Traits\SendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FatoraPaymentController extends Controller
{
    use SendNotification;

    //   9110 0161 3010 7687
    //9760 1505 1076 4750
    //662e081b655a424_04_28_08_26_03  // failed merchent
    // 662e09ebc61ba24_04_28_08_33_47 // success merchent

    // for cancel
    public const paymentCallbackURL = "payment-callbackURL";
    // when open the url and the payment are canceled
    public const paymentTriggerURL = "payment-triggerURL";

    function createPayment(FatoraPaymentRequest $request)
    {

        // $domainUrl = request()->getSchemeAndHttpHost();
        $domainUrl = "https://diamond-line.com.sy";

        $now = Carbon::now()->format("y_m_d_h_i_s");
        $merchant = uniqid() . $now;

        $data = json_decode(json_encode($request->all()), true);
        $data = [
            "amount" => $request->amount,
            "terminalId" => env("FATORA_TERMINAL_ID"),
            "lang" => "en",
            "callbackURL" => $domainUrl . "/api/" . FatoraPaymentController::paymentCallbackURL . "/" . $merchant,
            "triggerURL" => $domainUrl . "/api/" . FatoraPaymentController::paymentTriggerURL . "/" . $merchant,
        ];

        $response = $this->makePaymentRequest('/create-payment', $data, "POST");

        if ($response->ErrorCode == PaymentResponseStatus::success) {
            FatoraPayment::create([
                "bookings_id" => $request->booking_id,
                "merchant" => $merchant,
                "payment_id" => $response->Data->paymentId,
                "transaction_number" => null,
                "amount" => $request->amount,
                "terminal_id" => env("FATORA_TERMINAL_ID"),
                "notes" => null,
                "status" => PaymentStatus::pending,
                "creation_timestamp" => Carbon::now(),
            ]);

            $res['error'] = false;
            $res['message'] = "The Payment has been created Successfully";
            $res['data'] =  $response->Data;
            return $res;
        } else {
            $res['error'] = true;
            $res['message'] = "The Payment has not created";
            return $res;
        }
    }

    function getPaymentStatus($paymentId)
    {

        $fatoraPayment = FatoraPayment::where("payment_id", $paymentId)->first();

        $response = $this->makePaymentRequest('/get-payment-status' . "/$paymentId",);

        return $response;
    }

    function cancelPayment(FatoraPaymentRequest $request)
    {

        $data = [
            "payment_id" => $request->payment_id,
            "lang" => "en"
        ];

        $response = $this->makePaymentRequest('/cancel-payment',  $data, "POST");

        if ($response->ErrorCode == PaymentResponseStatus::success) {

            $fatoraPayment = FatoraPayment::where("payment_id", $request->payment_id)->first();

            if (!$fatoraPayment) {
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
        } else {
            $res['error'] = true;
            $res['message'] = $response->ErrorMessage;
            return $res;
        }
    }

    function callback($merchant)
    {
        $fatoraPayment = FatoraPayment::where("merchant", $merchant)->first();
        if (!$fatoraPayment) {
            $res['status'] = PaymentResponseStatus::getName(PaymentResponseStatus::failed);
            $res['error'] = true;
            $res['message'] = __("payment.not_found");
            return $res;
        }

        $response = $this->getPaymentStatus($fatoraPayment->payment_id);

        if ($response->ErrorCode == PaymentResponseStatus::success) {

            $fatoraPayment->status = $response->Data->status;
            $fatoraPayment->transaction_number = $response->Data->rrn;
            $fatoraPayment->notes = $response->Data->notes;
            $fatoraPayment->save();

            $notificationData = [
                "payment_status" =>  $response->Data->status,
                "trip_id" =>  $fatoraPayment->bookings->id,
            ];

            $message_user = "";
            $message_driver = "";

            $send_notification = false;

            if ($response->Data->status == PaymentStatus::canceled) {
                $message_user = __("payment.canceled_payment_status_user");
                $message_driver = __("payment.canceled_payment_status_driver");
                $notificationData["message"] =  $message_driver;
                $send_notification = true;
            }

            if ($send_notification) {
                // notify the user
                $this->send_notification($fatoraPayment->bookings->user->device_token, 'DiamondLine user', $message_user,  $notificationData);

                // notify the driver
                $this->send_notification($fatoraPayment->bookings->driver->device_token, 'DiamondLine', $message_driver,  $notificationData);
            }

            $res['status'] = PaymentResponseStatus::getName(PaymentResponseStatus::success);
            $res['error'] = false;
            $res['message'] = $message_user;
            $res['data'] =  [
                "send_notification" => $send_notification
            ];
            return $res;
        }

        $res['status'] = PaymentResponseStatus::getName(PaymentResponseStatus::failed);
        $res['error'] = true;
        $res['message'] = __("payment.electronic_payment_error");
    }

    function trigger($merchant)
    {

        $fatoraPayment = FatoraPayment::where("merchant", $merchant)->first();
        if (!$fatoraPayment) {
            $res['error'] = true;
            $res['message'] = __("payment.not_found");
            return $res;
        }

        $response = $this->getPaymentStatus($fatoraPayment->payment_id);
        if ($response->ErrorCode == PaymentResponseStatus::success) {

            $fatoraPayment->status = $response->Data->status;
            $fatoraPayment->transaction_number = $response->Data->rrn;
            $fatoraPayment->notes = $response->Data->notes;
            $fatoraPayment->save();

            $notificationData = [
                "payment_status" =>  $response->Data->status,
                "trip_id" =>  $fatoraPayment->bookings->id,
            ];

            $message_user = "";
            $message_driver = "";
            $send_notification = false;

            if ($response->Data->status == PaymentStatus::accepted) {
                $message_user = __("payment.accepted_payment_status_user");
                $message_driver = __("payment.accepted_payment_status_driver");
                $notificationData["message"] =  $message_driver;
                $send_notification = true;

                $apiUserController = new DriversApi();

                $request = new Request([
                    "trip_id" => $fatoraPayment->bookings->id,
                    "end_time" => $fatoraPayment->bookings->end_time,
                    "km" => $fatoraPayment->bookings->km,
                ]);
                $trip_ended = $apiUserController->trip_ended($request);
            } else if ($response->Data->status == PaymentStatus::failed) {
                $message_user = __("payment.failed_payment_status_user");
                $message_driver = __("payment.failed_payment_status_driver");
                $notificationData["message"] =  $message_driver;
                $send_notification = true;
            } else if ($response->Data->status == PaymentStatus::canceled) {
                $message_user = __("payment.canceled_payment_status_user");
                $message_driver = __("payment.canceled_payment_status_driver");
            }

            if ($send_notification) {
                // notify the user
                $this->send_notification($fatoraPayment->bookings->user->device_token, 'DiamondLine', $message_user,  $notificationData);

                // notify the driver
                $this->send_notification($fatoraPayment->bookings->driver->device_token, 'DiamondLine', $message_driver,  $notificationData);
            }

            if (isset($trip_ended)) {
                $trip_ended["status"] = PaymentResponseStatus::getName(PaymentResponseStatus::success);
                return $trip_ended;
            }

            $res['status'] = PaymentResponseStatus::getName(PaymentResponseStatus::success);
            $res['error'] = false;
            $res['message'] = $message_user;
            $res['data'] =  [
                "send_notification" => $send_notification
            ];
            return $res;
        }

        $res['status'] = PaymentResponseStatus::getName(PaymentResponseStatus::failed);
        $res['error'] = true;
        $res['message'] = __("payment.electronic_payment_error");
        return $res;
    }

    function makePaymentRequest($url, $data = null, $method = "GET")
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env("PAYMENT_URL") . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,

            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Basic " . base64_encode(env("FATORA_USERNAME") . ":" . env("FATORA_PASSWORD")),
            ),

            CURLOPT_POSTFIELDS => json_encode($data),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        return $response;
    }
}