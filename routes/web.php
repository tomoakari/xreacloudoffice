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
