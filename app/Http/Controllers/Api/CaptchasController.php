<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchasRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;


class CaptchasController extends Controller
{
    public function store(CaptchasRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key   = 'captcha-' . str_random(15);
        $phone = $request->phone;

        $captcha  = $captchaBuilder->build();
        $expiredAt= now()->addMinutes(2);

        \Cache::put($key, ['code'=>$captcha->getPhrase(), 'phone'=>$phone], $expiredAt);

        $result = [

            'captcha_key' =>$key,
            'expired_at'  =>$expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
