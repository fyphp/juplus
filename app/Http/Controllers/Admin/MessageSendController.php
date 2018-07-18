<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Wechat\SendController;
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
     * 文本图片消息发送
     * 参数:formto,有三种情况:为空发送全部用户,数组为openid,整形为标签id
     * content:发送内容
     * img 图片路径
     */
    public function send(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            //发送文本消息
            if ($param['type'] == 1){
                $result = $this->send->sendText($param['content'],$param['formto']);
                if ($result['errcode'] == 0){
                    $data = $this->message->addText($param);
                    return response()->json($data);
                }
            }
            //发送图片
            if ($param['type'] == 3){
                //上传临时素材得到meida_id
                $media_id = $this->send->uploadImage(public_path().'/'.$param['img']);

                //在继续发送
                $result = $this->send->sendImg($media_id,$param['formto']);
                if ($result['errcode'] == 0){
                    $param['media_id'] = $media_id;
                    $data = $this->message->addText($param);
                    return response()->json($data);
                }
            }

            return  response()->json(['msg'=>'发送失败','code'=>0,'data'=>'']);

        }
    }


}
