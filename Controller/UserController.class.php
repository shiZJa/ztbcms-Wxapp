<?php
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\ParseRequestService;
use Wxapp\Service\WxappService;

class UserController extends Base {
    /**
     * 登录操作
     */
    public function login() {
        $wxapp = new WxappService();
        $wxapp->login();
    }

    /**
     * 获取登录用户信息
     */
    public function getUserinfo() {
        $wxapp = new WxappService();
        $res = $wxapp->check();
        $this->ajaxReturn($res);
    }

    /**
     * 权限认证
     */
    public function mina_auth() {
        $request = file_get_contents("php://input");
        $parse_request = new ParseRequestService();
        $res = $parse_request->parseJson($request);
        echo $res;
    }
}