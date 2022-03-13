<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Conference;
use App\Company;
use App\Department;
use App\Enrolled;

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

        try{
            $data = [
                'name' => $request['name'],
                'username' => $request['username'],
                'secret' => $request['secret'],
                'password' => $request['password'],
                'innerflg' => $request['innerflg'],
                'status' => $request['status'],
                'schedule' => $request['schedule'],
            ];
            $conf = Conference::create($data);

            return [
                'result' => true,
                'data' => $conf
            ];

        }catch(Exception $err){
            return [
                'result' => false,
                'data' => $err
            ];
        }
    }

    public function getOuterConfs(Request $request)
    {
        $company_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();

        $today = date("Y-m-d 0:0:0");
        return $outerConfs = Conference::
            where('innerflg', 0)->
            where('company_id', $company_id)->
            where('schedule', '>=', $today)->
            get();
    }
    public function getInnerConfs(Request $request)
    {
        $company_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();

        $today = date("Y-m-d 0:0:0");
        return $outerConfs = Conference::
            where('innerflg', 1)->
            where('company_id', $company_id)->
            where('schedule', '>=', $today)->
            get();
    }
    public function createConf(Request $request)
    {
        $data = [
            'name' => $request['name'],
            'username' => $request['username'],
            'secret' => $request['secret'],
            'password' => $request['password'],
            'innerflg' => $request['innerflg'],
            'status' => $request['status'],
            'schedule' => $request['schedule'],
        ];
        $result = Conference::create($data);
        return $result;   
    }

    
    public function createCompany(Request $request)
    {
        try{
            DB::beginTransaction();

            $comp = new Company();
            $comp->name = $request['name'];
            $comp->plan = $request['plan'];
            $comp->createuserid = Auth::id();
            $comp->save();

            $dept = new Department();
            $dept->name = 'いらないかも';
            $dept->company_id = $comp->id;
            $dept->depid1 = 0;
            $dept->depname1 = 'デフォルト部署';
            $dept->depid2 = 0;
            $dept->depname2 = '';
            $dept->depid3 = 0;
            $dept->depname3 = '';
            $dept->save();

            $enr = new Enrolled();
            $enr->user_id = Auth::id();
            $enr->company_id = $comp->id;
            $enr->department_id = $dept->id;
            $enr->countadminflg = 1;
            $enr->depadminflg = 1;
            $enr->compadminflg = 1;
            $enr->save();

            DB::commit();

            $result = [
                'result' => 'true',
                'data' => [
                    'company' => $comp,
                    'department' => $dept,
                    'enrolled' => $enr
                ]
            ];
            return $result; 

        }catch(Exception $err){
            DB::rollBack();
            return [
                'result' => 'false',
                'data' => $err
            ];
        } 
    }

    /**
     * 
     */
    public function getCompanyInfo(Request $request)
    {
        try{
            $comp;
            if($request->has('company_id')){
                $comp = Company::find($request['company_id']);
            }else{
                $comp_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();
                $comp = Company::find($comp_id);
            }
            return [
                'result' => 'true',
                'data' => $comp
            ];
        }catch(Exception $err){
            return [
                'result' => 'false',
                'data' => $err
            ];
        }
    }
    
    /**
     * Show the application dashboard.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $company_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();
        // 本日の日付で絞る
        $today = date("Y-m-d");
        $outerConfs = Conference::
            where('innerflg', 0)->
            where('company_id', $company_id)->
            whereDate('schedule', $today)->
            limit(3)->get();

        $innerConfs = Conference::
            where('innerflg', 1)->
            where('company_id', $company_id)->
            whereDate('schedule', $today)->
            limit(3)->get();
        
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
