<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class VerificationCodesRequest extends FormRequest
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
            'phone'=> 'required|regex:/^1[34578]\d{9}$/|unique:users',
        ];
    }

    public function attributes()
    {
        return [
            'phone'=>'手机号码',
        ];
    }
}
