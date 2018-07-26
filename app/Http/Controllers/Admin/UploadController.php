<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                $file = $request->file('file');
                //判断文件是否上传
                if ($file->isValid()){
                    ini_set('default_socket_timeout', 3*60*60);  //3个小时
                    ini_set ('memory_limit', '3072M');
                    $originalName = $file->getClientOriginalName(); // 文件原名
                    $ext = $file->getClientOriginalExtension();     // 扩展名
                    $realPath = $file->getRealPath();   //临时文件的绝对路径
                    $type = $file->getClientMimeType();     // image/jpeg

                    // 上传文件
                    $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
                    // 使用我们新建的uploads本地存储空间（目录）
                    //这里的uploads是配置文件的名称
                    $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
                    if ($bool){
                        return response()->json(['msg'=>'上传成功','code'=>1,'data'=>'/upload/'.$filename]);
                    }else{
                        return response()->json(['msg'=>'上传失败','code'=>0,'data'=>'']);
                    }

                }
            }
        }catch (\Exception $e){
            return response()->json(['msg'=>'上传失败','code'=>0,'data'=>'']);
        }

    }
}
