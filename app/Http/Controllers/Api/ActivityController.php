<?php

namespace App\Http\Controllers\Api;

use App\Model\Activity;
use App\Model\ActivityMember;
use App\Model\Grouping;
use App\Model\MemberGroup;
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
     * 获取所有的活动
     */
    public function getActivity(Request $request)
    {
        $param = $request->input();
        $result = $this->activity->activityAll($param['type']);

        return response()->json(['msg'=>'获取成功','code'=>1,'data'=>$result]);
    }


    /**
     * 获取活动详情
     */
    public function activityData(Request $request)
    {
        $param = $request->input();
        $result = $this->activity->getOne($param['id']);

        return response()->json(['msg'=>'获取成功','code'=>1,'data'=>$result]);
    }

    /**
     * 用户报名活动
     */
    public function activityMember(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            $result = $this->am->upActivity($param);
            if ($result['code'] == 1){//报名成功,将用户关联到分组中
                //查找根据活动创建的标签分组
                $group = new Grouping();
                $membergroup = new MemberGroup();
                $label = $group->getActivityLabel(['activity_id'=>$result['data']]);
                $membergroup->addMemberActivity([
                    'member_id' => $param['member_id'],
                    'group_id' => $label['id']
                ]);
            }
            return response()->json($result);
        }
    }

    /**
     * 用户活动签到
     */
    public function activityShare(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();

            $result = $this->activity->share($param);
            return response()->json($result);
        }
    }

    /**
     * 活动签到成功
     */
    public function activitySign(Request $request)
    {
        $param = $request->input();
        $result = $this->am->sign([
            'member_id' => $param['member_id'],
            'activity_id' => $param['activity_id']
        ]);
        return $result;
    }

}
