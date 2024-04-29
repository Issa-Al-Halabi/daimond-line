<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FatoraPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            "amount" => "required|integer",
        ];
        // return match ($this->route()->getActionMethod()) {
        //     'createPayment'  =>  $this->getcreatePaymentRules(),
        //     'cancelPayment'  =>  $this->getcancelPaymentRules(),
        // };
    }

    public function getcreatePaymentRules()
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            "amount" => "required|integer",
        ];
    }

    public function getcancelPaymentRules()
    {
        return [
            "payment_id" => "required",
        ];
    }
}