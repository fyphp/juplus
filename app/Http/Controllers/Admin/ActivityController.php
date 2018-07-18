<?php

namespace App\Http\Controllers\Admin;

use App\Model\Activity;
use App\Model\ActivityMember;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    protected $activity;
    protected $am;

    public function __construct()
    {
        $this->activity = new Activity();
        $this->am = new ActivityMember();
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

        return ['total'=>$count,'data'=>$data['info']];
    }

    /**
     * 新增编辑活动
     */
    public function activityAdd(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            if ($param['id']){

                $result = $this->activity->edit($param);
            }else{
                $result = $this->activity->add($param);
                if ($result['code'] == 1){
                    $filename = '/upload/'.date('Y-m-d-H-i-s') . '-' . uniqid() . '.svg';
                    $dir = public_path().$filename;
                    \SimpleSoftwareIO\QrCode\Facades\QrCode::size(1000)->generate('https://juplus.cn/oauth?url=activitysign.html&activity='.$request['data'],$dir);
                    $this->activity->signImg(['id'=>$result['data'],'signimg'=>$filename]);
                }
            }
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
    /**
     * 获取活动报名
     */
    public function activityMember(Request $request)
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
        $member_info = $this->am->pageMember($where,$page,$pagenum,$param['activity_id']);
        //获取总数量
        $count = $this->am->total($where,$param['activity_id']);
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
