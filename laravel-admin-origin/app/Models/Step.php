<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    //
    public static function GetKeyVall()
    {
        $types= Type::all()->toArray();
        $type_names = array_column($types, 'title','id');
        return $type_names;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
