<?php

namespace App\Http\Controllers\Admin;

use App\Model\Grouping;
use App\Model\Member;
use App\Model\Systemuser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    protected $system;
    protected $member;
    protected $grouping;

    public function __construct()
    {
        $this->system = new Systemuser();
        $this->member = new Member();
        $this->grouping = new Grouping();
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

        if (!empty($param['tel'])){
            $where['tel'] = $param['tel'];
        }

//        if (!empty($param['tel'])){
//            $where['tel'] = $param['tel'];
//        }
        if (!empty($param['grouping_id'])){
            $where['grouping_id'] = $param['grouping_id'];
        }


        //获取每页数量
        $member_info = $this->member->pageMember($where,$page,$pagenum);
//        dd($member_info);
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

    /**
     * 获取所有的一级标签与二级标签
     */
    public function getAllLarbel(Request $request)
    {
        //先回去一级的
        $data = $this->grouping->where('pata_id',0)->get()->toArray();
        //根据一级标签获取到属于它的耳机标签
        foreach ($data as $k=>$v){
            $data[$k]['info'] = $this->grouping->where('pata_id',$v['id'])->get()->toArray();
        }
        return ['msg'=>'获取所有标签成功','code'=>1,'data'=>$data];
    }
}
