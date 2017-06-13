<?php
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\WxpayService;

class NotifyController extends Base {
    public function payOrder() {
        $wxpay = new WxpayService();
        $wxpay->pay_notify(function () {

        });
    }
}