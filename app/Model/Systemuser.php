<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Systemuser extends Model
{
    protected $table = 'xk_system_user';

    public $timestamps = false;

    /**
     * 查询是用户名密码匹配
     */
    public function isSelect($param)
    {
        $result = $this->where('username',$param['username'])->where('password',md5($param['password']))->first();

        if ($result){
            return ['msg'=>'登入成功','code'=>1,'data'=>$result];
        }else{
            return ['msg'=>'账户或密码错误','code'=>0,'data'=>''];
        }
    }
}
