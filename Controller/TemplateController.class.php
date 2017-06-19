<?php
namespace Wxapp\Controller;

use Wxapp\Service\TemplateService;

class TemplateController extends BaseController {
    public function addTemplateFrom() {
        $openid = $this->userInfo['openId'];
        $from_id = I('post.from_id');
        $from_type = I('post.from_type', 'form');
        $res = TemplateService::addTemplateFrom($openid, $from_id, $from_type);
        $this->ajaxReturn($res);
    }

    public function sendTemplate() {
        $openid = $this->userInfo['openId'];
        $from_id = I('post.from_id');
        $data = [
            'keyword1' => ['value' => time(), 'color' => '#173177'],
            'keyword2' => ['value' => '200', 'color' => '#173177'],
            'keyword3' => ['value' => '已支付', 'color' => '#173177'],
            'keyword4' => ['value' => '购买商品', 'color' => '#173177'],
            'keyword5' => ['value' => date("Y-m-d H:i", time()), 'color' => '#173177'],
        ];
        $res = TemplateService::sendTemplate($openid, 'THt3zSp7wmHKDJpWvHugQe_qLt470YQ7c1CcI6Pke8w', $from_id,
            '/pages/test/test', $data);
        $this->ajaxReturn($res);
    }
}