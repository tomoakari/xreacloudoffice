<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conference;

/**
 * ログイン認証付きのルーティングのコントローラクラス
 * ※本来的には大機能ごとに分けた方がいいけど、現時点では無意味なので
 * 見通し重視でまとめました
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $outerConfs = array(
            [
                'name' => 'サンプル会議',
                'username' => '松本', 
                'secret' => 'e991f94c5453936e206dabc3542c3344', 
                'password' => '',
                'innerflg' => '0',
                'status' =>'0',
                'schedule' => '',
                'statusStr' =>'未開催',
                'scheduleStr' => '2021/12/20 10:00',
                'url' => 'https://conference.aice.cloud/?secret=e991f94c5453936e206dabc3542c3344'
            ],
            [
                'name' => 'サンプル会議２',
                'username' => '松本', 
                'secret' => 'e991f94c5453936e206dabc3542c3344', 
                'password' => '',
                'innerflg' => '0',
                'status' =>'0',
                'schedule' => '',
                'statusStr' =>'未開催',
                'scheduleStr' => '2021/12/20 10:00',
                'url' => 'https://conference.aice.cloud/?secret=e991f94c5453936e206dabc3542c3344'
            ]
            );
        
        return view('home', ['outerConfs' => $outerConfs]);
        // return view('home');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function mypage()
    {
        $data = [
            'name' => 'テスト会議',
            'username' => 'てすと',
            'secret' => '12345678901234567890',
            'password' => '',
            'innerflg' => 0,
            'status' => new Date(),
        ];
        Conference::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'secret' => $data['secret'],
            'password' => $data['password'],
            'innerflg' => $data['innerflg'],
            'status' => $data['schedule'],
        ]);


        /*
        $auth = Auth::user();
        return view('mypage', [ 'auth' => $auth ]);
        */
        $user = [
            'id' => '111',
            'name' => 'namename',
            'mail' => 'address'
        ];
        return view('mypage', compact('user'));
        
        // return view('mypage');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function conference()
    {
        return view('conference');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reception()
    {
        return view('reception');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function inhouse()
    {
        return view('inhouse');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function company()
    {
        return view('company');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function organize()
    {
        return view('organize');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invite()
    {
        return view('invite');
    }
}
