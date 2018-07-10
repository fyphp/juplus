<?php

namespace App\Http\Controllers\Api;

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
     * 获取所有的活动
     */
    public function getActivity()
    {
        $result = $this->activity->activityAll();

        return response()->json(['msg'=>'获取成功','code'=>1,'data'=>$result]);
    }

    /**
     * 用户报名活动
     */
    public function activityMember(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            $result = $this->activity->upActivity($param);
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

}
