<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'xk_questionnaire';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());;
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

    public function add($param)
    {
        $this->title = $param['title'];
        $this->creater_time = $this->ytime();
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'注册完成','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'注册失败','code'=>0,'data'=>''];
        }
    }

    /**
     * @param $id
     * 根据id来获取数据
     */
    public function getId($id)
    {
        return $this->find($id);
    }
}
