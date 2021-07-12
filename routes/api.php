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


Route::post("/login",function(){
    $email = request()->get("email");
    $password = request()->get("password");
    $user = AppUser::where("email",$email)->first();
    if ($user && Hash::check($password, $user->password)) {
        $token = str_random();
        $user->token = $token;
        $user->save();
        return [
            "token" => $token,
            "user" => $user
        ];
    }else{
        abort(401);
    }
});