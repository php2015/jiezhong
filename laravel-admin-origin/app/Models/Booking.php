<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    const NO = 0; //否
    const YES = 1; //是
    public static function IsYes($is_yes = 0)
    {
        $map = [
            self::NO => '否',
            self::YES => '是',
        ];
        if (!empty($is_yes)) {
            return $map[$is_yes] ?? '';
        }

        return $map;
    }
}
