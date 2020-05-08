<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    //
    public static function GetKeyVall()
    {
        $title_arr = Organization::all();
        $titles = array();
        if ($title_arr) {
            $titles = array_column($title_arr->toArray(), 'title', 'id');
        }
        return $titles;

    }
}
