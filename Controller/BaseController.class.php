<?php

namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\UserinfoService;
use Wxapp\Service\WxappService;

class BaseController extends Base {
    protected $userInfo = null;

    //初始化
    protected function _initialize() {
        parent::_initialize();
        $wxappService = new WxappService();
        $res = $wxappService->check();
        if ($res['code'] != 0) {
            //没有登录
            $this->ajaxReturn(['status' => false, 'msg' => '未登录', 'code' => "500"]);
        } else {
            $this->userInfo = $res['data']['userInfo'];
        }
    }
}