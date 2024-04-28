<?php

namespace App\Http\Traits;

trait SendNotification
{

    public function send_notification($device_token, $title, $body, $data = [])
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        //$SERVER_API_KEY = 'AAAAmZXuq1U:APA91bERLjTZ7zqcKPXXy6kpKgDus2bv2XUpo_lJmvQJvDgJ9Qlz6UDOYU-gxsB_1mefPr1MPQ-G5e1N6s7xNKHRUqEDe8ge6_y-I2AZDq1n36trquN7NyhKHYKbvkhlwqzGSdG2_N5p';

        $SERVER_API_KEY = 'AAAAHWYE85g:APA91bGs3RkA5CP0Yb9OrQJ-2rJLvLgJAhNS8mRxoBQ7Ly3PCLe_MlcaGHBkr7lsdkkq-OMVFhXiRvoPc7eVmfhCWU6fClZUpv6_kHDda3ES-FRVzGgDCuAiqIwa2NSd_XABLxqrz2L9';

        $notificationData = [
            'to' => $device_token, //$FcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];
        $headers = [
            'Authorization:key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $encodedData = json_encode($notificationData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        //dd($result);
    }
}