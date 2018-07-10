<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LabelController extends Controller
{
    /**
     * 创建标签
     */
    public function addGroup($name)
    {
        $app = $this->officialAccount();
        $result = $app->user_tag->create($name);

        return $result['tag']['id'];
    }

    /**
     * 删除标签
     */
    public function deleteGroup($tagId)
    {
        $app = $this->officialAccount();
        $result = $app->user_tag->delete($tagId);

        return $result;
    }
}
