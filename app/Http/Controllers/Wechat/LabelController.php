<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LabelController extends Controller
{
    protected $app;

    public function __construct()
    {
        $this->app = $this->officialAccount();
    }

    /**
     * 创建标签
     */
    public function addGroup($name)
    {
        $app = $this->officialAccount();
        $result = $app->user_tag->create($name);

        return $result['tag']['id'];
    }

    /**
     * 删除标签
     */
    public function deleteGroup($tagId)
    {
        $app = $this->officialAccount();
        $result = $app->user_tag->delete($tagId);

        return $result;
    }

    /**
     * 获取微信jssdk
     */
    public function getJsSkd()
    {
        $jssdk = $this->app->jssdk->buildConfig(['onMenuShareTimeline','onMenuShareAppMessage','chooseImage','previewImage','uploadImage','downloadImage'], $debug = false, $beta = false, $json = false);
        return response()->json(['msg'=>'获取成功','code'=>1,'data'=>$jssdk]);
    }

    /**
     * 创建微信带参数二维码
     */
    public function Qrcode($data)
    {
        //data: 1_2  1为带参数二维码id,2为标签id
        $result = $this->app->qrcode->forever('1_2');

        $url = $this->app->qrcode->url($result['ticket']);//获取网址内容
        $content = file_get_contents($url);//二进制图片
        $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.jpg';
        $dir = public_path().'/upload/'.$filename;
        file_put_contents($dir,$content);
        return '/upload/'.$filename;
    }

}
