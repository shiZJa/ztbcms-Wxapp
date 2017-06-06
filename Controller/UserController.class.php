<?php

namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\ParseRequestService;
use Wxapp\Service\WxappService;
use Wxapp\Lib\AuthAPI;
use Wxapp\Service\UserinfoService;

class UserController extends Base {

    // 小程序用户的 CMS 用户模型名称,如果没有将会自动创建
    CONST CMS_MEMBER_MODEL_NAME = 'wxapp';

    /**
     * 登录操作
     */
    public function login() {
        $wxapp = new WxappService();
        $res = $wxapp->login();

        if ($res['code'] == 0) {
            $id = $res['data']['id'];
            $skey = $res['data']['skey'];

            $checkResult = AuthAPI::checkLogin($id, $skey);

            $userInfo = $checkResult['user_info'];

            //检查有没有注册
            $record = M('member')->where(['username' => $userInfo['openId']])->find();
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
                    'regip' => get_client_ip(), //注册的ip地址
                    'checked' => 1,
                    'modelid' => self::getCMSModelId(),//获取小程序用户的用户模型ID
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

    /**
     * 获取小程序用户的用户模型ID
     * @return int 模型ID
     */
    protected function getCMSModelId() {
        $name = self::CMS_MEMBER_MODEL_NAME;
        $record = M('model')->where(['name' => $name])->find();
        if (empty($record)) {
            $data = [
                'name' => $name,
                'description' => '小程序用户的用户模型',
                'tablename' => 'member_' . $name,
                'type' => 2,
            ];
            $model = D('Content/Model');
            $data = $model->create($data);
            if ($data) {
                $id = $model->add($data);
                if ($id > 0) {
                    //创建表
                    $model->AddModelMember($data['tablename'], $id);
                    //更新缓存
                    D('Member/Member')->member_cache();
                    return $id;
                }
            }
            return 0; //创建模型失败
        } else {
            return $record['id'];
        }
    }
}