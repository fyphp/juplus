<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QuestionInfoType extends Model
{
    protected $table = 'xk_questionnaire_info_type';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());;
    }

    public function add($param)
    {
        $this->questionnaire_info_id = $param['questionnaire_info_id'];
        $this->content = $param['content'];
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'注册完成','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'注册失败','code'=>0,'data'=>''];
        }
    }

    /**
     * 获取数据
     */
    public function getType($questionnaire_info_id)
    {
        return $this->where('questionnaire_info_id',$questionnaire_info_id)->get()->toArray();
    }
}
