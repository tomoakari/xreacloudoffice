<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// トップ画面
Route::get('/', function () {
    //return view('welcome');
    return view('outertop');
});

// mail/pwを入力して確認画面を開く
Route::post('register/pre_check', 'Auth\RegisterController@pre_check')->name('register.pre_check');

// 仮登録してメース送信しました画面を開く
//Route::post('register', 'Auth\RegisterController@register');
// registerで暗黙的に使えるのでここでは記述なし

// メール記載のURLから認証して名前入力画面とかを開く
Route::get('register/verify/{token}', 'Auth\RegisterController@showForm');

// 名前の確認画面を開く
Route::post('register/main_check', 'Auth\RegisterController@main_check')->name('register.main_check');

// 登録完了画面を開く
Route::post('register/main_register', 'Auth\RegisterController@mainRegister')->name('register.main.registered');


Auth::routes();

// ダッシュボード
Route::get('/home', 'HomeController@index')->name('home');

// ヘルプ
Route::get('/help', 'HomeController@help')->name('help');

// マイページ
Route::get('/mypage', 'HomeController@mypage')->name('mypage');
Route::get('/updateUser', 'HomeController@updateUser')->name('updateUser');

// 会議室管理画面
Route::get('/conference', 'HomeController@conference')->name('conference');

// 外部会議・内部会議だけど、管理画面に入れ込んじゃうかも
Route::get('/conference/reception', 'HomeController@reception')->name('reception');
Route::get('/conference/inhouse', 'HomeController@inhouse')->name('inhouse');

// 会社管理画面
Route::get('/company', 'HomeController@company')->name('company');
Route::get('/company/organize', 'HomeController@organize')->name('organize');
Route::get('/company/invite', 'HomeController@invite')->name('invite');
Route::get('/createCompany', 'HomeController@createCompany')->name('createCompany');
Route::get('/getCompanyInfo', 'HomeController@getCompanyInfo')->name('getCompanyInfo');
Route::get('/joinCompany', 'HomeController@joinCompany')->name('joinCompany');
Route::get('/inviteMember', 'HomeController@inviteMember')->name('inviteMember');


// 会議管理画面
Route::get('/addconf', 'HomeController@addConference')->name('addconf');
Route::get('/getOuterConfs', 'HomeController@getOuterConfs')->name('getOuterConfs');
Route::get('/getInnerConfs', 'HomeController@getInnerConfs')->name('getInnerConfs');
Route::get('/getTodayOuterConfs', 'HomeController@getTodayOuterConfs')->name('getTodayOuterConfs');
Route::get('/getTodayInnerConfs', 'HomeController@getTodayInnerConfs')->name('getTodayInnerConfs');
Route::get('/createConf', 'HomeController@createConf')->name('createConf');
Route::get('/deleteConf', 'HomeController@deleteConf')->name('deleteConf');
Route::get('/updateConf', 'HomeController@updateConf')->name('updateConf');
Route::get('/getMembers', 'HomeController@getMembers')->name('getMembers');

// マイページ
Route::get('/getUserInfo', 'HomeController@getUserInfo')->name('getUserInfo');
Route::get('/getEnrollInfo', 'HomeController@getEnrollInfo')->name('getEnrollInfo');



/*
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/mypage', 'HomeController@mypage')->name('mypage');

Route::get('/conference', 'HomeController@conference')->name('conference');
Route::get('/conference/reception', 'HomeController@reception')->name('reception');
Route::get('/conference/inhouse', 'HomeController@inhouse')->name('inhouse');

Route::get('/company', 'HomeController@company')->name('company');
Route::get('/company/organize', 'HomeController@organize')->name('organize');
Route::get('/company/invite', 'HomeController@invite')->name('invite');
*/