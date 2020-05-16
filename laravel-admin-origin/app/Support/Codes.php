<?php


namespace App\Support;


use App\Models\ApiErrorLog;
use http\Env\Request;
use Illuminate\Support\Facades\Log;

class Codes
{
    /**
     * 正常时,返回码
     */
    const SUCC = 0;

    /**
     * 100 致命错误
     */
    const TOKEN_ERROR = 101; // token验证失败
    const TOKEN_TIME = 102; // time
    const TOKEN_MOBILE = 102; // mobile
    const TOKEN_PASSWORD = 102; // password
    const ERR_LOGIN_FAILS = 103; // TOKEN_PASSWORD
    const ERR_SET_STEP = 104; // 步数不能为空
    const ERR_SET_STEP_NUM = 105; // 设置步数不能小于部门最小步数
    const ERR_SET_LONGITUDE = 106; // 经度
    const ERR_SET_LATITUDE = 107; // 纬度
    const ERR_SET_SITE = 108; // 位置

    /**
     * http 相关错误
     */
    const ERR_HTTP_UNAUTHORIZED = 401; // 登录已过期，请重新登录
    const ERR_HTTP_FORBIDDEN = 403; // 无权访问该地址
    const ERR_HTTP_NOT_FOUND = 404; // 请求地址不存在
    const ERR_INTERNAL_SERVER = 500; // 服务器内部错误
    const ERR_NO_AUTH = 401; // 没有改访问权限

    /**
     * 1000 系统级别错误
     */
    const ERR_QUERY = 1001; // 数据库操作失败
    const ERR_DB = 1002; // 数据库连接失败
    const ERR_PARAMS = 1003; // '参数验证失败： %s
    const ERR_MODEL = 1004; // 数据不存在
    const ERR_FILE_UP_LOAD = 1005; // 文件上传出错
    const ERR_PERM = 1007; // 没有该操作权限，请联系管理员
    const ERR_CLOSE = 1008; // 该接口已停用
    const ERR_MESSAGE = 1009; // 该接口已停用


    public static $msgs = [
        self::SUCC => '请求成功',

        self::TOKEN_ERROR => 'token验证失败',

        self::ERR_HTTP_UNAUTHORIZED => '登录已过期，请重新登录',
        self::ERR_HTTP_FORBIDDEN => '无权访问该地址',
        self::ERR_HTTP_NOT_FOUND => '请求地址不存在',
        self::ERR_INTERNAL_SERVER => '服务器内部错误',
        self::ERR_NO_AUTH => '没有改访问权限：%s',

        self::ERR_QUERY => '数据库操作失败： %s',
        self::ERR_DB => '数据库连接失败',
        self::ERR_PARAMS => '参数验证失败： %s',
        self::ERR_MODEL => '数据不存在： %s',
        self::ERR_FILE_UP_LOAD => '文件上传出错： %s',
        self::ERR_PERM => '没有该操作权限，请联系管理员',
        self::ERR_CLOSE => '该接口已停用',

        self::ERR_FETCH_OPENID => '获取openid失败：%s',
        self::ERR_LOGIN_FAILS => '登录失败：%s',
        self::ERR_BIND_PHONE => '绑定手机号失败',
    ];

    /**
     * 提示代码
     * @var | int
     */
    protected static $code;

    /**
     * 提示信息
     * @var | string
     */
    protected static $msg;

    /**
     * 详情信息
     * @var
     */
    protected static $detail;

    /**
     * 需要返回的数据
     * @var
     */
    protected static $data;
    protected static $rquest_params;

    /**
     * 设置提示信息
     *
     * @param $code 提示代码
     * @param null $msg 提示信息
     * @param array $params 提示信息中动态参数
     */
    public static function setCode($code, $msg = null, $data = [], $params = [], $rquest_params = [])
    {
        $code = (int)$code;
        if (null == $msg || '' == $msg) {
            if (isset($msgs[$code])) {
                if (!empty($params)) {
                    //array_unshift($params, $msgs[$code]);
                   // self::$msg = call_user_func_array('sprintf', $params);
                } else {
                    //self::$msg = self::$msgs[$code];
                }
            } else {
                //self::$msg = '提示信息未定义';
            }
        } else {
            //self::$msg = $msg;
        }

        if (self::SUCC !== $code) {
            // save log
        }
        //self::$data = $data;
       // self::$rquest_params = $rquest_params;
        //self::addLog();
        if (empty($data)) {
            return ['code' => $code, 'message' => $msg];
        } else {
            return ['code' => $code, 'message' => $msg, 'data' => $data];

        }

    }

    public static function addLog()
    {
        $log = new ApiErrorLog();
        $log->code = self::$code;
        $log->message = self::$msg;
        $log->data = self::$data;
        $log->param = self::$rquest_params;
        if (!$log->save()) {
            Log::error('请求日志写入失败');
        }
    }

}
