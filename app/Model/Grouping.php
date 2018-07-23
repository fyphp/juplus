<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Grouping extends Model
{
    protected $table = 'xk_grouping';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    /**
     * 后端获取用户
     */
    public function pageMember($where,$page,$pagenum,$path_id)
    {
        return $this->where($where)->forPage($page,$pagenum)->where('pata_id',$path_id)->orderBy('id', 'desc')->get()->toArray();
    }

    /**
     * 获取用户总数
     */
    public function total($where,$path_id)
    {
        return $this->where($where)->where('pata_id',$path_id)->count();
    }

    /**
     * 创建一级分组
     */
    public function insterOne($param)
    {
        $info = $this->where('grouping_name',$param['name'])->first();
        if (!$info){
            $this->grouping_name = $param['name'];
            $this->grouping_content = $param['content'];
            $this->creater_time = $this->ytime();
            $this->wx_id = $param['tag_id'];
            $result = $this->save();
            if ($result == true){
                return ['msg'=>'注册完成','code'=>1,'data'=>''];
            }else{
                return ['msg'=>'注册失败','code'=>0,'data'=>''];
            }
        }
        return ['msg'=>'注册失败,名字不能重复','code'=>0,'data'=>''];
    }

    /**
     * 创建二级分组
     */
    public function insterTwo($param)
    {
        $info = $this->where('grouping_name',$param['name'])->first();
        if (!$info){
            $this->grouping_name = $param['name'];
            $this->grouping_content = $param['content'];
            $this->creater_time = $this->ytime();
            $this->pata_id = $param['pata_id'];
            if (isset($param['activity_id'])){
                $this->activity_id = $param['activity_id'];
            }
            $result = $this->save();
            if ($result == true){
                return ['msg'=>'注册完成','code'=>1,'data'=>''];
            }else{
                return ['msg'=>'注册失拜','code'=>0,'data'=>''];
            }
        }
        return ['msg'=>'注册失败,名字不能重复','code'=>0,'data'=>''];

    }

    /**
     * 根据名字获取一级分组
     */
    public function getNameOne($param)
    {
        return $this->where('grouping_name',$param['name'])->where('pata_id',0)->first();
    }

    /**
     * 根据活动id获取创建的关联表
     */
    public function getActivityLabel($param)
    {
        return $this->where('activity_id',$param['activity_id'])->first();
    }

}
