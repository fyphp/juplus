<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MemberQuestion extends Model
{
    protected $table = 'xk_member_questionnaire';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    /**
     * 后端获取用户
     */
    public function pageMember($where,$page,$pagenum,$questionnaire_id)
    {
        return $this->where($where)->forPage($page,$pagenum)->where('questionnaire_id',$questionnaire_id)->orderBy('id', 'desc')->get()->toArray();
    }

    /**
     * 获取用户总数
     */
    public function total($where,$questionnaire_id)
    {
        return $this->where($where)->where('questionnaire_id',$questionnaire_id)->count();
    }

    /**
     * 一对多获取用户回答的问关联到member_info_type表
     */
    public function comments()
    {
        return $this->hasMany('App\MemberQuestionInfoType','questionnaire_member_id','id');
    }


    /**
     * 查询用户的回答与问题
     */
    public function getMemberAnswer($id)
    {
        return $this->comments()->find($id)->toArray();
    }

    /**
     * 获取数据
     */
    public function getInfo($id)
    {
        return $this->find($id);
    }

    /**
     * 保存数据
     */
    public function add($param)
    {
        $this->member_id = $param['member_id'];
        $this->questionnaire_id = $param['questionnaire_id'];
        $this->creater_time= $this->ytime();
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'注册完成','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'注册失败','code'=>0,'data'=>''];
        }
    }
}
