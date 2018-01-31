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

Route::post('v1/messages', 'TelegramController@sendMessages');

Route::post('v1/latestActivities', 'TelegramController@getLatestActivities');

Route::post('v1/setwebhook', 'TelegramController@setWebHook');

Route::post('v1/removewebhook', 'TelegramController@removeWebHook');