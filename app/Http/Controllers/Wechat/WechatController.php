<?php

namespace App\Http\Controllers\Wechat;

use App\Model\AutoReply;
use App\Model\Grouping;
use App\Model\Member;
use App\Model\MemberGroup;
use App\Model\qrcode;
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
                            //分配标签id
                            return $this->EventKeyRegister($user,$message);
                        }
                        //普通关注
                        return $this->autoRegister($user);
                    }
                    if ($message['Event'] == 'unsubscribe'){ //取消关注事件
                        return $this->isCancel($user);
                    }
                    if ($message['Event'] == 'SCAN'){//用户已关注时扫带参数二维码
                        //分配标签id
                        return $this->SCANDataQrocd($user,$message);
                    }
                    return '欢迎关注';
                    break;
                case 'text':
                    return $this->autoMessage($message['Content']);
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

    /**
     * 已关注扫码带参数二维码,分配标签id与二维码id
     */
    public function SCANDataQrocd($user,$message)
    {
        $membergroup = new MemberGroup();
        $qrcode = new qrcode();

        $openid = $user['openid'];
        $qrcode_data = $message['EventKey'];
        //根据openid获取用户数据
        $member = $this->member->getOpenidMember($openid);

        //查询标签与二维码推送,并关联到用户
        $id_info = explode('_',$qrcode_data);
        $result = $membergroup->addMemberActivity([
            'data_qrcode_id' => $id_info[0],
            'group_id' => $id_info[1],
            'member_id' => $member['id']
        ]);
        if ($result['code'] == 1){//关系一级标签
            $this->setMemberLabel([
                'group_id' => $id_info[1]
            ],$user);
        }

        //查询出二维码是否有推送,如果有则推送无则默认推送
        $qrcode_info = $qrcode->find($id_info[0]);
        if ($qrcode_info['push']){
            return $qrcode_info['push'];
        }
        return '关于进入诺德传动';
    }

    /**
     * 未关注扫带参数二维码进入,分配标签id与二维码id,并默认注册
     */
    public function EventKeyRegister($user,$message)
    {
        $membergroup = new MemberGroup();
        $qrcode = new qrcode();
        $qrcode_data = $message['EventKey'];
        $id_info = explode('_',$qrcode_data);

        //注册
        $user['data_qrcode_id'] = $id_info[0];
        $data = $this->member->inster($user);

        //关联标签二维码
        $result = $membergroup->addMemberActivity([
            'data_qrcode_id' => $id_info[0],
            'group_id' => $id_info[1],
            'member_id' => $data['id']
        ]);
        if ($result['code'] == 1){//关系一级标签
            $this->setMemberLabel([
                'group_id' => $id_info[1]
            ],$user);
        }

        //查询出二维码是否有推送,如果有则推送无则默认推送
        $qrcode_info = $qrcode->find($id_info[0]);
        if ($qrcode_info['push']){
            return $qrcode_info['push'];
        }
        return '欢迎关注诺德传动';
    }

    /**
     * 先查询出一级标签,归类到一级标签中
     */
    public function setMemberLabel($param,$user)
    {
        $group = new Grouping();
        //根据得到的二级标签id获取一级标签
        $oneLable = $group->getOneTwoLabel($param['group_id']);

        //获取用户数据
        $lable = new LabelController();
        $lable->addLabelOne([$user['openid']],$oneLable['wx_id']);
    }

    /**
     * 自动回复消息
     */
    public function autoMessage($param)
    {
        $auto = new AutoReply();
        $info = $auto->where('key',$param)->first();
        return $info['content'];
    }

}
