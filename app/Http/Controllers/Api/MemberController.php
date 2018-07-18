<?php

namespace App\Http\Controllers\Api;

use App\Model\Activity;
use App\Model\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    private $member;
    private $activity;

    function __construct()
    {
        $this->member = new Member();
        $this->activity = new Activity();
    }

    /**
     * 获取用户数据
     */
    public function getMember(Request $request)
    {
        try{
            $param = $request->input();

            $info = $this->member->find($param['id']);

            return response()->json(['code'=>1,'msg'=>'获取成功','data'=>$info]);
        }catch (\Exception $e){

            return response()->json(['code'=>0,'msg'=>'获取失败','data'=>'']);
        }
    }

    /**
     * 修改用户信息
     */
    public function edit(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                $param = $request->input();
                $member = $this->member;

                $result = $member->memberEdit($param);
                return response()->json($result);
            }
        }catch (\Exception $e){
            return response()->json(['code'=>0,'msg'=>'修改失败','data'=>'']);
        }
    }

    /**
     * 获取参加过的活动
     */
    public function getMActivity(Request $request)
    {
        try{
                $param = $request->input();
                $result = $this->activity->getMA($param);
                return response()->json($result);
        }catch (\Exception $e){
            return response()->json(['code'=>0,'msg'=>'获取失败','data'=>'']);
        }
    }
}
