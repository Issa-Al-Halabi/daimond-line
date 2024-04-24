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
        return match ($this->route()->getActionMethod()) {
            'createPayment'  =>  $this->getcreatePaymentRules(),
            'cancelPayment'  =>  $this->getcancelPaymentRules(),
        };
    }

    public function getcreatePaymentRules(){
        return [
                "lang" => "required",
                "terminalId" => "required",
                "amount" => "required|integer",
                "callbackURL" => "nullable|url",
                "triggerURL" => "nullable|url",
        ];
    }

    public function getcancelPaymentRules(){
        return [
                "lang" => "required",
                "payment_id" => "required",
        ];
    }
}
