<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'xk_message';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    /**
     * 添加文本消息
     */
    public function addText($param)
    {
        $this->group_id = $param['grouping_id'];

        if ($param['type'] == 1){
            $this->content = $param['content'];
        }
        if ($param['type'] == 2){
            $this->template_content = $param['template_content'];
            $this->template_id = $param['template_id'];
        }
        if ($param['type'] == 3){
            $this->media = $param['media_id'];
        }

        $this->creater_time = $this->ytime();
        $this->type = $param['type'];
        $resilt = $this->save();
        if ($resilt == true){
            return ['msg'=>'添加成功','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'添加失败','code'=>0,'data'=>''];
        }
    }
}
