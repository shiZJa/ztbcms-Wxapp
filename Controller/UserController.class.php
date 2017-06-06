<?php
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\ParseRequestService;
use Wxapp\Service\WxappService;
use Wxapp\Lib\AuthAPI;
use Wxapp\Service\UserinfoService;

class UserController extends Base {
    /**
     * 登录操作
     */
    public function login() {
        $wxapp = new WxappService();
        $res = $wxapp->login();

        if ($res['code'] == 0){
            $id = $res['data']['id'];
            $skey = $res['data']['skey'];

            $checkResult = AuthAPI::checkLogin($id, $skey);

            $userInfo = $checkResult['user_info'];

            //检查有没有注册
            $record = M('member')->where(['username' => $this->userInfo['openId']])->find();
            if (empty($record)) {
                //未注册
                $info = [
                    'username' => $userInfo['openId'],
                    'password' => $userInfo['openId'],
                    'email' => $userInfo['openId'] . '@163.com',
                ];

                //注册 cms 账号
                $userid = service("Passport")->userRegister($info['username'], $info['password'], $info['email']);

                $data = [
                    'nickname' => $userInfo['nickName'],
                    'sex' => $userInfo['gender'],
                    'userpic' => $userInfo['avatarUrl'],
                    'regdate' => time(),
                    'regip' => get_client_ip, //注册的ip地址
                    'checked' => 1,
                    // TODO 配置小程序用户模型ID
//                    'modelid' => 1,
                ];
                D('Member')->where("userid='%d'", $userid)->save($data);
            }

            UserinfoService::updateInfo($userInfo);
        }
    }

    /**
     * 获取登录用户信息
     */
    public function getUserinfo() {
        $wxapp = new WxappService();
        $res = $wxapp->check();
        $this->ajaxReturn($res);
    }

    /**
     * 权限认证
     */
    public function mina_auth() {
        $request = file_get_contents("php://input");
        $parse_request = new ParseRequestService();
        $res = $parse_request->parseJson($request);
        echo $res;
    }
}