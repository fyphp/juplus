<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'Wechat'],function (){
    Route::any('/wechat', 'WechatController@serve');
    Route::any('/oauth', 'OAuthController@index');//授权登入
    Route::any('/getSession', 'OAuthController@getSession');

    Route::any('/getjsskd', 'LabelController@getJsSkd');//获取jssdk
});

Route::group(['namespace' => 'Api'], function(){
    Route::group(['prefix' => 'api'], function () {
        Route::post('register', 'RegisterController@index');//注册
        Route::post('sendsms', 'RegisterController@verificationCode');//发送验证码
        Route::post('qcl', 'RegisterController@Qcl');//名片识别

        Route::get('getMember', 'MemberController@getMember');//获取用户数据
        Route::post('edit', 'MemberController@edit');//修改用户数据
        Route::get('member/activity', 'MemberController@getMActivity');//获取参加的活动

        Route::get('getactivity', 'ActivityController@getActivity');//获取所有的活动
        Route::post('activitymember', 'ActivityController@activityMember');//用户活动报名
        Route::post('activityshare', 'ActivityController@activityShare');//用户活动签到
        Route::get('activitydata', 'ActivityController@activityData');//获取活动详情


    });
});

Route::group(['namespace' => 'Admin'], function(){
    Route::group(['prefix' => 'admin'], function () {
        Route::post('login', 'IndexController@login');//登入
        Route::get('getmember', 'IndexController@getMember');//根据获取用户数据
        Route::get('member/export','IndexController@getMember');
        Route::post('upload', 'UploadController@upload');//上传图片或者文件

        Route::get('activitylist', 'ActivityController@activityList');//获取活动列表
        Route::post('activityadd', 'ActivityController@activityAdd');//新增活动
        Route::get('getactivity', 'ActivityController@getActivity');//单独获取活动
        Route::get('activitymember','ActivityController@activityMember');//获取用户报名

        Route::post('addone','LabelController@addOne');//创建一级标签
        Route::post('addtwo','LabelController@addTwo');//创建二级标签
        Route::post('addqrcode','LabelController@addQrcode');//创建带参数二维码

        Route::post('send','MessageSendController@send');//发送文本与图片消息

        Route::post('sendnormal','PaymentController@send');//发送普通红包

        Route::post('');//保存问卷调查

    });
});

Route::get('/', function () {
    return view('welcome');
});
