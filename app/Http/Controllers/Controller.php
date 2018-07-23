<?php

namespace App\Http\Controllers;

use EasyWeChat\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function officialAccount()
    {
        $options =[
            /**
             * 是否开启调试
             */
            'debug'  => true,
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => 'wxe58eeb9048a331f8', // AppID
            'secret'  => 'd78f3b359bcd13d739b30839ab7be739',// AppSecret
            'token'   => '123456', // Token
            'aes_key' => '7AeYbZHIT4vMNpilmI15elP12NDeUY0TUgcLs0esPW2'//密文
        ];

        $app = Factory::officialAccount($options);
        return $app;
    }

    public function payment()
    {
        $options =[
            /**
             * 是否开启调试
             */
            'debug'  => true,
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => 'wxe58eeb9048a331f8', // AppID
            'secret'  => 'd78f3b359bcd13d739b30839ab7be739',// AppSecret
            'token'   => '123456', // Token
            'aes_key' => '7AeYbZHIT4vMNpilmI15elP12NDeUY0TUgcLs0esPW2'//密文
        ];
        Factory::officialAccount($options);
        $config = [
            'app_id'    => 'wxe58eeb9048a331f8',
            'mch_id'    => '1508534671',
            'key'       => 'nord@Drivesystem2shixiaoxia1234AB',
            'cert_path' => '/www/html/jue/nord/apiclient_cert.pem',
            'key_path'  => '/www/html/jue/nord/apiclient_key.pem',
        ];

        $payment = Factory::payment($config);

        return $payment->redpack;
    }
}
