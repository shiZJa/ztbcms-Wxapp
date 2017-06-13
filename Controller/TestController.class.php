<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\WxappService;
use Wxapp\Service\WxpayService;

class TestController extends Base {
    public function index() {
        $res = WxpayService::getWxpayConfig('odu_u0NDWBCBsRA9L5AngdY_z-Aw', time(), 1, U('index'));
        $this->ajaxReturn($res);
    }
}