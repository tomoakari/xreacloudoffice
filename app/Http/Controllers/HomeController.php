<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Conference;
use App\Company;
use App\Department;
use App\Enrolled;
use App\Secretcode;
use App\Confmember;

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
        $company_id = $this->getMyCompanyId();
        if($company_id == 0){
            return [];
        }

        $today = date("Y-m-d");
        return $outerConfs = Conference::
            where('innerflg', 0)->
            where('company_id', $company_id)->
            where('schedule', '>=', $today . " 00:00:00")->
            get();
    }
    public function getInnerConfs(Request $request)
    {
        $company_id = $this->getMyCompanyId();
        if($company_id == 0){
            return [];
        }

        $today = date("Y-m-d");
        return $outerConfs = Conference::
            where('innerflg', 1)->
            where('company_id', $company_id)->
            where('schedule', '>=', $today . " 00:00:00")->
            get();
    }
    public function getTodayOuterConfs(Request $request)
    {
        $company_id = $this->getMyCompanyId();
        if($company_id == 0){
            return [];
        }

        $today = date("Y-m-d");
        return $outerConfs = Conference::
            where('innerflg', 0)->
            where('company_id', $company_id)->
            whereDate('schedule', $today)->
            //limit(3)->get();
            get();
    }
    public function getTodayInnerConfs(Request $request)
    {
        $company_id = $this->getMyCompanyId();
        if($company_id == 0){
            return [];
        }

        $today = date("Y-m-d");
        return $outerConfs = Conference::
            where('innerflg', 1)->
            where('company_id', $company_id)->
            whereDate('schedule', $today)->
            //limit(3)->get();
            get();
    }
    public function createConf(Request $request)
    {
        $company_id = $this->getMyCompanyId();
        if($company_id == 0){
            return [];
        }
        $data = [
            'name' => $request['name'],
            'company_id' => $company_id,
            'username' => $request['username'],
            'secret' => $request['secret'],
            'password' => $request['password'],
            'innerflg' => $request['innerflg'],
            'status' => $request['status'],
            'schedule' => $request['schedule'],
        ];
        $result = Conference::create($data);

        if($request->has('invitedUser')){
            $invitedUser = json_decode($request['invitedUser']);
            foreach($invitedUser as $item){
                $invitedData = [
                    'conference_id' => $result['id'],
                    'user_id' => $item->id,
                    'user_name' => $item->name,
                ];
                Confmember::create($invitedData);
            }
        }
        
        return $result;   
    }
    public function updateConf(Request $request)
    {
        try{
            Conference::
                find($request['id'])
                ->update([
                    'name' => $request['name'],
                    'schedule' => $request['schedule']
                ]);
            return "true";
        }catch(Exception $err){
            return $err;
        }   
    }
    public function deleteConf(Request $request)
    {
        try{
            Conference::
                find($request['id'])
                ->update(['status' => -1]);
            return "true";
        }catch(Exception $err){
            return $err;
        }
        
    }
    public function getMembers()
    {
        try{
            // 会社IDから所属リスト一覧を取得
            $company_id = $this->getMyCompanyId();
            $enrList = Enrolled::where('company_id', $company_id)->get();

            // ユーザ
            $uidList = array();
            foreach($enrList as $item){
                array_push($uidList, $item->user_id);
            }
            $userList = User::whereIn('id', $uidList)->
                select('id','name','email')->get();

            $deptList = Department::
                where('company_id', $company_id)
                ->get();
            
            return [
                'data' => [
                    "enrollList" => $enrList,
                    "userList" => $userList,
                    "deptList" => $deptList
                ]
            ];
            
        }catch(Exception $err){
            return [
                'data' => []
            ];
        }
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
            $dept->depname1 = '所属設定なし';
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
    public function getCompanyInfo()
    {
        try{
            $company_id = $this->getMyCompanyId();
            if($company_id == 0){
                return [
                    'result' => false,
                    'data' => ''
                ];
            }

            // 会社
            $comp = Company::where('id', $company_id);

            // 所属メンバー
            $enrList = Enrolled::where('company_id', $company_id)->get();
            $uidList = array();
            foreach($enrList as $item){
                array_push($uidList, $item->user_id);
            }
            $userList = User::whereIn('id', $uidList)->
                select('id','name','email')->get();

            // 招待済みメンバー
            $invitedList = Secretcode::
                where('company_id', $company_id)->
                whereColumn('created_at', 'updated_at')->
                get();
            
            return [
                'result' => true,
                'data' => [
                    'company_info' => $comp,
                    'company_member' => $userList,
                    'invited_member' => $invitedList
                ]
            ];
            
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
        $SUBJECT = "さんからAICE.CLOUDに招待されています";

        try{
            foreach($mailList as $mail){
                // シークレットコードを生成、保存する
                $secret = substr(md5(mt_rand()), 0, 16);
                $company_id = $this->getMyCompanyId();

                $scr = new Secretcode();
                $scr->mail = $mail;
                $scr->secret = $secret;
                $scr->company_id = $company_id;
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

    public function getUserInfo(){
        return User::where('id',  Auth::id())->select('id','name','email')->get()[0];
    }

    public function getEnrollInfo(){
        $company_id = $this->getMyCompanyId();
        if($company_id == 0){
            return [
                'company_id' => 0,
                'company_name' => '未登録',
                'dept_name_1' => '未登録',
                'dept_id_1' => 0,
                'depadminflg' => 0,
                'compadminflg' => 0
            ];
        }

        // 会社
        $comp = Company::where('id', $company_id)->get()[0];

        // 所属
        $enr = Enrolled::where('user_id', Auth::id())->get()[0];

        // 部署
        $dept = Department::where('id', $enr->department_id)->get()[0];

        return [
            'company_id' => $comp->id,
            'company_name' => $comp->name,
            'dept_name_1' => $dept->depname1,
            'dept_id_1' => $dept->depid1,
            'depadminflg' => $enr->depadminflg,
            'compadminflg' => $enr->compadminflg
        ];
    }

    public function updateUser(Request $request)
    {
        try{
            User::
                find($request['id'])
                ->update(['name' => $request['name']]);
            return "true";
        }catch(Exception $err){
            return $err;
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
     * ヘルプ画面
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function help()
    {   
        return view('help', );
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

    /**
     * ログインユーザの所属する会社を取得する
     * @return String company_id
     */
    public function getMyCompanyId(){
        $enr = Enrolled::where('user_id', Auth::id())->get();
        if(count($enr) == 0){
            return 0;
        }
        return $enr[0]->company_id;
    }
}
