<?php

require_once __DIR__ . '/autoload.php';
use QcloudImage\CIClient;

$appid = '1256441419';
$secretId = 'AKID7A4nKgh0xmQxJVqQ9mFBwVt8C5pNmSmy';
$secretKey = 'AA1hCZlogNBsQo160pY7WkmeQ1RjqmX8';
$bucket = 'ocr';

$client = new CIClient($appid, $secretId, $secretKey, $bucket);
$client->setTimeout(30);

//名片识别
//单个或多个图片Url
$ret = $client->namecardDetect(array('urls'=>array('https://www.juplus.cn/test/vendor/ocr/tenlentOCR/test.png')), 0);

//单个或多个图片file,
//var_dump ($client->namecardDetect(array('files'=>array('./test.png')), 0));

$ret = json_decode($ret,true);
header("content-type:text/html;charset=utf-8"); 
var_dump($ret);