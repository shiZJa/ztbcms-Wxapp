<?php
namespace Wxapp\Service;

use \Exception as Exception;

use System\Service\BaseService;
use Wxapp\Helper\Util;
use Wxapp\Lib\AuthAPI;
use Wxapp\Lib\Constants;


class LoginService extends BaseService {
    public static function login($appid) {
        $code = self::getHttpHeader(Constants::WX_HEADER_CODE);
        $encryptedData = self::getHttpHeader(Constants::WX_HEADER_ENCRYPTED_DATA);
        $iv = self::getHttpHeader(Constants::WX_HEADER_IV);

        $loginResult = AuthAPI::login($appid, $code, $encryptedData, $iv);
        if ($loginResult['returnCode'] == 0) {
            $result = array();
            $result['session'] = array(
                'id' => $loginResult['returnData']['id'],
                'skey' => $loginResult['returnData']['skey'],
            );
            $result['userInfo'] = $loginResult['returnData']['user_info'];

            return self::createReturn(true, $result, 'ok');
        } else {
            return self::createReturn(false, $loginResult, $loginResult['returnMessage']);
        }
    }

    public static function check($appid) {
        $id = self::getHttpHeader(Constants::WX_HEADER_ID);
        $skey = self::getHttpHeader(Constants::WX_HEADER_SKEY);

        $checkResult = AuthAPI::checkLogin($appid, $id, $skey);
        if ($checkResult['returnCode'] == 0) {
            return [
                'code' => 0,
                'message' => 'ok',
                'data' => [
                    'userInfo' => $checkResult['returnData']['user_info'],
                ],
            ];
        } else {
            return [
                'code' => $checkResult['returnCode'],
                'message' => $checkResult['returnMessage'],
            ];
        }
    }

    static function getHttpHeader($headerKey) {
        $headerValue = Util::getHttpHeader($headerKey);
        if (!$headerValue) {
            throw new Exception("请求头未包含 {$headerKey}，请配合客户端 SDK 登录后再进行请求");
        }
        return $headerValue;
    }
}
