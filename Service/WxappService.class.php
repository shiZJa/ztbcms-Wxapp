<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Service;

use System\Service\BaseService;
use Wxapp\Lib\Conf;
use Wxapp\Lib\HttpUtil;

class WxappService extends BaseService {
    function __construct() {
        Conf::setup([
            'ServerHost' => $_SERVER['HTTP_HOST'],
            'AuthServerUrl' => U('Wxapp/User/minaAuth'),
        ]);
    }

    /**
     * 获取access_token
     *
     * @return array
     */
    static function getAccessToken() {
        $app = CappinfoService::getAppInfo()['data'];
        if (time() < $app['expires_in'] + $app['get_access_token_time'] - 600) {
            //还没有到过期时间
            return self::createReturn(true, $app['access_token'], '');
        }
        if ($app) {
            $http = new HttpUtil();
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
            $params = [
                'appid' => $app['appid'],
                'secret' => $app['secret']
            ];
            foreach ($params as $key => $value) {
                $url .= '&' . $key . '=' . $value;
            }
            $res = json_decode($http->http_get($url), 1);
            if (!empty($res['access_token'])) {
                $update_data = [
                    'access_token' => $res['access_token'],
                    'expires_in' => $res['expires_in'],
                    'get_access_token_time' => time()
                ];
                //更新access_token
                M(CappinfoService::TABLE_NAME)->where(['appid' => $app['appid']])->save($update_data);

                return self::createReturn(true, $res['access_token'], '');
            } else {
                return self::createReturn(false, $res, empty($res['errmsg']) ? '获取失败' : $res['errmsg']);
            }
        } else {
            return self::createReturn(false, '', '找不到app配置');
        }
    }

    /**
     * 调用登录sdk
     *
     * @return array
     */
    public function login() {
        return LoginService::login();
    }

    /**
     * 获取用户信息sdk
     *
     * @return array
     */
    public function check() {
        return LoginService::check();
    }
}