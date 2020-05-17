<?php

namespace App\Http\Controllers\Api\V1;
require  '../app/Libs/JPush/autoload.php'; // 自动加载
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use App\Models\Step;
use App\Models\User;
use App\Models\UserLogs;
use Illuminate\Http\Request;
use App\Support\Codes;
use App\Support\Helpers;
use JPush\Client as JPush;
use Illuminate\Support\Facades\Log;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;



class StepController extends Controller
{

    /**
     * TODO login
     * @param Request $request
     * @return array
     */
    public function Login(Request $request)
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        if (empty($mobile)) return Codes::setCode(Codes::TOKEN_MOBILE, '手机号为空', '', '');
        if (empty($password)) return Codes::setCode(Codes::TOKEN_PASSWORD, '密码为空', '', '');
        if (!$res = User::login($mobile, $password, $request->getClientIp())) {
            return Codes::setCode(Codes::ERR_LOGIN_FAILS, '登录失败', array('mobile'=>$mobile), [], $request->input());
        }

        return Codes::setCode(Codes::SUCC, '', $res);
    }

    /**
     * TODO get setp
     * @param Request $request
     * @return array
     */
    public function getStep(Request $request)
    {
        $token = $request->input('token');
        $type  = $request->input('type');
        $user = Helpers::getUserIdByToken($token);
        if (empty($user)) return Codes::setCode(Codes::TOKEN_ERROR, 'token验证失败', '', '');
        $sTime = strtotime(date('Y-m-d', time()));
        $eTime = $sTime + 86400;
        $where = " u.id > 0 ";
        if($type){
            $where = " u.type_id = " . $user['type_id'] ;
        }
        $sql = "SELECT
                    u.id,
                    u.name,
                    u.head_icon,
                    t.num,
                    u.set_num,
                    ifnull(ss.user_num,'0') as user_num

                FROM
                    users AS u
                LEFT JOIN types AS t ON u.type_id = t.id
                LEFT JOIN (
                    SELECT
                        s.user_num,
                        s.user_id
                    FROM
                        steps AS s
                    WHERE
                        UNIX_TIMESTAMP(s.created_at) >= $sTime
                    AND UNIX_TIMESTAMP(s.created_at) <= $eTime
                ) AS ss ON u.id = ss.user_id
                WHERE
                    " . $where . " order by ss.user_num desc ";

        //echo $sql;die;
        $res = DB::select($sql);
        return Codes::setCode(Codes::SUCC, '', $res);
    }

    /**
     * TODO set setp
     * @param Request $request
     * @return array
     */
    public function setStep(Request $request)
    {
        $token = $request->input('token');
        $step_num = $request->input('step_num');
        if (empty($step_num)) return Codes::setCode(Codes::ERR_SET_STEP, '步数不能为空', '', '');
        $user = Helpers::getUserIdByToken($token);
        if (empty($user)) return Codes::setCode(Codes::TOKEN_ERROR, 'token验证失败', '', '');
        //get types num
        $type_num = Type::select('num')->where(['id' => $user['type_id']])->first();

        if ($type_num) {
            if ($type_num->num > $step_num) {
                return Codes::setCode(Codes::ERR_SET_STEP_NUM, '设置步数不能小于部门最小步数', '', '');
            }
            $user_info = User::where(['id' => $user['user_id']])->first();
            $before_num = $user_info->set_num;
            $user_info->set_num = $step_num;
            if (!$user_info->save()) {
                Log::error('设置目标步数失败，user_id:'.$user['user_id'].' 步数:'.$step_num);
                return Codes::setCode(Codes::ERR_QUERY, '失败');
            }
            $log = new UserLogs();
            $log->ip = $request->getClientIp();
            $log->action = 'setStep';
            $log->user_id = $user_info->id;
            $data['user_id'] = $user_info->id;
            $data['before_num'] = $before_num;
            $data['after_num'] = $step_num;
            $log->data = json_encode($data);
            $log->save();
            return Codes::setCode(Codes::SUCC, '成功');
        }
        return Codes::setCode(Codes::ERR_QUERY, '失败');
    }

    /**
     * TODO update setp
     * @param Request $request
     * @return array
     */
    public function updateStep(Request $request)
    {
        $token = $request->input('token');
        $step_num = $request->input('step_num');
        if (empty($step_num)) return Codes::setCode(Codes::ERR_SET_STEP, '步数不能为空', '', '');
        $user = Helpers::getUserIdByToken($token);
        if (empty($user)) return Codes::setCode(Codes::TOKEN_ERROR, 'token验证失败', '', '');
        $sql = "
                    SELECT
                id,user_num
            FROM
                `steps`
            WHERE
                `user_id` = ".$user['user_id']."
            AND UNIX_TIMESTAMP(created_at) >= ".strtotime(date('Y-m-d', time()))."
            LIMIT 1";
        $user_step = DB::select($sql);
        $step = new Step();
        $step->user_id = $user['user_id'];
        $step->user_num = $step_num;

        if (!$user_step) {
            //create
            $step->time = date('Y-m-d H:i:s',time());
            if(!$step->save()){
                Log::error('上报步数失败，user_id:'.$user['user_id'].' 步数:'.$step_num);
                return Codes::setCode(Codes::ERR_QUERY, '失败');
            }
            return Codes::setCode(Codes::SUCC, '成功');
        }

        $before_num = $user_step[0]->user_num;

        if(!$step->where(['id'=>$user_step[0]->id])->update(['user_num'=>$step_num])){
            Log::error('上报步数失败，user_id:'.$user['user_id'].' 步数:'.$step_num);
            return Codes::setCode(Codes::ERR_QUERY, '失败');
        }
        $log = new UserLogs();
        $log->ip = $request->getClientIp();
        $log->action = 'updateStep';
        $log->user_id = $user['user_id'];
        $data['user_id'] = $user['user_id'];
        $data['before_num'] = $before_num;
        $data['after_num'] = $step_num;
        $log->data = json_encode($data);
        $log->save();
        return Codes::setCode(Codes::SUCC, '成功');
    }

    /**
     * TODO set Locations
     * @param Request $request
     * @return array
     */
    public function setLocations(Request $request)
    {
        $token = $request->input('token');
        $longitude = $request->input('longitude');
        $latitude = $request->input('latitude');
        $site = $request->input('site');
        if (empty($longitude)) return Codes::setCode(Codes::ERR_SET_LONGITUDE, '经度不能为空', '', '');
        if (empty($latitude)) return Codes::setCode(Codes::ERR_SET_LATITUDE, '纬度不能为空', '', '');
        if (empty($site)) return Codes::setCode(Codes::ERR_SET_SITE, '位置不能为空', '', '');
        $user = Helpers::getUserIdByToken($token);
        if (empty($user)) return Codes::setCode(Codes::TOKEN_ERROR, 'token验证失败', '', '');

        $location = new Location();
        $location->user_id = $user['user_id'];
        $location->longitude = $longitude;
        $location->latitude = $latitude;
        $location->site = $site;
        $location->time = date('Y-m-d H:i:s',time());
        if(!$location->save()){
            Log::error('上报经纬度失败，user_id:'.$user['user_id'].' 经度:'.$longitude.' 纬度:'.$latitude.' 地址:'.$site);
            return Codes::setCode(Codes::ERR_QUERY, '失败');
        }
        return Codes::setCode(Codes::SUCC, '成功');
    }

    /**
     * TODO set Locations
     * @param Request $request
     * @return array
     */
    public function pushApp(Request $request)
    {
        $app_key = env('JPUSH_APP_KEY');
        $master_secret = env('JPUSH_MASTER_SECRET');
        $log = env('JPUSH_LOGS');
        $client =  new JPush($app_key, $master_secret,$log);

        $result = $client->push()
            ->setPlatform('all')
            ->addAllAudience()
            ->setNotificationAlert("建支运动提醒，点击开启工作模式")  //你要推送的信息
            ->send();

        echo json_encode($result);  //返回发送结果

    }

    /**
     * TODO set head_icon
     * @param Request $request
     * @return array
     */
    public function setHeadIcon(Request $request)
    {
        $token = $request->input('token');
        $userInfo = Helpers::getUserIdByToken($token);
        if (empty($userInfo)) return Codes::setCode(Codes::TOKEN_ERROR, 'token验证失败', '', '');
        $user = User::where(['id'=>$userInfo['user_id']])->first();
        $user_icon = Helpers::uploadFile($request->file('head_icon'), 'public', true);
        $user->head_icon = !empty($user_icon) ? $user_icon : $user->head_icon;
        $data['heade_icon'] =  $user->head_icon ;
        if ($user->save()) {
            return Codes::setCode(Codes::SUCC, '成功',$data);
        }

        return Codes::setCode(Codes::ERR_QUERY, '');
    }


    public function test(Request $request)
    {

        Excel::import(new UsersImport(), "../storage/app/public/file/users.csv");
        echo 1;die;
    }

    /**
     * TODO updateVersion
     * @param Request $request
     * @return array
     */
    public function updateVersion(Request $request)
    {
        $appid= $request->input('appid');
        $str = '';
        if($appid == 1){//android
            $str ='{"code":1,"ver":"'.env('APP_ANDROID').'","path":"'.env('APP_URL').'/apk/jianzhi.apk","logo":"'.env('APP_URL').'/apk/icon.png", "desc":"建支android"}';
        }else if($appid == 2){//ios
            $str ='{"code":1,"ver":"'.env('APP_IOS').'","path":"'.env('APP_URL').'/apk/文件名","logo":"'.env('APP_URL').'/apk/icon.png", "desc":"建支ios"}';
        }
        return $str;
    }


}
