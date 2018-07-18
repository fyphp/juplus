<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ActivityMember extends Model
{
    protected $table = 'xk_activity_member';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    /**
     * 后端获取列表
     */
    public function pageMember($where,$page,$pagenum,$activity_id)
    {
        return $this->where($where)->where('activity_id',$activity_id)->forPage($page,$pagenum)->orderBy('id', 'desc')->get();
    }

    /**
     * 获取活动总数
     */
    public function total($where,$activity_id)
    {
        return $this->where($where)->where('activity_id',$activity_id)->count();
    }

    /**
     * 定义关联到活动表
     */
    public function hasActivity()
    {
        return $this->hasOne('App\Model\Phone','id','activity_id');
    }

    /**
     * 获取用户的所有活动
     */
    public function getMA($param)
    {
        return $this->hasActivity()->where('member_id',$param['member_id'])->where('status',$param['status'])->get();
    }

    /**
     * 用户报名活动
     */
    public function upActivity($param)
    {
        $this->activity_id = $param['activity_id'];
        $this->member_id = $param['member_id'];
        $this->time = $this->ytime();
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'报名成功','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'报名失败','code'=>0,'data'=>''];
        }
    }

}
