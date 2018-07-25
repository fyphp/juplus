<?php

namespace App\Http\Controllers\Admin;

use App\Model\AutoReply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AtutoReplyController extends Controller
{
    protected $auto;

    public function __construct()
    {
        $this->auto = new AutoReply();
    }

    /**
     * 获取活动表格
     */
    public function autoList(Request $request)
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
        $member_info = $this->auto->pageMember($where,$page,$pagenum);
        //获取总数量
        $count = $this->auto->total($where);
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
     * 新增自动回复
     */
    public function addAuto(Request $request)
    {
        $param = $request->input();
        $result = $this->auto->add($param);
        return $result;
    }

    /**
     * 删除自动回复
     */
    public function delAuto(Request $request)
    {
        $param = $request->input();
        $this->auto->where('id',$param['id'])->delete();
        return ['msg'=>'删除成功','code'=>1,'data'=>''];
    }


}
