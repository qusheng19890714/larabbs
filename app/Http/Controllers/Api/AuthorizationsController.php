<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthorizationsController extends Controller
{
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        //没有集成该第三方登录
        if (!in_array($type, ['weixin'])) {

            return $this->response->errorBadRequest();
        }

        $driver = Socialite::driver($type);

        try{

            //授权码获取
            if ($code = $request->code) {

                $response = $driver->getAccessTokenResponse($code);
                $token    = array_get($response, 'access_token');

            }else{ //access_token获取

                $token = $request->access_token;

                if ($type == 'weixin') {

                    $driver->setOpenId($request->openid);
                }
            }

            $oauthUser = $driver->userFromToken($token);

        }catch(\Exception $e) {

            return $this->response->errorUnauthorized("参数错误, 未获取用户信息");
        }

        switch($type)
        {
            case 'weixin' :

                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') :null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                }else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                //创建用户
                if (!$user) {

                    $user = User::create([
                        'name'          => $oauthUser->getNickname(),
                        'avatar'        => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid'=> $unionid,
                    ]);
                }

                break;
        }

        return $this->response->array(['token'=>$user->id]);

    }
}
