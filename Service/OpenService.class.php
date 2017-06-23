<?php
namespace Wxapp\Service;

use System\Service\BaseService;
use Wxapp\Lib\Constants;
use Wxapp\Lib\DecryptData;
use Wxapp\Lib\HttpUtil;
use Wxapp\Lib\ReturnCode;
use Wxapp\Lib\WXBizDataCrypt;


class OpenService extends BaseService {
    const DOMAIN = 'http://fenxiangbei.com';
    const EXPIRES_IN = 7200;

    static function getUserInfo($appid) {

        $code = LoginService::getHttpHeader(Constants::WX_HEADER_CODE);
        $encrypt_data = LoginService::getHttpHeader(Constants::WX_HEADER_ENCRYPTED_DATA);
        $iv = LoginService::getHttpHeader(Constants::WX_HEADER_IV);

        $res = OpenService::getSession($appid, $code);

        if ($res['status']) {
            $json_message = $res['data'];
            $json_message['expires_in'] = self::EXPIRES_IN;
            $uuid = md5((time() - mt_rand(1, 10000)) . mt_rand(1, 1000000));//生成UUID
            $skey = md5(time() . mt_rand(1, 1000000));//生成skey
            $create_time = date('Y-m-d H:i:s', time());
            $last_visit_time = date('Y-m-d H:i:s', time());
            $openid = $json_message['openid'];
            $session_key = $json_message['session_key'];
            $errCode = 0;
            $user_info = false;
            //兼容旧的解密算法
            if ($iv == "old") {
                $decrypt_data = new DecryptData();
                $user_info = $decrypt_data->aes128cbc_Decrypt($encrypt_data, $session_key);
                log_message("INFO", "userinfo:" . $user_info);
                $user_info = base64_encode($user_info);
            } else {
                $pc = new WXBizDataCrypt($appid, $session_key);
                $errCode = $pc->decryptData($encrypt_data, $iv, $user_info);
                $user_info = base64_encode($user_info);
            }
            if ($user_info === false || $errCode !== 0) {
                $ret['returnCode'] = ReturnCode::MA_DECRYPT_ERR;
                $ret['returnMessage'] = 'DECRYPT_FAIL';
                $ret['returnData'] = '';
            } else {
                $params = array(
                    "uuid" => $uuid,
                    "skey" => $skey,
                    "create_time" => $create_time,
                    "last_visit_time" => $last_visit_time,
                    "openid" => $openid,
                    "session_key" => $session_key,
                    "user_info" => $user_info,
                    "login_duration" => self::EXPIRES_IN,
                    "appid" => $appid,
                );

                $csessioninfo_service = new CsessioninfoService();
                $change_result = $csessioninfo_service->change_csessioninfo($params);
                if ($change_result) {
                    $id = $csessioninfo_service->get_id_csessioninfo($openid);
                    $arr_result['id'] = $id;
                    $arr_result['skey'] = $skey;
                    $arr_result['user_info'] = json_decode(base64_decode($user_info));
                    $arr_result['duration'] = $json_message['expires_in'];
                    $ret['returnCode'] = ReturnCode::MA_OK;
                    $ret['returnMessage'] = 'NEW_SESSION_SUCCESS';
                    $ret['returnData'] = $arr_result;
                } else {
                    if ($change_result === false) {
                        $ret['returnCode'] = ReturnCode::MA_CHANGE_SESSION_ERR;
                        $ret['returnMessage'] = 'CHANGE_SESSION_ERR';
                        $ret['returnData'] = '';
                    } else {
                        $arr_result['id'] = $change_result;
                        $arr_result['skey'] = $skey;
                        $arr_result['user_info'] = json_decode(base64_decode($user_info));
                        $arr_result['duration'] = $json_message['expires_in'];
                        $ret['returnCode'] = ReturnCode::MA_OK;
                        $ret['returnMessage'] = 'UPDATE_SESSION_SUCCESS';
                        $ret['returnData'] = $arr_result;
                    }
                }
            }
            if ($ret['returnCode'] == 0) {
                return self::createReturn(true, $ret['returnData'], 'ok');
            } else {
                return self::createReturn(true, $ret['returnCode'], $ret['returnMessage']);
            }
        }
    }

    static function getSession($appid, $code) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $url = self::DOMAIN . '/api_v2/wxapp/get_session/app_id/' . $appid . '.html';
        $data = ['code' => $code];
        $http = new HttpUtil();
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $res = json_decode($http->http_get($url, $data), 1);
        if (!empty($res['code'])) {
            return self::createReturn(true, $res['data'], 'ok');
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    /** 签名生成函数
     *
     * @param $app_id
     * @param $post_data
     * @param $secret_key
     * @return string
     */
    static function sign($app_id, $post_data, $secret_key) {
        ksort($post_data);
        $str = '';
        foreach ($post_data as $key => $value) {
            $str = $key . '=' . $value . '&';
        }

        return self::createReturn(true, md5($app_id . $str . $secret_key), 'ok');
    }
}