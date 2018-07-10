<?php

namespace App\Http\Controllers\Admin;

use App\Model\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    protected $activity;

    public function __construct()
    {
        $this->activity = new Activity();
    }

    /**
     * 获取活动表格
     */
    public function activityList(Request $request)
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
        $member_info = $this->activity->pageMember($where,$page,$pagenum);
        //获取总数量
        $count = $this->activity->total($where);
        //获取总页码数
        $countpage = ceil($count / $pagenum);

        $data['info'] = $member_info;
        $data['count'] = $count;
        $data['page'] = $page;
        $data['countpage'] = $countpage;
        $data['pagenum'] = $pagenum;

        return response()->json(['msg'=>'获取成功','code'=>1,'data'=>$data]);
    }

    /**
     * 新增活动
     */
    public function activityAdd(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            $result = $this->activity->add($param);
            return response()->json($result);
        }
    }

    /**
     * 获取活动详情
     */
    public function getActivity(Request $request)
    {
        $param = $request->input();
        $result = $this->activity->getOne($param['id']);

        return response()->json(['msg'=>'获取成功','code'=>1,'data'=>$result]);
    }

    /**
     * 修改活动
     */
    public function activityEdit(Request $request)
    {
        $param = $request->input();

        $request = $this->activity->edit($param);

        return response()->json($request);

    }
}
