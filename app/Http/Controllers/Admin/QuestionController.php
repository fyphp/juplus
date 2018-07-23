<?php

namespace App\Http\Controllers\Admin;

use App\Model\Member;
use App\Model\MemberQuestion;
use App\Model\MemberQuestionInfoType;
use App\Model\Question;
use App\Model\QuestionInfo;
use App\Model\QuestionInfoType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    protected $question;
    protected $question_info;
    protected $question_info_type;
    protected $member_question;
    protected $member_question_info_type;

    public function __construct()
    {
        $this->question = new Question();
        $this->question_info = new QuestionInfo();
        $this->question_info_type = new QuestionInfoType();
        $this->member_question = new MemberQuestion();
        $this->member_question_info_type = new MemberQuestionInfoType();
    }

    /**
     * 获取问卷类表
     */
    public function getQuestionList(Request $request)
    {
        $param = $request->input();

        $where = [];
        $pagenum = 10;//每页数量
        if (!empty($param['pagenum'])){
            $pagenum = $param['pagenum'];
        }

        $page = 1;//页码
        if (!empty($param['page'])){
            $page = $param['page'];
        }

        //获取每页数量
        $member_info = $this->question->pageMember($where,$page,$pagenum);
        //获取总数量
        $count = $this->question->total($where);
        //获取总页码数
        $countpage = ceil($count / $pagenum);

        $data['info'] = $member_info;
        $data['count'] = $count;
        $data['page'] = $page;
        $data['countpage'] = $countpage;
        $data['pagenum'] = $pagenum;

        return ['total'=>$count,'data'=>$data['info']];
    }


    /**
     * 新增问卷调查
     */
    public function add(Request $request)
    {
        $param = $request->input();

        $result = $this->question->add(['title'=>$param['title']]);

        if ($result['code'] == 1){
            foreach ($param['info'] as $k=>$v){
                $question_info = new QuestionInfo();
                $info = $question_info->add([
                    'questionnaire_id' => $result['data'],
                    'info_name' => $v['info_name'],
                    'is_select' => $v['is_select'],
                    'type' => $v['type']
                ]);
                if ($info['code'] == 1){
                    if ($v['type'] !== '3'){
                        foreach ($v['info_type'] as $key=>$val){
                            $question_info_type = new QuestionInfoType();
                            $question_info_type->add([
                                'questionnaire_info_id' => $info['data'],
                                'content' => $val['content']
                            ]);
                        }
                    }
                }
            }
        }
        return ['msg'=>'新增成功','code'=>1,'data'=>$result];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 根据id获取问卷
     */
    public function getQuestion(Request $request)
    {
        $param = $request->input();
        $question = $this->question->getId($param['id']);
        $info = $this->question_info->getInfo($question['id']);
        foreach ($info as $k=>$v){
            if($v['type'] !== 3){
                $info[$k]['info_type'] = $this->question_info_type->getType($v['id']);
            }

        }
        $data['info'] = $info;
        $data['title'] = $question['title'];
        $data['id'] = $question['id'];
        $data['creater_time'] = $question['creater_time'];

        return ['msg'=>'获取成功','code'=>1,'data'=>$data];
    }

    /**
     * 保存用户回答
     */
    public function memberQuestion(Request $request)
    {
        $param = $request->input();
        //添加主表
        $result = $this->member_question->add([
            'member_id' => $param['member_id'],
            'questionnaire_id' => $param['question_id'],
        ]);
        if ($result['code'] == 1) {
            //循环添加副表
            foreach ($param['list'] as $k => $v) {
                $data = [
                    'questionnaire_member_id' => $result['data'],
                    'questionnaire_info_id' => $v['info_id'],
                    'creater_time' => date('Y-m-d H:i:s', time()),
                    'type' => $v['type']
                ];
                $info_type_model = new MemberQuestionInfoType();
                if ($v['type'] != 3) {
                    if (is_array($v['type_id'])) {//type等于3是文本
                        foreach ($v['type_id'] as $key => $value) {
                            $info_type_model = new MemberQuestionInfoType();
                            $data['questionnaire_info_type_id'] = $value;
                            $info_type_model->add($data);
                        }

                    } else {
                        $data['questionnaire_info_type_id'] = $v['type_id'];
                        $info_type_model->add($data);
                    }

                } else {
                    $data['content'] = $v['content'];
                    $info_type_model->add($data);
                }

            }
            return $result;
        }

    }

    /**
     * 查看问题回答的百分比
     */
    public function getinfo(Request $request)
    {
        $param = $request->input();
        $zhu = $this->question->getId($param['id']);//获取主表
        $info = $this->question_info->getInfo($zhu['id']);//获取info表数据
       
        foreach ($info as $k=>$v){

            $type_info = $this->question_info_type->getType($v['id']);

            if ($v['type'] == 3){
                //查找是否有文本回答
                $is_Text = $this->member_question_info_type->getIsText([
                    'id' => $v['id'],
                ]);

                $info[$k]['is_text'] = $is_Text;
            }else{
                $info[$k]['info'] = $type_info;//添加问题

                $count_sum = 0;//添加对此问题的选择数
                foreach ($type_info as $key=>$val){
                    $sum = $this->member_question_info_type->getSum($val['id']);
                    $info[$k]['info'][$key]['type_sum'] = $sum;
                    $count_sum += $sum;
                }

                $info[$k]['count'] = $count_sum;
                foreach ($info[$k]['info'] as $ke => $va) {//计算百分比
                        if ($va['type_sum']) {
                            $info[$k]['info'][$ke]['value'] = round($va['type_sum'] / $count_sum * 100);
                        } else {
                            $info[$k]['info'][$ke]['value'] = 0;
                        }
                }
            }

        }
        return ['msg'=>'获取成功','code'=>1,'data'=>$info];
    }

    /**
     * 查看全部用户回答
     */
    public function getMemberInfo(Request $request)
    {
        $param = $request->input();

        $where = [];
        $pagenum = 10;//每页数量
        if (!empty($param['pagenum'])){
            $pagenum = $param['pagenum'];
        }

        $page = 1;//页码
        if (!empty($param['page'])){
            $page = $param['page'];
        }

        //获取每页数量
        $member_info = $this->member_question->pageMember($where,$page,$pagenum,$param['id']);
        //得到用户的名字与回答时间与头像
        $member = new Member();
        foreach ($member_info as $k=>$v){
            //先获取回答的用户id
            $member_data = $member->find($v['member_id']);
            $member_info[$k]['name'] = $member_data['name'];
            $member_info[$k]['time'] = $v['creater_time'];
            $member_info[$k]['member_id'] = $member_data['id'];
            $member_info[$k]['headimgurl'] = $member_data['headimgurl'];
        }
        //获取总数量
        $count = $this->member_question->total($where,$param['id']);
        //获取总页码数
        $countpage = ceil($count / $pagenum);

        $data['info'] = $member_info;
        $data['count'] = $count;
        $data['page'] = $page;
        $data['countpage'] = $countpage;
        $data['pagenum'] = $pagenum;

        return ['total'=>$count,'data'=>$data['info']];
    }

    /**
     * 查看问卷某个文本的所有回答
     */
    public function questionText(Request $request)
    {
        $param = $request->input();

        $where = [];
        $pagenum = 10;//每页数量
        if (!empty($param['pagenum'])){
            $pagenum = $param['pagenum'];
        }

        $page = 1;//页码
        if (!empty($param['page'])){
            $page = $param['page'];
        }

        //获取每页数量
        $member_info = $this->member_question_info_type->pageMember($where,$page,$pagenum,$param['info_id']);
        //得到用户的名字与回答时间与头像
        $member = new Member();
        foreach ($member_info as $k=>$v){
            //先获取回答的用户id
            $data = $this->member_question->find($v['questionnaire_member_id']);

            $member_data = $member->find($data['member_id']);
            $member_info[$k]['name'] = $member_data['name'];
            $member_info[$k]['time'] = $data['creater_time'];
            $member_info[$k]['member_id'] = $member_data['id'];
            $member_info[$k]['headimgurl'] = $member_data['headimgurl'];
        }
        //获取总数量
        $count = $this->member_question->total($where,$param['info_id']);
        //获取总页码数
        $countpage = ceil($count / $pagenum);

        $data['info'] = $member_info;
        $data['count'] = $count;
        $data['page'] = $page;
        $data['countpage'] = $countpage;
        $data['pagenum'] = $pagenum;

        return ['total'=>$count,'data'=>$data['info']];
    }

    /**
     * 查看单个用户的填写
     */
    public function answer(Request $request)
    {
        $param = $request->input();

        $data = $this->member_question->getMemberAnswer($param['id']);//获取用户所有的回答

        $questionnaire = $this->member_question->getInfo($param['id']);
        $questionnaire_id = $questionnaire['questionnaire_id'];//得到问卷主表id

        $quest = $this->question->getId($questionnaire_id);//获取主表数据

        $quest_info = $this->question_info->getInfo($quest['id']);//获取副表info数据

        foreach ($quest_info as $key=>$val){//组合数据
            $quest_info[$key]['type_info'] = $this->question_info_type->getType($val['id']);

            foreach ($quest_info[$key]['type_info'] as $e => $a) {
                foreach ($data as $ke => $va) {
                    if ($va['questionnaire_info_type_id'] == $a['id']) {
                        $quest_info[$key]['type_info'][$e]['is_on'] = 1;
                    }
                }
            }
        }


        foreach ($quest_info as $key => $value) {
            foreach ($data as $k => $v) {
                if ($v['questionnaire_info_id'] == $value['id'] && $v['type'] == 3) {
                    $quest_info[$key]['content'] = $v['content'];
                }
            }

        }
        return ['msg'=>'获取成功','code'=>1,'data'=>$quest_info];
    }

    /**
     * 删除问卷主表以所有关联表数据
     */
    public function delQuestion(Request $request)
    {
        $param = $request->input();
        $this->question->destroy($param['id']);
        $data = $this->question_info->getInfo($param['id']);
        $this->question_info->where('questionnaire_id',$param['id'])->delete();
        foreach ($data as $k=>$v){
            $this->question_info_type->where('questionnaire_info_id',$v['id'])->delete();
            $this->member_question_info_type->where('questionnaire_info_id',$v['id'])->delete();
        }
        $this->member_question->where('questionnaire_id',$param['id'])->delete();
        return ['msg'=>'删除成功','code'=>1,'data'=>''];
    }
}
