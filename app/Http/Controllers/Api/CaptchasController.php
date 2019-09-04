<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-' . str_random(15);
        $phone = $request->input('phone');

        $captcha = $captchaBuilder->build();
        $expireAt = now()->addMinutes(2);

        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expireAt);

        $result = [
            'captcha_key'           => $key,
            'expire_at'             => $expireAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);

    }
}
