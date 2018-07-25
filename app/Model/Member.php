<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    protected $table = 'xk_member';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());
    }

    /**
     * 一对多关联用户标签表
     */
    public function groupMember()
    {
        return $this->hasMany('App\Model\MemberGroup','group_id','member_id');
    }

    /**
     * 后端获取用户
     */
    public function pageMember($where,$page,$pagenum)
    {
        if (isset($where['grouping_id'])){
            $grouping_id = $where['grouping_id'];
            unset($where['grouping_id']);
            return DB::table('xk_member')
                ->join('xk_member_group','xk_member.id','=','xk_member_group.member_id')
                ->where($where)
                ->where('xk_member_group.group_id','=',$grouping_id)
                ->select('xk_member.*')
                ->forPage($page,$pagenum)->orderBy('xk_member.id', 'desc')->get()->toArray();
//            return $this::has('App\Model\MemberGroup',function ($query) use ($grouping_id){
//                $query->where('group_id',$grouping_id);
//            })->where($where)->forPage($page,$pagenum)->orderBy('id', 'desc')->get()->toArray();
        }else{
            return $this->where($where)->forPage($page,$pagenum)->orderBy('id', 'desc')->get()->toArray();
        }

    }

    /**
     * 获取用户总数
     */
    public function total($where)
    {

        if (isset($where['grouping_id'])){
            $grouping_id = $where['grouping_id'];
            unset($where['grouping_id']);
            return DB::table('xk_member')
                ->join('xk_member_group','xk_member.id','=','xk_member_group.member_id')
                ->where($where)
                ->where('xk_member_group.group_id','=',$grouping_id)
                ->select('xk_member.*')
               ->count();
        }else{
            return $this->where($where)->count();
        }
    }

    /**
     * 注册基本信息
     */
    public function inster($param)
    {
        $this->openid = $param['openid'];
        $this->nickname = $param['nickname'];
        $this->sex = $param['sex'];
        $this->create_time = $this->ytime();
        $this->city = $param['city'];
        $this->province = $param['province'];
        $this->country = $param['country'];
        $this->headimgurl = $param['headimgurl'];
        $result = $this->save();
        if ($result == true){
            return ['msg'=>'插入成功','code'=>1,'data'=>$this->id];
        }else{
            return ['msg'=>'插入失败','code'=>0,'data'=>''];
        }
    }

    /**
     * 估计openid获取用户数据
     */
    public function getOpenidMember($openid)
    {
        return $this->where('openid',$openid)->first();
    }

    /**
     * 更改用户关注状态
     */
    public function cancel($openid)
    {
        $info = $this->where('openid',$openid)->first();
        if ($info['is_cancel'] == 2){
            $info->is_cancel = 1;
            $result = $info->save();
        }else{
            $info->is_cancel = 2;
            $result = $info->save();
        }
        if ($result == true){
            return ['msg'=>'修改完成','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'修改失败','code'=>0,'data'=>''];;
        }
    }

    /**
     * 用户自己注册完整信息
     */
    public function edit($param)
    {
        $info = $this->find($param['id']);
        $info->name = $param['name'];
        $info->tel = $param['tel'];
        if (isset($param['company'])){
            $info->company = $param['company'];
        }
        if (isset($param['position'])){
            $info->position = $param['position'];
        }
        if (isset($param['email'])){
            $info->email = $param['email'];
        }
        if (isset($param['home'])){
            $info->home = $param['home'];
        }
        $result = $info->save();
        if ($result == true){
            return ['msg'=>'注册完成','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'注册失败','code'=>0,'data'=>''];
        }
    }

    /**
     * 修改用户信息
     */
    public function memberEdit($param)
    {
        $info = $this->find($param['id']);
        $info->name = $param['name'];
        $info->tel = $param['tel'];
        if (isset($param['company'])){
            $info->company = $param['company'];
        }
        if (isset($param['position'])){
            $info->position = $param['position'];
        }
        if (isset($param['email'])){
            $info->email = $param['email'];
        }
        if (isset($param['home'])){
            $info->home = $param['home'];
        }
        $result = $info->save();
        if ($result == true){
            return ['msg'=>'修改完成','code'=>1,'data'=>''];
        }else{
            return ['msg'=>'修改失败','code'=>0,'data'=>''];
        }
    }


}
