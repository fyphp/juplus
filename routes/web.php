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
        Route::post('member/savesession', 'MemberController@saveSession');//获取参加的活动
        Route::post('edit', 'MemberController@edit');//修改用户数据
        Route::get('member/activity', 'MemberController@getMActivity');//获取参加的活动

        Route::get('getactivity', 'ActivityController@getActivity');//获取所有的活动
        Route::post('activitymember', 'ActivityController@activityMember');//用户活动报名
        Route::post('activityshare', 'ActivityController@activityShare');//用户活动签到
        Route::get('activitydata', 'ActivityController@activityData');//获取活动详情
        Route::post('activitysign', 'ActivityController@activitySign');//活动签到

        Route::post('addcontact','ContactController@addContact');//保存联系我们,并发送邮件
    });
});

Route::group(['namespace' => 'Admin'], function(){
    Route::group(['prefix' => 'admin'], function () {
        Route::post('login', 'IndexController@login');//登入
        Route::get('getmember', 'IndexController@getMember');//根据获取用户数据
        Route::get('member/export','IndexController@getMember');//获取所有用户
        Route::get('getalllarbel','IndexController@getAllLarbel');//获取所有标签
        Route::post('upload', 'UploadController@upload');//上传图片或者文件

        Route::get('activitylist', 'ActivityController@activityList');//获取活动列表
        Route::post('activityadd', 'ActivityController@activityAdd');//新增活动
        Route::get('getactivity', 'ActivityController@getActivity');//单独获取活动
        Route::get('activitymember','ActivityController@activityMember');//获取用户报名
        Route::post('delactivity','ActivityController@delActivity');//获取用户报名

        Route::post('addone','LabelController@addOne');//创建一级标签
        Route::post('addtwo','LabelController@addTwo');//创建二级标签
        Route::post('addqrcode','LabelController@addQrcode');//创建带参数二维码
        Route::get('labelonelist','LabelController@getOneList');//获取一级标签
        Route::get('labeltowlist','LabelController@getTowList');//获取二级标签列表
        Route::get('qrcodelist','LabelController@getQrcodeList');//带参数二维码类表
        Route::post('dellabelone','LabelController@delLabelOne');//删除一级标签
        Route::post('dellabeltow','LabelController@delLabelTow');//删除二级标签
        Route::post('delqrcode','LabelController@delQrcode');//删除带参数二维码
        Route::get('getlabel','LabelController@getLabel');//联动一级标签或二级标签
        Route::post('editlabelone','LabelController@editLabelOne');//修改二级标签

        Route::post('send','MessageSendController@send');//发送文本与图片消息
        Route::get('sendlist','MessageSendController@sendList');//获取文本与图片列表
        Route::get('getalltemplate','MessageSendController@getAllTemplate');//获取全部模板信息

        Route::post('sendnormal','PaymentController@send');//发送普通红包
        Route::post('easypay','EasyPayController@send');//easywecaht发送普通红包

        Route::get('getquestionlist','QuestionController@getQuestionList');//获取问卷类表
        Route::post('addquestion','QuestionController@add');//保存问卷调查
        Route::post('getquestion','QuestionController@getQuestion');//根据id获取问卷调查
        Route::post('memberquestion','QuestionController@memberQuestion');//保存用户问卷回答信息
        Route::get('getinfo','QuestionController@getinfo');//看问题详情与回答的百分比
        Route::get('getmemberinfo','QuestionController@getMemberInfo');//获取某问卷全部用户的列表
        Route::get('answer','QuestionController@answer');//获取某问卷单个用户的回答
        Route::get('questiontext','QuestionController@questionText');//获取某问卷全部的文本回答
        Route::post('delquestion','QuestionController@delQuestion');//保存问卷调查

        Route::get('contactlist','ContactController@contactList');//获取联系我们列表

        Route::post('addauto','AtutoReplyController@add');//保存问卷调查

        Route::get('autolist','AtutoReplyController@autoList');//获取自动回复的列表
        Route::post('addauto','AtutoReplyController@addAuto');//新增自动回复
        Route::post('delauto','AtutoReplyController@delAuto');//删除自动回复

        Route::get('positionlist','PositionController@positionList');//获取公司列表
        Route::post('addposition','PositionController@positionAdd');//新增公司
        Route::post('delposition','PositionController@delPosition');//删除公司
    });
});

Route::get('/', function () {
    return view('welcome');
});
