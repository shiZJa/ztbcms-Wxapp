<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Service;

use QCloud_WeApp_SDK\Auth\LoginService;
use QCloud_WeApp_SDK\Conf;
use QCloud_WeApp_SDK\Tunnel\TunnelService;
use System\Service\BaseService;
use Wxapp\Handler\ChatTunnelHandler;

class WxappService extends BaseService {
    function __construct() {
        Conf::setup([
            'ServerHost' => 'wxapp.local.zhlhuang.cn',
            'AuthServerUrl' => 'http://wxapp.local.zhlhuang.cn/index.php?g=Wxapp&m=User&a=mina_auth',
        ]);
    }

    public function login() {
        return LoginService::login();
    }

    public function check(){
        return LoginService::check();
    }
    public function tunnel(){
        $handle = new ChatTunnelHandler();
        return TunnelService::handle($handle, ['checkLogin' => true]);
    }
}