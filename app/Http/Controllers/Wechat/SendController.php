<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SendController extends Controller
{
    protected $app;

    public function __construct()
    {
        $this->app = $this->officialAccount();
    }

    /**
     * 文本消息发送
     * 当 $to 为整型时为标签 id
     * 当 $to 为数组时为用户的 openid 列表（至少两个用户的 openid）
     * 当 $to 为 null 时表示全部用户
     */
    public function sendText($content,$to=[])
    {
        $result = $this->app->broadcasting->sendText($content,$to);
        return $result;
    }

    /**
     * 图片消息发送
     * 当 $to 为整型时为标签 id
     * 当 $to 为数组时为用户的 openid 列表（至少两个用户的 openid）
     * 当 $to 为 null 时表示全部用户
     */
    public function sendImg($media_id,$to=[])
    {
        $result = $this->app->broadcasting->sendImage($media_id,$to);
        return $result;
    }


    /**
     * 上传临时素材
     *  图片（image）: 2M，支持 JPG 格式
        语音（voice）：2M，播放长度不超过 60s，支持 AMR\MP3 格式
        视频（video）：10MB，支持 MP4 格式
        缩略图（thumb）：64KB，支持 JPG 格式
     */
    public function uploadImage($path)
    {
       $result = $this->app->media->uploadImage($path);
       return $result['media_id'];
    }

    /**
     * 获取所有消息模板
     */
    public function getTemplate ()
    {
        return $this->app->template_message->getPrivateTemplates();
    }

    /**
     * 发送模板消息
     */
    public function sendTemplate($param,$openid)
    {
        foreach ($openid as $k=>$v){
            $this->app->template_message->send([
                'touser' => $v,
                'template_id' => $param['template_id'],
                'url' => $param['url'],
                'data' => $param['data']
            ]);
        }

    }

}
