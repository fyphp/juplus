<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Wechat\LabelController;
use App\Model\Activity;
use App\Model\ActivityMember;
use App\Model\Grouping;
use App\Model\Member;
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
                $data = $membergroup->addMemberActivity([
                    'member_id' => $param['member_id'],
                    'group_id' => $label['id']
                ]);
                if ($data['code'] == 1){
                    $this->setMemberLabel([
                        'member_id' => $param['member_id'],
                        'wx_id' => $label['wx_id']
                    ]);
                }
            }
            return response()->json($result);
        }
    }

    /**
     * 在微信中归类到一级标签中
     */
    public function setMemberLabel($param)
    {
        //获取用户数据
        $member = new Member();
        $member_info = $member->find($param['member_id']);
        $lable = new LabelController();
        $lable->addLabelOne([$member_info['openid']],$param['wx_id']);
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
