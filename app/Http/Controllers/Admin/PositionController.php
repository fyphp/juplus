<?php

namespace App\Http\Controllers\Admin;

use App\Model\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    protected $positon;

    public function __construct()
    {
        $this->positon = new Position();
    }

    /**
     * 表格
     */
    public function positionList(Request $request)
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
        $member_info = $this->positon->pageMember($where,$page,$pagenum);
        //获取总数量
        $count = $this->positon->total($where);
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
     * 新增公司
     */
    public function positionAdd(Request $request)
    {
        $param = $request->input();
        $result = $this->positon->add($param);
        return $result;
    }

    /**
     * 删除公司
     */
    public function delPosition(Request $request)
    {
        $param = $request->input();
        $result = $this->positon->del($param);
        return $result;
    }

}
