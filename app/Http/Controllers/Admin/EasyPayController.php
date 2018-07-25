<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EasyPayController extends Controller
{

    /**
     * 发送红包
     */
    public function send()
    {
        $Redpack = new \Redpack_pub();
        //商户订单号
        $Redpack->setParameter('mch_billno', uniqid());
        //提供方名称
        $Redpack->setParameter('nick_name', "格兰富");
        //商户名称
        $Redpack->setParameter('send_name', "格兰富");
        //用户openid
        $Redpack->setParameter('re_openid', 'oXpMdv-XOjoPdgV_59_J5UgV_8y0');
        //付款金额  每个红包的金额必须在1-200元之间
        $Redpack->setParameter('total_amount', 1*100);
        //最小红包金额
        $Redpack->setParameter('min_value', 1*100);
        //最大红包金额
        $Redpack->setParameter('max_value', 1*100);
        //红包发放总人数
        $Redpack->setParameter('total_num', 1);
		//场景id发放红包使用场景，红包金额大于200时必传PRODUCT_1:商品促销PRODUCT_2:抽奖PRODUCT_3:虚拟物品兑奖 PRODUCT_4:企业内部福利PRODUCT_5:渠道分润PRODUCT_6:保险回馈PRODUCT_7:彩票派奖PRODUCT_8:税务刮奖
		//$Redpack->setParameter('scene_id', "PRODUCT_3");
        //红包祝福语
        $Redpack->setParameter('wishing', "恭喜你获得".number_format(1,2)."元现金红包。");
        //活动名称
        $Redpack->setParameter('act_name', "格兰富活动");
        //备注
        $Redpack->setParameter('remark', "test");
        //以下是非必填项目
        //子商户号
//       $Redpack->setParameter('sub_mch_id', $parameterValue);
//      //商户logo的url
//      $Redpack->setParameter('logo_imgurl', $parameterValue);
//      //分享文案
//      $Redpack->setParameter('share_content', $parameterValue);
//      //分享链接
//      $Redpack->setParameter('share_url', $parameterValue);
//      //分享的图片
//      $Redpack->setParameter('share_imgurl', $parameterValue);
		return $Redpack->sendRedpack();
    }

}
