<?php
namespace Wxapp\Controller;

use Common\Controller\AdminBase;
use Wxapp\Service\CappinfoService;

/**
 * 微信后台类
 *
 * Class WxappController
 *
 * @package Wxapp\Controller
 */
class WxappController extends AdminBase {

    /**
     * 模块设置页面
     */
    function setting() {
        if (IS_POST) {
            $wxapp_is_author = I('wxapp_is_author');
            $wxapp_template_id = I('wxapp_template_id');
            $data = [
                'wxapp_template_id' => $wxapp_template_id,
                'wxapp_is_author' => $wxapp_is_author
            ];
            foreach ($data as $key => $val) {
                $add_item = [
                    'varname' => $key,
                    'value' => $val,
                    'groupid' => 20
                ];
                $is_exist = M('Config')->where(['varname' => $key])->find();
                if ($is_exist) {
                    M('Config')->where(['varname' => $key])->save($add_item);
                } else {
                    M('Config')->add($add_item);
                }
            }
            cache('Config', null);
            $this->success('保存成功', U('setting'));
        } else {
            $config = cache('Config');
            $this->assign('config', $config);
            $this->display();
        }
    }

    public function index() {
        if (IS_AJAX) {
            $page = I('page', 1);
            $limit = I('limit', 1);
            $wxapps = M(CappinfoService::TABLE_NAME)->page($page, $limit)->select();
            $total = M(CappinfoService::TABLE_NAME)->count();
            $data = [
                'lists' => $wxapps ? $wxapps : [],
                'page' => $page,
                'limit' => $limit,
                'total' => $total
            ];
            $this->ajaxReturn(self::createReturn(true, $data, 'ok'));
        }
        $config = cache('Config');
        $this->assign('config', $config);
        $this->display('index');
    }

    public function addWxapp() {
        $id = I('get.id');
        $config = cache('Config');
        $this->assign('id', $id);
        $this->assign('config', $config);
        $this->display('addwxapp');
    }


    /**
     * 获取微信小程序参数
     */
    function getSettings() {
        $id = I('get.id');
        //获取表字段
        $Model = M('wxappAppinfo');
        //获取记录
        $setting = $Model->find($id);
        $this->ajaxReturn(createReturn(true, $setting));
    }

    /**
     * 设置微信小程序参数
     */
    public function doSetting() {
        $Model = M('WxappAppinfo');
        if (I('post.is_default') == 1) {
            //如果设置成默认，则其他配置就非默认
            $Model->where(['is_default' => 1])->save(['is_default' => 0]);
        }
        if (I('post.id')) {
            $result = $Model->data(I('post.'))->save();
        } else {
            $result = $Model->add(I('post.'));
        }
        if ($result) {
            $this->ajaxReturn(self::createReturn(true, '', '操作成功'));
        } else {
            $this->ajaxReturn(self::createReturn(false, '', ''));
        }
    }

    public function deleteWxapp() {
        $id = I('post.id');
        $Model = M('WxappAppinfo');
        $result = $Model->where(['id' => $id])->delete();
        if ($result) {
            $this->ajaxReturn(self::createReturn(true, $result, 'ok'));
        } else {
            $this->ajaxReturn(self::createReturn(false, '', '删除失败'));
        }
    }
}