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
     * 
     */
    public function addConference(Request $request)
    {
        // https://kaigishitsu.aice.cloud/addconf?name=%E7%A7%8B%E8%91%89%E5%8E%9F%E9%8A%80%E8%A1%8C%E6%A7%98%E6%89%93%E3%81%A1%E5%90%88%E3%82%8F%E3%81%9B&username=%E5%A4%A7%E7%9F%A2%E9%83%A8&secret=2345678901234567890&password=%20&innerflg=0&status=1&schedule=2021-12-20%2010:10:10
        // など

        $data = [
            'name' => $request['name'],
            'username' => $request['username'],
            'secret' => $request['secret'],
            'password' => $request['password'],
            'innerflg' => $request['innerflg'],
            'status' => $request['status'],
            'schedule' => $request['schedule'],
        ];
        Conference::create($data);

        $user = [
            'id' => '111',
            'name' => 'namename',
            'mail' => 'address'
        ];
        return ['code' => '111', 'content' => 'ok'];
        
    }

    
    /**
     * Show the application dashboard.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            // 本日の日付で絞る
            $outerConfs = Conference::where('innerflg', 0)->limit(3)->get();
            $innerConfs = Conference::where('innerflg', 1)->limit(3)->get();
        
        return view('home', ['outerConfs' => $outerConfs, 'innerConfs' => $innerConfs]);
        // return view('home');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function mypage()
    {
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
        $outerConfs = Conference::where('innerflg', 0)->get();
        $innerConfs = Conference::where('innerflg', 1)->get();
        return view('conference', ['outerConfs' => $outerConfs, 'innerConfs' => $innerConfs]);
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
