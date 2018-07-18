<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController
{

//主帐号,对应开官网发者主账号下的 ACCOUNT SID
    public $accountSid = '8a48b5514b0b8727014b23cfa43610d3';
    //主帐号Token
    public $accountToken= '35b176c2430c48a69013c76dfb6ef95d';
    //应用Id
    public $appId='8aaf0708644a2efd01645f1e6b5c0af6';
//请求地址
//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
//生产环境（用户应用上线使用）：app.cloopen.com
    private $serverIP='app.cloopen.com';

//请求端口，生产环境和沙盒环境一致
    private $serverPort='8883';

//REST版本号，在官网文档REST介绍中获得。
    private $softVersion='2013-12-26';

    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id
     */
    public function sendTemplateSMS($to,$datas,$tempId)
    {
        $rest = new CcsdkController($this->serverIP,$this->serverPort,$this->softVersion);
//        return 11222;
        $rest->setAccount($this->accountSid,$this->accountToken);
        $rest->setAppId($this->appId);
        // 发送模板短信
        // echo "Sending TemplateSMS to $to <br/>";
        $result = $rest->sendTemplateSMS($to,$datas,$tempId);

        if($result == NULL ) {
            echo "result error!";

        }
        if($result->statusCode!=0) {
            // return returninfos('2','短信获取错误');
             echo "error code :" . $result->statusCode . "<br>";
             echo "error msg :" . $result->statusMsg . "<br>";
            // TODO 添加错误处理逻辑
            return 2;
        }else{
            // 获取返回信息
            // $smsmessage = $result->TemplateSMS;
//             echo "dateCreated:".$smsmessage->dateCreated."<br/>";
//             echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
            return 1;
        }
    }
}
