<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class User extends Model
{
    //
    public static function GetKeyVall()
    {
        $name_arr = User::all()->toArray();
        $names = array_column($name_arr, 'name', 'id');
        return $names;
    }

    public static function login($mobile, $login_password, $ip)
    {
        $log = new UserLogs();
        $log->ip = $ip;
        $user = self::findUserByOpenid($mobile);
        if (!$user) {
            Log::error('登录失败，手机号不存在:' . $mobile);
            return false;
        }
        $password = substr(md5($login_password), 3, 20);
        if ($password != $user->password) {
            Log::error('登录失败，密码错误:' . $mobile);
            return false;
        }

        $log->action = '登录';

        $log->user_id = $user->id;

        $token = md5($user->mobile . $user->password .time());
        Cache::forget($token);
        //Cache::add($token, [
        Cache::forever($token, [
            'user_id' => $user->id,
            'name' => $user->name,
            'head_icon' => env('APP_URL')  . $user->head_icon,
            'type_id' => $user->type_id,
            'job' => $user->job,
            'set_num' => $user->set_num,
            'description' => $user->description

        ]);

        $data['user'] = Cache::get($token, []);

        $log->data = json_encode($data);
        if (!$log->save()) {
            Log::error('记录登录日志失败');
        }
        $data['user']['token'] = $token;
        return $data['user'];

    }

    /**
     * 根据openid查找用户是否存在
     * @param $openid
     * @return mixed
     */
    public static function findUserByOpenid($mobile)
    {
        return self::where(['mobile' => $mobile])->first();
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }
}
