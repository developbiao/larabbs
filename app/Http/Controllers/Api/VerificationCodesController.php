<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchaData = \Cache::get($request->captcha_key);

        if( !$captchaData )
        {
           return $this->response->error('图片验证码已失效', 422);
        }

        if( !hash_equals($captchaData['code'], $request->captcha_code) )
        {
           \Cache::forget($request->captcha_key);
           return $this->response->errorUnauthorized('验证码错误');
        }

        $phone = $captchaData['phone'];

        if(!app()->environment('production'))
        {
           $code = '1234';
        }
        else
        {
            // generate 4 random number
            $code = str_pad( random_int(1, 9999), 4, 0, STR_PAD_LEFT );
            try{
                $result = $easySms->send($phone, [
                    'content'  => "【Skill社区】您的验证码是{$code}。如非本人操作，请忽略本短信"
                ]);
            } catch (NoGatewayAvailableException $exception){
                $message = $exception->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinute(10);

        // cache verification code 10  minutes
        Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        // clear captcha code
        \Cache::forget($request->captcha_key);

        return $this->response->array([
           'key'        => $key,
           'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
