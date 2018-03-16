<?php

namespace App\Http\Requests\Api;


class SocialAuthorizationRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [

            'code'         => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
        ];

        //如果是微信登录,并且没有传授权码
        if ($this->social_type == 'weixin' && !$this->code) {
            $rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
