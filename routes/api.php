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
   'namespace'  => 'App\Http\Controller\Api'
], function ($api){
   // sms validate
   $api->post('verificationCodes', 'VerificationController@store')
       ->name('api.verificationCodes.store');
});

