<?php

namespace App\Admin\Actions\Organization;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class People extends RowAction
{
    public $name = '机构人员';


    public function href()
    {
        return "/admin/organization-peoples?organization_id=".$this->getKey();
    }

}
