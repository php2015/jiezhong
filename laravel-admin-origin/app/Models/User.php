<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public static function GetKeyVall()
    {
        $user_arr = self::where(['is_organization' => 1])->get();
        $users = array();
        if ($user_arr) {
            $users = array_column($user_arr->toArray(), 'name', 'id');
        }
        return $users;
    }
    /**
     * 根据user_id查找user name
     * @param $user_id
     * @return $user_name
     */
    public static function findUserByName($user_id)
    {
        $user_info = self::where(['id' => $user_id])->first();
        $user_name = array();
        if ($user_info) {
            $user_name[$user_info->id] = $user_info->name;
        }

        return $user_name;
    }
}
