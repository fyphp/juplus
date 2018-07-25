<?php

namespace App\Http\Controllers\Api;

use App\Model\contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;

class ContactController extends Controller
{
    protected $contactModel;

    public function __construct()
    {
        $this->contactModel = new contact();
    }



    /**
     * 新增联系我们
     */
    public function addContact(Request $request)
    {
        $param = $request->input();
        $info = $this->contactModel->add([
            'name' => $param['name'],
            'tel' => $param['tel'],
            'company' => $param['company'],
            'position' => $param['position'],
            'email' => $param['email'],
            'content' => $param['content'],
            'type' => $param['type']
        ]);
        if ($info['code'] == 1){//插入成功后进行邮件发送
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->CharSet = 'utf8';
            $mail->Host = 'smtp.163.com';
            $mail->SMTPAuth = true;
            $mail->Username = '18958636789@163.com';
            $mail->Password = 'TZ3915506';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 994;
            $mail->From = '18958636789@163.com';
            $mail->FromName  = $param['name'];
            $mail->setFrom('18958636789@163.com',$param['name'],true);
            $mail->addAddress('info@juplus.cn',$param['name']);
            $mail->IsHTML(true);
            $mail->Subject = '微信联系我们留言';
            $mail->Body = nl2br("姓名:".$param['name'].PHP_EOL.'电话:'.$param['tel'].PHP_EOL.'邮箱:'.$param['email'].PHP_EOL.'内容:'.$param['content']);
            $mail->send();
        }
        return $info;
    }
}
