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
        if (empty(session('wechat_user'))){
            session(['target_url'=>$param['url']]);
            $app = $this->officialAccount();
            //发起授权
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect('http://m.juplus.cn/getSession');
            return $response;
        }else{
            $json = response()->json(session('wechat_user'));
            header('Location:'.$param['url'].'?data='.$json);exit();
        }
    }


    /**
     * 授权回调地址
     */
    public function getSession(Request $request)
    {
        $app = $this->officialAccount();
        $oauth = $app->oauth;
        $user = $oauth->user();
        $userarray = $user->toArray();
        //检查是否有基本信息
        $result = $this->isLogin($userarray['original']);
        session(['wechat_user'=>$result]);

        $json = response()->json(session('wechat_user'));
        header('Location:'.session('target_url').'?data='.$json);exit();
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