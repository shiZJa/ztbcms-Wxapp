<?php
namespace Wxapp\Lib;

use \Exception as Exception;

use Think\Log;
use Wxapp\Helper\Request as Request;

class AuthAPI {
    public static function login($appid, $code, $encrypt_data, $iv) {
        if ($code && isset($encrypt_data)) {
            $auth = new Auth();
            $ret = $auth->get_id_skey($appid, $code, $encrypt_data, $iv);
        } else {
            $ret['returnCode'] = ReturnCode::MA_PARA_ERR;
            $ret['returnMessage'] = 'PARA_ERR';
            $ret['returnData'] = '';
        }
        return $ret;
    }

    public static function checkLogin($appid, $id, $skey) {
        if (isset($id) && isset($skey)) {
            $auth = new Auth();
            $ret = $auth->auth($appid, $id, $skey);
        } else {
            $ret['returnCode'] = ReturnCode::MA_PARA_ERR;
            $ret['returnMessage'] = 'PARA_ERR';
            $ret['returnData'] = '';
        }
        return $ret;
    }
}
