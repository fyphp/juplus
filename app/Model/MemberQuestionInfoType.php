<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MemberQuestionInfoType extends Model
{
    protected $table = 'xk_member_questionnaire_info_type';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());;
    }

    /**
     * 后端获取用户
     */
    public function pageMember($where,$page,$pagenum,$info_type_id)
    {
        return $this->where($where)->where('questionnaire_info_id',$info_type_id)->where('type',3)->forPage($page,$pagenum)->orderBy('id', 'desc')->get()->toArray();
    }

    /**
     * 获取用户总数
     */
    public function total($where,$info_type_id)
    {
        return $this->where($where)->where('questionnaire_info_type_id',$info_type_id)->where('type',3)->count();
    }

    /**
     * 获取属于info的type
     */
    public function getSum($id)
    {
        return count($this->where('questionnaire_info_type_id',$id)->get()->toArray());
    }

    /**
     * 添加
     */
    public function add($param)
    {
        $this->questionnaire_member_id = $param['questionnaire_member_id'];
        $this->questionnaire_info_id = $param['questionnaire_info_id'];
        $this->creater_time = $this->ytime();
        $this->type = $param['type'];
        if (isset($param['questionnaire_info_type_id'])){
            $this->questionnaire_info_type_id = $param['questionnaire_info_type_id'];
        }
        if (isset($param['content'])){
            $this->content = $param['content'];
        }
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'注册完成','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'注册失败','code'=>0,'data'=>''];
        }
    }

    /**
     * 查找是否有文本回答
     */
    public function getIsText($param)
    {
        $count = count($this->where('questionnaire_info_id',$param['id'])->where('type',3)->get()->toArray());
        return $count;
    }
}
