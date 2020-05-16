<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    public static function GetKeyVall()
    {
        $types= Type::all()->toArray();
        $type_names = array_column($types, 'title','id');
        return $type_names;
    }



}
