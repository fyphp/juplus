<?php

namespace App\Http\Controllers\Admin;

use App\Model\Member;
use App\Model\Systemuser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    protected $system;
    protected $member;

    public function __construct()
    {
        $this->system = new Systemuser();
        $this->member = new Member();
    }

    /**
     * 后端登入
     */
    public function login(Request $request)
    {
        if ($request->isMethod('post')){
            if (session('systemuser')){
               return response()->json(['code'=>1,'msg'=>'获取成功','data'=>session('systemuser')]);
            }else{
                $param = $request->input();
                $result = $this->system->isSelect($param);
                if ($result['code'] == 1){
                    session(['systemuser'=>$result['data']]);
                }
                return response()->json($result);
            }

        }
    }

    /**
     * 获取用户
     */
    public function getMember(Request $request)
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
        $member_info = $this->member->pageMember($where,$page,$pagenum);
        //获取总数量
        $count = $this->member->total($where);
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
     * 导出用户数据
     */
    public function export(Request $request)
    {


    }
}
