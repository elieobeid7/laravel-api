<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAuthRequest extends FormRequest
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
            'token' => 'required',
            'userID' => 'required|string',
            'subscriptionId' => 'required|string',
            'msisdn' => 'required|string',
            'operatorId' => 'required|string',
            'subscriptionStatus' => 'required|string',
        ];
    }
}
