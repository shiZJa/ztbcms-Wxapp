<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Service;

use QCloud_WeApp_SDK\Auth\LoginService;
use QCloud_WeApp_SDK\Conf;
use System\Service\BaseService;

class WxappService extends BaseService {
    function __construct() {
        Conf::setup([
            'ServerHost' => 'wxapp.local.zhlhuang.cn',
            'AuthServerUrl' => 'http://wxapp.local.zhlhuang.cn//index.php?g=Wxapp&m=Test&a=mina_auth',
            'TunnelServerUrl' => 'https://ws.qcloud.la',
            'TunnelSignatureKey' => 'https://13054170.ws.qcloud.la',
        ]);
    }

    public function login() {
        return LoginService::login();
    }

    public function check(){
        return LoginService::check();
    }
}