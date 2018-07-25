<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AutoReply extends Model
{
    protected $table = 'xk_auto_reply';
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
        return $this->where($where)->forPage($page,$pagenum)->orderBy('id', 'desc')->get()->toArray();
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
        $this->key = $param['key'];
        $this->content = $param['content'];
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'插入成功','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'插入失败','code'=>0,'data'=>''];
        }

    }

}
