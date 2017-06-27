<?php
namespace Wxapp\Controller;

use Common\Controller\AdminBase;
use Wxapp\Service\OpenService;

class OpenController extends AdminBase {
    public function domainList() {
        $appid = I('appid');
        $this->assign('appid', $appid);
        if (IS_AJAX) {
            $res = OpenService::modifyDomain($appid, 'get');
            $this->ajaxReturn($res);
        }
        $this->display('domainlist');
    }

    public function addDomain() {
        $appid = I('appid');
        if (IS_POST) {
            $settings = I('post.settings');
            $data = [
                'requestdomain' => trim($settings['requestdomain']),
                'uploaddomain' => trim($settings['uploaddomain']),
                'wsrequestdomain' => trim($settings['uploaddomain']),
                'downloaddomain' => trim($settings['downloaddomain'])
            ];
            $res = OpenService::modifyDomain($appid, 'add', $data);

            return $this->ajaxReturn($res);
        }
        $this->assign('appid', $appid);
        $this->display('adddomain');
    }

    public function deleteDomain() {
        if (IS_POST) {
            $appid = I('post.appid');
            $key = I('post.key');
            $domain = I('post.domain');

            $res = OpenService::modifyDomain($appid, 'delete', [$key => $domain]);

            return $this->ajaxReturn($res);
        }
    }

    public function addTester() {
        $appid = I('appid');
        if (IS_POST) {
            $wechatid = I('post.wechatid');
            $res = OpenService::bindTester($appid, $wechatid);
            $this->ajaxReturn($res);
        }
        $this->assign('appid', $appid);
        $this->display('addtester');
    }
}