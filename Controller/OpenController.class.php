<?php
namespace Wxapp\Controller;

use Common\Controller\AdminBase;
use Wxapp\Service\AuditService;
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

    /**
     * 上传代码操作
     */
    public function commit() {
        $appid = I('appid');
        if (IS_POST) {
            $template_id = I('post.template_id');
            $user_version = I('post.user_version');
            $user_desc = I('post.user_desc');
            $ext = I('post.ext');
            $ext_arr = [
                'extAppid' => $appid,
                'ext' => []
            ];
            foreach ($ext as $value) {
                $ext_arr['ext'][$value['key']] = $value['value'];
            }
            $res = OpenService::commit($appid, $template_id, $user_version, $user_desc, json_encode($ext_arr));
            $this->ajaxReturn($res);
        }
        $this->assign('appid', $appid);
        $this->display('commit');
    }

    /**
     * 获取二维码
     */
    public function getQrCode() {
        $appid = I('get.appid');
        $res = OpenService::getQrcode($appid);
        if ($res['status']) {
            header('Content-type: image/jpg');
            echo base64_decode($res['data']);
        } else {
            $this->ajaxReturn($res);
        }
    }

    public function submitAudit() {
        $appid = I('appid');
        if (IS_POST) {
            $post = I('post.');
            $post_data = [
                'title' => $post['title'],
                'address' => $post['address'],
                'tag' => $post['tag'],
                'first_class' => $post['first_class'],
                'second_class' => $post['second_class'],
                'third_class' => $post['third_class']
            ];
            $res = OpenService::submitAudit($appid, ['item_list' => [$post_data]]);
            $this->ajaxReturn($res);
        }
        $this->assign('appid', $appid);
        $this->display('submitaudit');
    }

    public function getAddressList() {
        $appid = I('appid');
        $res = OpenService::getPage($appid);
        $this->ajaxReturn($res);
    }

    public function getCategoryList() {
        $appid = I('appid');
        $res = OpenService::getCategory($appid);
        $this->ajaxReturn($res);
    }

    public function getAuditstatus() {
        $appid = I('appid');
        //获取最新的提交记录
        $audit = M(AuditService::TABLE_NAME)->where(['appid' => $appid])->order('create_time desc')->find();
        $res = OpenService::getAuditstatus($appid, $audit['auditid']);
        $this->ajaxReturn($res);
    }
}