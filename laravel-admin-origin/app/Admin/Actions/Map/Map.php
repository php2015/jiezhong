<?php

namespace App\Admin\Actions\Map;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Map extends RowAction
{
    public $name = '查看地图';


    public function href()
    {
        return "/admin/map?type_id=".$this->getKey();
    }

}
