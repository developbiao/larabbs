<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SocialAuthorizationRequest;
use Illuminate\Http\Request;

class AuthorizationsController extends Controller
{
    public function store($type, SocialAuthorizationRequest $request)
    {
        if (!in_array($type, ['weixin']))
        {
           return $this->response->errorBadRequest();
        }

        $driver = \Socialite::dirver($type);

        try {
            if($code = $request->code)
            {
                $response = $driver->getAccessTokenResponse($code);
                $token = array_get($response, 'access_token');
            }
            else
            {
                $token = $request->access_token;
                if($type == 'weixin')
                {
                    $driver->setOpenId($request->openid);

                }

            }

            $oauthUser = $driver->userFromToken($token);

        } catch (\Exception $e) {
           return $this->response->errorunauthorized('parameters error, can not get user information');
        }

        switch($type)
        { // TODO:: social weixin authorization

        }
    }
}
