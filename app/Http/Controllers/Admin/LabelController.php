<?php

namespace App\Http\Controllers\Admin;

use App\Model\Grouping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LabelController extends Controller
{
    protected $grouping;

    public function __construct()
    {
        $this->grouping = new Grouping();
    }

    /**
     * 创建一级标签
     */
    public function addOne(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            $welabel = new \App\Http\Controllers\Wechat\LabelController();
            $tag_id = $welabel->addGroup($param['name']);
            $param['tag_id'] = $tag_id;

            $result = $this->grouping->insterOne($param);
            return response()->json($result);
        }
    }

    /**
     * 创建二级标签
     */
    public function addTwo(Request $request)
    {
        if ($request->isMethod('post')){
            $param = $request->input();
            $result = $this->grouping->insterTwo($param);
            return response()->json($result);
        }
    }
}
