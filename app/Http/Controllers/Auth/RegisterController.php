<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use App\User;
use App\Secretcode;
use App\Department;
use App\Enrolled;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            //'api_token' => Str::random('alnum', 60),
            'api_token' => Hash::make($data['password']. $data['email']),
        ]);


        if(strlen($data['secret']) == 0){
            return $newUser;
        }

        try{
            // 招待された会社の部署を取得

            $secret = Secretcode::first('secret', $data['secret'])->get();
            $dept = Department::
                where('company_id', $comp[0]->id)->
                where('depid1', 0)
                ->get();
            
            // その部署に配属する
            $enr = new Enrolled();
            $enr->user_id = $newUser->id;
            $enr->company_id = $secret->company_id;
            $enr->department_id = $dept[0]->id;
            $enr->countadminflg = 0;
            $enr->depadminflg = 0;
            $enr->compadminflg = 0;
            $enr->save();

            return $newUser;

        }catch(Exception $err){
            return $newUser;
        } 
    }
}
