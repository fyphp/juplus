<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'xk_activity';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    /**
     * 后端获取列表
     */
    public function pageMember($where,$page,$pagenum)
    {
        return $this->where($where)->forPage($page,$pagenum)->orderBy('id', 'desc')->get();
    }

    /**
     * 获取活动总数
     */
    public function total($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 新建活动
     */
    public function add($param)
    {
        $this->title = $param['title'];
        $this->time = $param['time'];
        $this->img = $param['img'];
        $this->city = $param['city'];
        $this->content = $param['content'];
        $this->is_open = $param['is_open'];
        $this->is_gps = $param['is_gps'];
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'插入成功','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'插入失败','code'=>0,'data'=>''];
        }

    }

    /**
     * 获取单个活动
     */
    public function getOne($id)
    {
        return $this->find($id);
    }

    /**
     * 修改活动
     */
    public function edit($param)
    {
        $info = $this->find($param['id']);
        $info->title = $param['title'];
        $info->time = $param['time'];
        $info->img = $param['img'];
        $info->city = $param['city'];
        $info->content = $param['content'];
        $info->is_open = $param['is_open'];
        $info->is_gps = $param['is_gps'];
        $result = $info->save();
        if ($result == true){
            return ['msg'=>'修改成功','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'修改失败','code'=>0,'data'=>''];
        }
    }

    /**
     * 获取所有的活动
     */
    public function activityAll()
    {
        return $this->where('status',1)->all();
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

    /**
     * 用户报名签到
     */
    public function share($param)
    {
        $info = $this->where('activity_id',$param['activity_id'])->where('member_id',$param['member_id'])->first();
        if ($info){
            $info->status = 2;
            $info->sign_time = $this->ytime();
            $result = $info->save();
            if ($result == true){
                return ['msg'=>'签到成功','code'=>1,'data'=>''];
            }else{
                return ['msg'=>'签到失败','code'=>0,'data'=>''];
            }
        }
    }

}
