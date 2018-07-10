<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SmsController;
use App\Model\Member;
use App\Model\Sms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    private $member;
    private $sms;

    function __construct()
    {
        $this->member = new Member();
        $this->sms = new Sms();
    }

    /**
     * @param Request $request
     * 注册post
     */
    public function index(Request $request)
    {
        if (Request()->isMethod('post')){
            $param = $request->input();
            $reuslt = $this->member->edit($param);
            if ($request['code'] == 1){
                $info = $this->member->find($param['id']);
                session(['wechat_user'=>$info]);
            }
            return response()->json($reuslt);
        }
    }

    /**
     * 验证码
     */
    public function verificationCode(Request $request)
    {
        if (Request()->isMethod('post')){
            $param = $request->input();
            $data = rand(pow(10,(6-1)), pow(10,6)-1);
            $param['data'] = $data;
            $sendsms = new SmsController();
            $result = $sendsms->sendTemplateSMS($param['tel'],[$data],'');
            if ($result == 1){
                $sms = $this->sms;
                $sms->instrt($param);
                return response()->json(['msg'=>'发送成功','code'=>1,'data'=>$data]);
            }else{
                return response()->json(['msg'=>'发送失败','code'=>0,'data'=>'']);
            }

        }
    }
}
