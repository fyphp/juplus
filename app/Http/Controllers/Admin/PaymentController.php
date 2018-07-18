<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{

    public function __construct()
    {

    }

    /**
     * 发送普通红包
     */
    public function send()
    {
        $rnd_num = array('0','1','2','3','4','5','6','7','8','9','0');
        $rendstr = "";
        while (strlen($rendstr)<10){
            $rendstr.=$rnd_num[array_rand($rnd_num)];
        }
        $mac = '1508534671'.date("Ymd").$rendstr;
        $redpackData = [
            'mch_billno'   => str_random(16),
            'send_name'    => '诺德传动',
            're_openid'    => 'ogXT71Y2qDMDBPbSh3WiwjUqoqE0',
            'total_num'    => 1, //固定为1，可不传
            'total_amount' => 100,  //单位为分，不小于100
            'wishing'      => '1111',
            'client_ip'    => '',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name'     => '3333',
            'remark'       => '擦擦撒',
        ];

        $resutl = $this->payment()->sendNormal($redpackData);
        dd($resutl);
    }
}


