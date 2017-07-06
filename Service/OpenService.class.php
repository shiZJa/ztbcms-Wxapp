<?php
namespace Wxapp\Service;

use \Exception as Exception;
use System\Service\BaseService;
use Wxapp\Lib\Constants;
use Wxapp\Lib\DecryptData;
use Wxapp\Lib\HttpUtil;
use Wxapp\Lib\LoginServiceException;
use Wxapp\Lib\ReturnCode;
use Wxapp\Lib\WXBizDataCrypt;


class OpenService extends BaseService {
    const DOMAIN = 'http://fenxiangbei.com';
    const EXPIRES_IN = 7200;

    static function release($appid, $auditid) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/release/app_id/' . $appid . '.html';
        $data = [];
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $http = new HttpUtil();
        $res = json_decode($http->http_post($url, $data), 1);
        if (!empty($res['code'])) {
            M(AuditService::TABLE_NAME)->where([
                'appid' => $appid,
                'auditid' => $auditid,
                'update_time'=>time(),
            ])->save(['is_release' => 1]); //已经发布

            return self::createReturn(true, $res['data'], $res['msg']);
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    static function getAuditstatus($appid, $auditid) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/get_auditstatus/app_id/' . $appid . '.html';
        $data = ['auditid' => $auditid];
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $http = new HttpUtil();
        $res = json_decode($http->http_post($url, $data), 1);
        if (!empty($res['code'])) {
            //获取最新的审核信息更新
            $data = $res['data'];
            M(AuditService::TABLE_NAME)->where(['auditid' => $auditid])->save([
                'status' => $data['status'],
                'reason' => $data['reason'],
                'update_time' => time()
            ]);

            return self::createReturn(true, M(AuditService::TABLE_NAME)->where(['auditid' => $auditid])->find(),
                $res['msg']);
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    static function submitAudit($appid, $data) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/submit_audit/app_id/' . $appid . '.html';
        $data = ['data' => json_encode($data, JSON_UNESCAPED_UNICODE)];
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $http = new HttpUtil();
        $res = json_decode($http->http_post($url, $data), 1);
        if (!empty($res['code'])) {
            M(AuditService::TABLE_NAME)->add([
                'appid' => $appid,
                'auditid' => $res['data'],
                'create_time' => time(),
                'status' => 2,
            ]);

            return self::createReturn(true, $res['data'], $res['msg']);
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    static function getPage($appid) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/get_page/app_id/' . $appid . '.html';
        $time = time();
        $data = ['time' => $time];
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $http = new HttpUtil();
        $res = json_decode($http->http_get($url, $data), 1);
        if (!empty($res['code'])) {
            return self::createReturn(true, $res['data'], $res['msg']);
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    static function getCategory($appid) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/get_category/app_id/' . $appid . '.html';
        $time = time();
        $data = ['time' => $time];
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $http = new HttpUtil();
        $res = json_decode($http->http_get($url, $data), 1);
        if (!empty($res['code'])) {
            return self::createReturn(true, $res['data'], $res['msg']);
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    static function getQrcode($appid) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/get_qrcode/app_id/' . $appid . '.html';
        $time = time();
        $data = ['time' => $time];
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $http = new HttpUtil();
        $res = json_decode($http->http_get($url, $data), 1);
        if (!empty($res['code'])) {
            return self::createReturn(true, $res['data'], $res['msg']);
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    static function commit($appid, $template_id, $user_version, $user_desc, $ext_json) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/commit/app_id/' . $appid . '.html';
        $data = [
            'template_id' => $template_id,
            'user_version' => $user_version,
            'user_desc' => $user_desc,
            'ext_json' => $ext_json,
        ];
        $http = new HttpUtil();
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $res = json_decode($http->http_post($url, $data), 1);
        if (!empty($res['code'])) {
            //创建成功后添加到数据库
            $data['appid'] = $appid;
            $data['create_time'] = time();
            $res = M(CommitService::TABLE_NAME)->add($data);

            if ($res) {
                return self::createReturn(true, $res['data'], $res['msg']);
            } else {
                return self::createReturn(false, $data, '数据库添加失败');
            }
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    /**
     * 绑定指定小程序的体验者
     *
     * @param $appid     需要绑定的小程序appid
     * @param $wechatid  绑定用户的微信号
     * @return array
     */
    static function bindTester($appid, $wechatid) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/bind_tester/app_id/' . $appid . '.html';
        $data = [
            'wechatid' => $wechatid,
        ];
        $http = new HttpUtil();
        $sign = self::sign($appid, $data, $app['secret_key'])['data'];
        $data['sign'] = $sign;
        $res = json_decode($http->http_post($url, $data), 1);
        if (!empty($res['code'])) {
            return self::createReturn(true, $res['data'], $res['msg']);
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    /**
     * @param null   $appid
     * @param string $action
     * @param array  $data
     * @return array
     */
    static function modifyDomain($appid = null, $action = 'get', $data = []) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
        $url = self::DOMAIN . '/api_v2/wxapp/modify_domain/app_id/' . $appid . '.html';
        $action_arr = [
            'action' => $action,
        ];
        $post_data = [
            'data' => json_encode(array_merge($action_arr, $data))
        ];
        $http = new HttpUtil();
        $sign = self::sign($appid, $post_data, $app['secret_key'])['data'];
        $post_data['sign'] = $sign;
        $res = json_decode($http->http_post($url, $post_data), 1);
        if (!empty($res['code'])) {
            return self::createReturn(true, $res['data'], $res['msg']);
        } else {
            return self::createReturn(false, $res['data'], $res['msg']);
        }
    }

    static function login($appid = null) {
        try {
            $app = CappinfoService::getAppInfo($appid)['data'];
            $appid = $app['appid'];

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
                    $user_info_arr = json_decode(base64_decode($user_info), 1);
                    if ($change_result) {
                        $id = $csessioninfo_service->get_id_csessioninfo($openid);
                        $arr_result['id'] = $id;
                        $arr_result['skey'] = $skey;
                        $arr_result['user_info'] = $user_info_arr;
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
                            $arr_result['user_info'] = $user_info_arr;
                            $arr_result['duration'] = $json_message['expires_in'];
                            $ret['returnCode'] = ReturnCode::MA_OK;
                            $ret['returnMessage'] = 'UPDATE_SESSION_SUCCESS';
                            $ret['returnData'] = $arr_result;
                        }
                    }
                }
                if ($ret['returnCode'] == 0) {
                    $data = $ret['returnData'];
                    $res_data = [
                        Constants::WX_SESSION_MAGIC_ID => 1,
                        'session' => ['id' => $data['id'], 'skey' => $data['skey']],
                        'userInfo' => $data['user_info']
                    ];

                    UserinfoService::updateInfo($user_info_arr, $appid);

                    return self::createReturn(true, $res_data, 'ok');
                } else {
                    return self::createReturn(false, $ret['returnCode'], $ret['returnMessage']);
                }
            } else {
                return $res;
            }
        } catch (Exception $e) {
            $error = new LoginServiceException(Constants::ERR_LOGIN_FAILED, $e->getMessage());

            return self::createReturn(false, $ret['returnCode'], $error);
        }

    }

    static function getSession($appid = null, $code) {
        $app = CappinfoService::getAppInfo($appid)['data'];
        $appid = $app['appid'];
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