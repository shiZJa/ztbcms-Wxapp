<?php
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\ParseRequestService;
use Wxapp\Service\WxappService;

class UserController extends Base {
    public function login() {
        $wxapp = new WxappService();
        $wxapp->login();
    }

    public function getUserinfo() {
        $wxapp = new WxappService();
        $res = $wxapp->check();
        $this->ajaxReturn($res);
    }

    public function mina_auth() {
        $request = file_get_contents("php://input");
        $parse_request = new ParseRequestService();
        $res = $parse_request->parseJson($request);
        echo $res;
    }
}