<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use App\User;
use App\Secretcode;
use App\Department;
use App\Enrolled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

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
            //'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * 
     */
    public function pre_check(Request $request){
        $this->validator($request->all())->validate();
        //flash data
        $request->flashOnly( 'email');

        $bridge_request = $request->all();
        // password マスキング
        $bridge_request['password_mask'] = '******';

        return view('auth.register_check')->with($bridge_request);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        /*
        // メール認証なしの時点での処理
        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            //'api_token' => Str::random('alnum', 60),
            'api_token' => Hash::make($data['password']. $data['email']),
        ]);
        */

        // メール認証ありの時点の処理
        $newUser = User::create([
            'email_verify_token' => 12345,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'api_token' => Hash::make($data['password']. $data['email']),
            // 'email_verify_token' => base64_encode($data['email'])
        ]);

        $email = new EmailVerification($newUser);
        Mail::to($user->email)->send($email);

        return $newUser;


        // 以下、招待コードがある場合の処理（別のクラスかも）
        if(strlen($data['secret']) == 0){
            return $newUser;
        }

        try{
            // 使われていないsecretcodeがあるか確認
            $secret = Secretcode::
                where('secret', $data['secret'])->
                where('mail', $data['email'])->
                whereColumn('created_at', 'updated_at')->
                get();
            if(count($secret) == 0){
                return $newUser;
            }

            // 招待された会社の部署を取得
            $dept = Department::
                where('company_id', $secret[0]->company_id)->
                where('depid1', 0)->
                get();
            
            // その部署に配属する
            $enr = new Enrolled();
            $enr->user_id = $newUser->id;
            $enr->company_id = $secret[0]->company_id;
            $enr->department_id = $dept[0]->id;
            $enr->countadminflg = 0;
            $enr->depadminflg = 0;
            $enr->compadminflg = 0;
            $enr->save();

            // secretcodeを使えなくする
            Secretcode::
                where('secret', $data['secret'])->
                where('mail', $data['email'])->
                whereColumn('created_at', 'updated_at')->
                update([
                    'mail' => $secret[0]->mail,
                    'secret' => $secret[0]->secret,
                    'company_id' => $secret[0]->company_id
                ]);

            return $newUser;

        }catch(Exception $err){
            return $newUser;
        } 
    }

    /**
     * Auth::routes()ルーティングのデフォルトメソッドにオーバーライドして
     * 生成時に上記createが呼ばれるようにする
     */
    public function register(Request $request)
    {
        event(new Registered($user = $this->create( $request->all() )));

        return view('auth.registered');
    }

    /**
     * トークンをチェックして本会員フォームに飛ばす処理
     */
    public function showForm($email_token){
        // 使用可能なトークンか
        if ( !User::where('email_verify_token',$email_token)->exists() )
        {
            return view('auth.main.register')->with('message', '無効なトークンです。');
        } else {
            $user = User::where('email_verify_token', $email_token)->first();
            // 本登録済みユーザーか
            if ($user->status == config('const.USER_STATUS.REGISTER')) //REGISTER=1
            {
                logger("status". $user->status );
                return view('auth.main.register')->with('message', 'すでに本登録されています。ログインして利用してください。');
            }
            // ユーザーステータス更新
            $user->status = config('const.USER_STATUS.MAIL_AUTHED');
            $user->verify_at = Carbon::now();
            if($user->save()) {
                return view('auth.main.register', compact('email_token'));
            } else{
                return view('auth.main.register')->with('message', 'メール認証に失敗しました。再度、メールからリンクをクリックしてください。');
            }
        }
    }

    /**
     * 確認画面のViewを返すメソッド
     */
    public function mainCheck(Request $request){
    $request->validate([
      'name' => 'required|string',
      'name_pronunciation' => 'required|string',
      'birth_year' => 'required|numeric',
      'birth_month' => 'required|numeric',
      'birth_day' => 'required|numeric',
    ]);

    //データ保持用
    $email_token = $request->email_token;

    $user = new User();
    $user->name = $request->name;
    $user->name_pronunciation = $request->name_pronunciation;
    $user->birth_year = $request->birth_year;
    $user->birth_month = $request->birth_month;
    $user->birth_day = $request->birth_day;

    return view('auth.main.register_check', compact('user','email_token'));
  }

  /**
   * 本登録して完了画面へ
   */
  public function mainRegister(Request $request){
    $user = User::where('email_verify_token',$request->email_token)->first();
    $user->status = config('const.USER_STATUS.REGISTER');
    $user->name = $request->name;
    $user->name_pronunciation = $request->name_pronunciation;
    $user->birth_year = $request->birth_year;
    $user->birth_month = $request->birth_month;
    $user->birth_day = $request->birth_day;
    $user->save();

    return view('auth.main.registered');
  }
}
