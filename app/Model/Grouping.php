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
     * 创建一级分组
     */
    public function insterOne($param)
    {
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

    /**
     * 创建二级分组
     */
    public function insterTwo($param)
    {
        $this->grouping_name = $param['name'];
        $this->grouping_content = $param['content'];
        $this->creater_time = $this->ytime();
        $this->pata_id = $param['pata_id'];
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'注册完成','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'注册失败','code'=>0,'data'=>''];
        }
    }
}
