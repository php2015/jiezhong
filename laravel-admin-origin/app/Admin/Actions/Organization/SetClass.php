<?php

namespace App\Admin\Actions\Organization;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class SetClass extends RowAction
{
    public $name = '机构排班';

    public function href()
    {
        return "/admin/set-classes?organization_id=".$this->getKey();
    }


}
