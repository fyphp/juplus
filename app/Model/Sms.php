<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $table = 'xk_SMS';

    public $timestamps = false;

    protected function ytime()
    {
        return date('Y-m-d h:i:s',time());;
    }

    public function instrt($param)
    {
        $this->datas = $param['data'];
        $this->member_id = $param['id'];
        $this->create_time = $this->ytime();
        $this->phone = $param['tel'];
        $this->save();
    }
}
