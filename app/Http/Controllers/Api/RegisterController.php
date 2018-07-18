<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SmsController;
use App\Model\Member;
use App\Model\Sms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use QcloudImage;

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
            $result = $sendsms->sendTemplateSMS($param['tel'],[$data],'261298');
            if ($result == 1){
                $sms = $this->sms;
                $sms->instrt($param);
                return response()->json(['msg'=>'发送成功','code'=>1,'data'=>$data]);
            }else{
                return response()->json(['msg'=>'发送失败','code'=>0,'data'=>'']);
            }
        }
    }

    /**
     * 名片识别
     */
    public function Qcl(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            $appid = '1256441419';
            $secretId = 'AKID7A4nKgh0xmQxJVqQ9mFBwVt8C5pNmSmy';
            $secretKey = 'AA1hCZlogNBsQo160pY7WkmeQ1RjqmX8';
            $bucket = 'ocr';

            $client = new QcloudImage\CIClient($appid, $secretId, $secretKey, $bucket);

            $client->setTimeout(30);

            //名片识别
            $ret = $client->namecardDetect(['urls'=>['http://m.juplus.cn'.$param['file']]], 0);

            return $ret;
        }


    }
}
