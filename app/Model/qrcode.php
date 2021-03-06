<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class qrcode extends Model
{
    protected $table = 'xk_data_qrcode';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    /**
     * 后端获取用户
     */
    public function pageMember($where,$page,$pagenum)
    {
        return $this->where($where)->forPage($page,$pagenum)->orderBy('id', 'desc')->get()->toArray();
    }

    /**
     * 获取用户总数
     */
    public function total($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 创建二维码
     */
    public function add($param)
    {
        $this->grouping_id = $param['grouping_id'];
        $this->name = $param['name'];
        $this->creater_time = $this->ytime();
        if (isset($param['push'])){
            $this->push = $param['push'];
        }
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'插入成功','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'插入失败','code'=>0,'data'=>''];
        }
    }

    /**
     * 根据id进行添加img路径
     */
    public function saveImg($param)
    {
        $info = $this->find($param['id']);
        $info->qrcode_img = $param['qrcode_img'];
        $result = $info->save();
        if ($result == true){
            return ['msg'=>'插入成功','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'插入失败','code'=>0,'data'=>''];
        }
    }
}
