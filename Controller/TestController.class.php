<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\OpenService;
use Wxapp\Service\WxappService;
use Wxapp\Service\WxpayService;

class TestController extends Base {
    public function index() {
        $res = OpenService::getPage('wxde88953c782ad68f');
        $this->ajaxReturn($res);
    }
}