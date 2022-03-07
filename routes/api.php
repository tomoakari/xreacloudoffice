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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// 会議室追加（あとで認証入れてputとかにする）
Route::get('/addconf', 'HomeController@addConference');

// 外部会議室取得（あとで認証入れてputとかにする）
//Route::get('/getOuterConfs', 'HomeController@getOuterConfs');


Route::post('/createcompany', 'HomeController@createCompany');