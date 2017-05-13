<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Service;

use System\Service\BaseService;
use Wxapp\Lib\Conf;

class WxappService extends BaseService {
    function __construct() {
        Conf::setup([
            'ServerHost' => C('ServerHost'),
            'AuthServerUrl' => C('AuthServerUrl'),
        ]);
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