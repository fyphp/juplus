<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    protected $table = 'xk_contact';

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
     * 添加联系我们
     */
    public function add($param)
    {
        $this->name = $param['name'];
        $this->tel = $param['tel'];
        $this->company = $param['company'];
        $this->position = $param['position'];
        $this->email = $param['email'];
        $this->content = $param['content'];
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'插入成功','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'插入失败','code'=>0,'data'=>''];
        }
    }
}
