<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'xk_position';
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
     * 新增公司
     */
    public function add($param)
    {
        $this->company = $param['company'];
        $this->gps = $param['gps'];
        if (isset($param['tel'])){
            $this->tel = $param['tel'];
        }
        if (isset($param['content'])){
            $this->tel = $param['content'];
        }
        $this->is_sale = $param['is_sale'];
        $this->is_agent = $param['is_agent'];
        $this->is_distributor = $param['is_distributor'];
        $this->is_repair = $param['is_repair'];
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'插入成功','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'插入失败','code'=>0,'data'=>''];
        }
    }

    /**
     * 删除
     */
    public function del($param)
    {
        $result = $this->where('id',$param['id'])->delete();
        if ($result == true){
            return ['msg'=>'插入成功','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'插入失败','code'=>0,'data'=>''];
        }
    }
}
