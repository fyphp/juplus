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

    /**
     * 获取一级标签类表
     */
    public function getOneList(Request $request)
    {
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
        $member_info = $this->grouping->pageMember($where,$page,$pagenum,0);
        //获取总数量
        $count = $this->grouping->total($where,0);
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
     * 获取二级标签类表
     */
    public function getTowList(Request $request)
    {
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
        $member_info = $this->grouping->pageMember($where,$page,$pagenum,$param['id']);
        //获取总数量
        $count = $this->grouping->total($where,$param['id']);
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
     * 获取带参数二维码类表
     */
    public function getQrcodeList(Request $request)
    {
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
        $member_info = $this->qrcode->pageMember($where,$page,$pagenum);
        //获取总数量
        $count = $this->qrcode->total($where);
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
     * 删除一级标签
     */
    public function delLabelOne(Request $request)
    {
        $param = $request->input();
        $this->grouping->where('id',$param['id'])->delete();
        return ['msg'=>'删除成功','code'=>1,'data'=>''];
    }

    /**
     * 删除一级标签
     */
    public function delLabelTow(Request $request)
    {
        $param = $request->input();
        $this->grouping->where('id',$param['id'])->delete();
        return ['msg'=>'删除成功','code'=>1,'data'=>''];
    }

    /**
     * 删除带参数二维码
     */
    public function delQrcode(Request $request)
    {
        $param = $request->input();
        $data = $request->find($param['id']);
        $this->qrcode->where('id',$param['id'])->delete();
        if (file_exists(public_path().'/'.$data['qrcode_img'])){
            unlink(public_path().'/'.$data['qrcode_img']);
        }
        return ['msg'=>'删除成功','code'=>1,'data'=>''];
    }
}
