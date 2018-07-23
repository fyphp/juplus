<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QuestionInfo extends Model
{
    protected $table = 'xk_questionnaire_info';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    public function add($param)
    {
        $this->questionnaire_id = $param['questionnaire_id'];
        $this->type = $param['type'];
        $this->info_name = $param['info_name'];
        $this->is_select = $param['is_select'];
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'注册完成','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'注册失败','code'=>0,'data'=>''];
        }
    }

    /**
     * 获取info数据
     */
    public function getInfo($questionnaire_id)
    {
        return $this->where('questionnaire_id',$questionnaire_id)->get()->toArray();
    }

}
