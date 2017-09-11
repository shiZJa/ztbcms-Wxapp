<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Service;

use System\Service\BaseService;
use Wxapp\Lib\Auth;
use Wxapp\Lib\ReturnCode;

class ParseRequestService extends BaseService {
    public function __construct() {
    }

    /**
     * @param $request_json
     * @return array|int
     * 描述：解析接口名称
     */
    public function parseJson($request_json) {
        if ($this->isJson($request_json)) {
            $json_decode = json_decode($request_json, true);
            if (!isset($json_decode['interface']['interfaceName'])) {
                $ret['returnCode'] = ReturnCode::MA_NO_INTERFACE;
                $ret['returnMessage'] = 'NO_INTERFACENAME_PARA';
                $ret['returnData'] = '';
            } else {
                if (!isset($json_decode['interface']['para'])) {
                    $ret['returnCode'] = ReturnCode::MA_NO_PARA;
                    $ret['returnMessage'] = 'NO_PARA';
                    $ret['returnData'] = '';
                } else {
                    if ($json_decode['interface']['interfaceName'] == 'qcloud.cam.id_skey') {
                        if (isset($json_decode['interface']['para']['code']) && isset($json_decode['interface']['para']['encrypt_data'])) {
                            $code = $json_decode['interface']['para']['code'];
                            $encrypt_data = $json_decode['interface']['para']['encrypt_data'];
                            $auth = new Auth();
                            if (!isset($json_decode['interface']['para']['iv'])) {
                                $ret = $auth->get_id_skey($code, $encrypt_data);
                            } else {
                                $iv = $json_decode['interface']['para']['iv'];
                                $ret = $auth->get_id_skey($code, $encrypt_data, $iv);
                            }
                        } else {
                            $ret['returnCode'] = ReturnCode::MA_PARA_ERR;
                            $ret['returnMessage'] = 'PARA_ERR';
                            $ret['returnData'] = '';
                        }
                    } else {
                        if ($json_decode['interface']['interfaceName'] == 'qcloud.cam.auth') {
                            if (isset($json_decode['interface']['para']['id']) && isset($json_decode['interface']['para']['skey'])) {
                                $id = $json_decode['interface']['para']['id'];
                                $skey = $json_decode['interface']['para']['skey'];
                                $auth = new Auth();
                                $ret = $auth->auth($id, $skey);
                            } else {
                                $ret['returnCode'] = ReturnCode::MA_PARA_ERR;
                                $ret['returnMessage'] = 'PARA_ERR';
                                $ret['returnData'] = '';
                            }
                        } else {
                            if ($json_decode['interface']['interfaceName'] == 'qcloud.cam.decrypt') {
                                if (isset($json_decode['interface']['para']['id']) && isset($json_decode['interface']['para']['skey']) && isset($json_decode['interface']['para']['encrypt_data'])) {
                                    $id = $json_decode['interface']['para']['id'];
                                    $skey = $json_decode['interface']['para']['skey'];
                                    $encrypt_data = $json_decode['interface']['para']['encrypt_data'];
                                    $auth = new Auth();
                                    $ret = $auth->decrypt($id, $skey, $encrypt_data);
                                } else {
                                    $ret['returnCode'] = ReturnCode::MA_PARA_ERR;
                                    $ret['returnMessage'] = 'PARA_ERR';
                                    $ret['returnData'] = '';
                                }
                            } else {
                                $ret['returnCode'] = ReturnCode::MA_INTERFACE_ERR;
                                $ret['returnMessage'] = 'INTERFACENAME_PARA_ERR';
                                $ret['returnData'] = '';
                            }
                        }
                    }
                }
            }
        } else {
            $ret['returnCode'] = ReturnCode::MA_REQUEST_ERR;
            $ret['returnMessage'] = 'REQUEST_IS_NOT_JSON';
            $ret['returnData'] = '';
        }
        $ret['version'] = 1;
        $ret['componentName'] = "MA";
//        log_message("info", json_encode($ret));

        return json_encode($ret);
    }

    /**
     * @param $str
     * @return bool
     * 描述：判断字符串是不是合法的json
     */
    private function isJson($str) {
        json_decode($str);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}