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
            //检查有没有注册
            $record = M('member')->where(['username' => $this->userInfo['openId']])->find();
            if (empty($record)) {
                //未注册
                $info = [
                    'username' => $this->userInfo['openId'],
                    'password' => $this->userInfo['openId'],
                    'email' => $this->userInfo['openId'] . '@163.com',
                ];

                $userid = service("Passport")->userRegister($info['username'], $info['password'], $info['email']);

                $data = [
                    'nickname' => $this->userInfo['nickName'],
                    'sex' => $this->userInfo['gender'],
                    'userpic' => $this->userInfo['avatarUrl'],
                    'regdate' => time(),
                    'regip' => get_client_ip, //注册的ip地址
                    'checked' => 1,
                    //TODO 配置小程序用户模型ID
//                    'modelid' => 1,
                ];
                D('Member')->where("userid='%d'", $userid)->save($data);
            }
            UserinfoService::updateInfo($this->userInfo);
        }
    }
}