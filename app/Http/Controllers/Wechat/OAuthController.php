<?php
/**
 * Created by PhpStorm.
 * User: juplus-06
 * Date: 2018/7/9
 * Time: 13:09
 */

namespace App\Http\Controllers\Wechat;


use App\Model\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class OAuthController extends Controller
{
    private $member;

    function __construct()
    {
        $this->member = new Member();
    }

    /**
     * 获取是否授权
     */
    public function index(Request $request)
    {
        $param = $request->input();
//        dd(Session::has('wechat_user'));
        if (Session::has('wechat_user')){
            $json = Session::get('wechat_user');
            $data = '?member_id="'.$json['id'].'&name='.$json['name'];
            if(isset($param['activity_id'])){
                session(['activity_id'=>$param['activity_id']]);
            }
            if(isset($param['question_id'])){
                session(['question_id'=>$param['question_id']]);
            }
            header('Location:https://juplus.cn/nord-view/'.$param['url'].$data);exit();
        }else{
            session(['target_url'=>$param['url']]);
            if(isset($param['activity_id'])){
                session(['activity_id'=>$param['activity_id']]);
            }
            if(isset($param['question_id'])){
                session(['question_id'=>$param['question_id']]);
            }
            $app = $this->officialAccount();
            //发起授权
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect('http://m.juplus.cn/getSession');
//            $oauth = $app->oauth;
//            $user = $oauth->user();
//            $userarray = $user->toArray();
//            $result = $this->isLogin($userarray['original']);
//            session(['wechat_user'=>$result]);
            return $response;
        }
    }


    /**
     * 授权回调地址
     */
    public function getSession(Request $request)
    {
        $app = $this->officialAccount();
        $member = new Member();
        $oauth = $app->oauth;
        $user = $oauth->user();
        $userarray = $user->toArray();
        //检查是否有基本信息
        $result = $this->isLogin($userarray['original']);
        Session::put('wechat_user',$result);
        $member_info = $member->getOpenidMember($result['openid']);
        $data = '?member_id='.$member_info['id'].'&name='.$member_info['name'];
        if(session('activity_id')){
            $data .= '&activity_id='.session('activity_id');
        }
        if(session('question_id')){
            $data .= '&question_id='.session('question_id');
        }
        header('Location:https://juplus.cn/nord-view/'.session('target_url').$data);
        exit();
    }

    /**
     * 检查是否有基本信息与注册
     */
    public function isLogin($original)
    {
        $member = $this->member;
        $info = $member->where('openid',$original['openid'])->first();
        if ($info){//有基本信息
            return $info;
        }else{//无基本信息
            $result = $member->inster($original);
            if ($result['code'] == 1){
               return $member->find($result['data']);
            }
        }
    }
}