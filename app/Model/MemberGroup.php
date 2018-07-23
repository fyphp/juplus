<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MemberGroup extends Model
{
    protected $table = 'xk_member_group';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    /**
     * 添加用户关联的分组标签
     */
    public function addMemberActivity($param)
    {
        $this->member_id = $param['member_id'];
        $this->group_id = $param['group_id'];
        if (isset($param['data_qrcode_id'])){//只有在通过扫带参数二维码的时候才会有这个
            $this->data_qrcode_id = $param['data_qrcode_id'];
        }
        $this->creater_time = $this->ytime();
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'关联成功','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'关联失败','code'=>0,'data'=>''];
        }
    }
}
