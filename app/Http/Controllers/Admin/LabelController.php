<?php

namespace App\Http\Controllers\Admin;

use App\Model\Grouping;
use App\Model\qrcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LabelController extends Controller
{
    protected $grouping;
    protected $qrcode;

    public function __construct()
    {
        $this->grouping = new Grouping();
        $this->qrcode = new qrcode();
    }

    /**
     * 创建一级标签
     */
    public function addOne(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            $welabel = new \App\Http\Controllers\Wechat\LabelController();
            $tag_id = $welabel->addGroup($param['name']);
            $param['tag_id'] = $tag_id;

            $result = $this->grouping->insterOne($param);
            return response()->json($result);
        }
    }

    /**
     * 创建二级标签
     */
    public function addTwo(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            $result = $this->grouping->insterTwo($param);
            return response()->json($result);
        }
    }

    /**
     * 创建带参数二维码
     */
    public function addQrcode(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            //先创建数据获得id然后再创建二维码
            $result = $this->qrcode->add($param);
            if ($result['code'] == 1){
                //获取二维码
                $code = new \App\Http\Controllers\Wechat\LabelController();
                $rsult = $code->Qrcode($result['data'].'_'.$param['grouping_id']);

                $info = $this->qrcode->saveImg(['id'=>$result['data'],'qrcode_img'=>$rsult]);
                return response()->json($info);
            }
        }
    }
}
