<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodesRequest;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodesRequest $request, EasySms $easySms)
    {
        $captchaData = \Cache::get($request->captcha_key);

        if (!$captchaData) {

            return $this->response->error('验证码已经失效', 422);

        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {

            //验证错误就清除缓存
            \Cache::forget($$request->captcha_key);
            return $this->response->errorUnauthorized('验证码错误');
        }

        $phone = $captchaData['phone'];

        if (!app()->environment('production')) {

            $code = '1234';

        }else {

            //生成4位随机数, 左侧补零
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {

                $result = $easySms->send($phone, [
                    'content' => "【大洋柜】您的验证码是{$code}。如非本人操作，请忽略本短信",
                ]);
            } catch (ClientException $exception) {

                $response = $exception->getResponse();
                $result = json_decode($response->getBody()->getContents(), true);
                return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
            }
        }

        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinutes(10);

        //缓存验证码, 10分钟内有效
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array(['key' => $key, 'expired_at' => $expiredAt->toDateTimeString()])
            ->setStatusCode(201);
    }
}
