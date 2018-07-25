<?php

namespace App\Http\Controllers\Admin;

use App\Model\contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    protected $contactModel;

    public function __construct()
    {
        $this->contactModel = new contact();
    }
    /**
     * 获取表格
     */
    public function contactList(Request $request)
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
        $member_info = $this->contactModel->pageMember($where,$page,$pagenum);
        //获取总数量
        $count = $this->contactModel->total($where);
        //获取总页码数
        $countpage = ceil($count / $pagenum);

        $data['info'] = $member_info;
        $data['count'] = $count;
        $data['page'] = $page;
        $data['countpage'] = $countpage;
        $data['pagenum'] = $pagenum;

        return ['total'=>$count,'data'=>$data['info']];
    }
}
