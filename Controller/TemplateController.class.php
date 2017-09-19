<?php
namespace Wxapp\Controller;

use Wxapp\Service\TemplateService;

class TemplateController extends BaseController {
    /**
     * 添加发送模板消息所需要的来源信息
     */
    public function addTemplateForm() {
        $openid = $this->userInfo['openId'];
        $form_id = I('post.form_id');
        $from_type = I('post.from_type', 'form'); //如果是支付则是wxpay
        $res = TemplateService::addTemplateFrom($openid, $form_id, $from_type);
        $this->ajaxReturn($res);
    }

    /**
     * 发送模板消息测试
     */
    public function sendTemplate() {
        $openid = $this->userInfo['openId'];
        $form_id = I('post.form_id');
        $data = [
            'keyword1' => ['value' => time(), 'color' => '#173177'],
            'keyword2' => ['value' => '200', 'color' => '#173177'],
            'keyword3' => ['value' => '已支付', 'color' => '#173177'],
            'keyword4' => ['value' => '购买商品', 'color' => '#173177'],
            'keyword5' => ['value' => date("Y-m-d H:i", time()), 'color' => '#173177'],
        ];
        $res = TemplateService::sendTemplate($openid, 'THt3zSp7wmHKDJpWvHugQe_qLt470YQ7c1CcI6Pke8w', $form_id,
            '/pages/test/test', $data);
        $this->ajaxReturn($res);
    }
}