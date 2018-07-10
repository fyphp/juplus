<?php

namespace App\Http\Controllers\Wechat;

use App\Model\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Factory;

class WechatController extends Controller
{
    protected $member;

    public function __construct()
    {
        $this->member = new Member();
    }

    /**
     * 处理微信的请求消息
     * @return string
     */
    public function serve()
    {
        $app = $this->officialAccount();
        // 从项目实例中得到服务端应用实例。
        $app->server->push(function ($message) use($app) {
            $user_openid = $message['FromUserName'];
            $user = $app->user->get($user_openid);//获取用户数据
            switch ($message['MsgType']) {
                case 'event':
                    if ($message['Event'] == 'subscribe') {//关注注册用户基本信息
                        //扫码带参数二维码关注,有EvenKey值
                        if (!empty($message['EventKey'])){

                        }

                        //普通关注
                        return $this->autoRegister($user);
                    }
                    if ($message['Event'] == 'unsubscribe'){ //取消关注事件
                        return $this->isCancel($user);
                    }
                    if ($message['Event'] == 'SCAN'){//用户已关注时扫带参数二维码

                    }
                    return '欢迎关注';
                    break;
                case 'text':
                    return '收到消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });
        $response = $app->server->serve();

        return $response;
    }

    /**
     * 关注自动完成基本信息的注册
     */
    public function autoRegister($param)
    {
        $result = $this->member->getOpenidMember($param['openid']);
        if ($result['is_cancel'] == 1){
            $this->member->cancel($param['openid']);
            return '欢迎再次关注诺德传动';
        }
        if (!$result){
            $this->member->inster($param);
            return '欢迎关注诺德传动';
        }
    }

    /**
     * 取消关注
     */
    public function isCancel($param)
    {
        $this->member->cancel($param['openid']);
    }


}
