<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Conference;
use App\Company;
use App\Department;
use App\Enrolled;
use App\Secretcode;
use Mail;

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

        $today = date("Y-m-d");
        return $outerConfs = Conference::
            where('innerflg', 0)->
            where('company_id', $company_id[0]->company_id)->
            where('schedule', '>=', $today . " 00:00:00")->
            get();
    }
    public function getInnerConfs(Request $request)
    {
        $company_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();

        $today = date("Y-m-d");
        return $outerConfs = Conference::
            where('innerflg', 1)->
            where('company_id', $company_id[0]->company_id)->
            where('schedule', '>=', $today . " 00:00:00")->
            get();
    }
    public function getTodayOuterConfs(Request $request)
    {
        $company_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();

        $today = date("Y-m-d");
        return $outerConfs = Conference::
            where('innerflg', 0)->
            where('company_id', $company_id[0]->company_id)->
            whereDate('schedule', $today)->
            //limit(3)->get();
            get();
    }
    public function getTodayInnerConfs(Request $request)
    {
        $company_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();

        $today = date("Y-m-d");
        return $outerConfs = Conference::
            where('innerflg', 1)->
            where('company_id', $company_id[0]->company_id)->
            whereDate('schedule', $today)->
            //limit(3)->get();
            get();
    }
    public function createConf(Request $request)
    {
        $company_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();
        $data = [
            'name' => $request['name'],
            'company_id' => $company_id[0]->company_id,
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

            $secret = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz"), 0, 8);

            $comp = new Company();
            $comp->name = $request['name'];
            $comp->plan = $request['plan'];
            $comp->secret = $secret;
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
                'result' => true,
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
                'result' => false,
                'data' => $err
            ];
        } 
    }
    public function joinCompany(Request $request)
    {
        try{
            DB::beginTransaction();

            $comp = Company::first('secret', $request['secret'])->select('id')->get();
            $dept = Department::
                where('company_id', $comp[0]->id)->
                where('depid1', 0)
                ->get();

            $enr = new Enrolled();
            $enr->user_id = Auth::id();
            $enr->company_id = $comp[0]->id;
            $enr->department_id = $dept[0]->id;
            $enr->countadminflg = 0;
            $enr->depadminflg = 0;
            $enr->compadminflg = 0;
            $enr->save();

            DB::commit();

            $result = [
                'result' => true,
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
                'result' => false,
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
            $enr = Enrolled::where('user_id', Auth::id())->get();
            $comp = Company::find($enr[0]->company_id);
            $enrList = Enrolled::where('company_id', $enr[0]->company_id)->get();
            $uidList = array();
            foreach($enrList as $item){
                array_push($uidList, $item->user_id);
            }

            // $uidList = array_column($enrList, 'user_id');
            $userList = User::whereIn('user_id', $uidList)->get();
            
            if(count($enr) == 0){
                return [
                    'result' => true,
                    'data' => [
                        'company_info' => $comp,
                        'company_member' => $userList
                    ]
                ];
            }else{
                $comp = Company::find($enr[0]->company_id);
                return [
                    'result' => true,
                    'data' => [
                        'company_info' => $comp,
                        'company_member' => $userList
                    ]
                ];
            }
            
            
        }catch(Exception $err){
            return [
                'result' => false,
                'data' => $err
            ];
        }
    }

    /**
     * メンバー招待メールを送信する
     */
    public function inviteMember(Request $request){
        $mailList = $request['mails'];
        $BASE_URL = "https://kaigishitsu.aice.cloud/register/?secret=";
        $SUBJECT = "さんから会議室に招待されています";

        try{
            foreach($mailList as $mail){
                // シークレットコードを生成、保存する
                $secret = substr(md5(mt_rand()), 0, 16);
                $company_id = Enrolled::first('user_id', Auth::id())->select('company_id')->get();

                $scr = new Secretcode();
                $scr->mail = $mail;
                $scr->secret = $secret;
                $scr->company_id = $company_id[0]->company_id;
                $scr->save();

                $data = [
                    'name' => Auth::user()->name,
                    'url' => $BASE_URL . $secret
                ];

                // メールを送信する
                Mail::send('emails.invite', $data, function($message)use($SUBJECT, $mail){
                    $message
                        ->to($mail)
                        ->from("register@kaigishitsu.aice.cloud","aiforusサポート")
                        ->subject(Auth::user()->name. $SUBJECT);
                });
            }

            return [
                'result' => true,
                'data' => ''
            ];
        }catch(Exception $err){
            return [
                'result' => false,
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
        return view('home', );
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
