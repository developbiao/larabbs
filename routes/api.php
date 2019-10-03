<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
   'namespace'  => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array'],
], function ($api){

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ],function($api){
        // sms validate
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        // user register
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        // captchas
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');

        // oauth login
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');

        // general login
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');

        // refresh token
        $api->put('authorizations', 'AuthorizationsController@update')
            ->name('api.authorizations.update');

        // delete token
        $api->delete('authorizations', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');

        // ---- required token -------
        $api->group(['middleware' => 'api.auth'], function ($api){
           // current user information
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');

            // image resource
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');

            // edit user information
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');
        });
    });
});

