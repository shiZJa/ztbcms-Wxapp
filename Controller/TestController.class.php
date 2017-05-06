<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\WxappService;

class TestController extends Base {
    public function index() {
        $wxapp = new WxappService();
        $wxapp->login();
    }
}