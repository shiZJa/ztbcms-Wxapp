<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\ParseRequestService;
use Wxapp\Service\WxappService;

class TestController extends Base {
    public function index() {
        $wxapp = new WxappService();
        $res = $wxapp->login();
        $this->ajaxReturn($res);
    }

    public function user() {
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