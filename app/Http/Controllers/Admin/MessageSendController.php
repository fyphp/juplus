<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Wechat\SendController;
use App\Model\Grouping;
use App\Model\Member;
use App\Model\MemberGroup;
use App\Model\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageSendController extends Controller
{
    protected $message;
    protected $send;

    public function __construct()
    {
        $this->message = new Message();
        $this->send = new SendController();
    }

    /**
     * 获取表格
     */
    public function sendList(Request $request)
    {
        $group = new Grouping();
        $param = $request->input();

        $where = [];
        $pagenum = 10;//每页数量
        if (!empty($param['pagenum'])){
            $pagenum = $param['pagenum'];
        }

        $page = 1;//页码
        if (!empty($param['page'])){
            $page = $param['page'];
        }

        //获取每页数量
        $member_info = $this->message->pageMember($where,$page,$pagenum);
        foreach ($member_info as $k=>$v){
            $group_info = $group->find($v['group_id']);
            $member_info[$k]['group'] = $group_info['grouping_name'];
        }
        //获取总数量
        $count = $this->message->total($where);
        //获取总页码数
        $countpage = ceil($count / $pagenum);

        $data['info'] = $member_info;
        $data['count'] = $count;
        $data['page'] = $page;
        $data['countpage'] = $countpage;
        $data['pagenum'] = $pagenum;

        return ['total'=>$count,'data'=>$data['info']];
    }

    /**
     * 文本图片消息发送
     * 参数:formto,有三种情况:为空发送全部用户,数组为openid,整形为标签id
     * content:发送内容
     * img 图片路径
     */
    public function send(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            //查询出此标签下的所有用户,的openid
            $openid = $this->allMember($param['grouping_id']);

            //发送文本消息
            if ($param['type'] == 1){
//                dd($openid);
                $result = $this->send->sendText($param['content'],$openid);
                if ($result['errcode'] == 0){
                    $data = $this->message->addText($param);
                    return $data;
                }
            }
            if ($param['type'] == 2){//发送模板消息
                $this->send->sendTemplate($param,$openid);
                //就行拼接发送内容
                $template_content = implode('-',$param['data']);
                $template_content = '头部:'.$param['first'].',内容:'.$template_content.',尾部:'.$param['remark'];
                $param['template_content'] = $template_content;
                $data = $this->message->addText($param);
                return $data;
            }
            //发送图片
            if ($param['type'] == 3){
                //上传临时素材得到meida_id
                $media_id = $this->send->uploadImage(public_path().'/'.$param['img']);

                //在继续发送
                $result = $this->send->sendImg($media_id,$openid);
                if ($result['errcode'] == 0){
                    $param['media_id'] = $media_id;
                    $data = $this->message->addText($param);
                    return $data;
                }
            }

            return  ['msg'=>'发送失败','code'=>0,'data'=>''];

        }
    }

    /**
     * 查询出标签下的所有用户
     */
    public function allMember($grouping_id)
    {
        //获取此分组下的所有id
        $group = new MemberGroup();
        $member = new Member();
        $data = $group->where('group_id',$grouping_id)->get()->toArray();
        //查询出所有用户的openid
        $openid = [];
        foreach ($data as $k=>$v){
            $member_info = $member->select('openid')->find($v['member_id']);
            $openid[$k] = $member_info['openid'];
        }
        return $openid;
    }

    /**
     * 获取所有模板
     */
    public function getAllTemplate()
    {
        $data = $this->send->getTemplate();
        return ['msg'=>'获取成功','code'=>0,'data'=>$data];
    }


}
