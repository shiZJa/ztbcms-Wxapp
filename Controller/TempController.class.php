<?php
namespace Wxapp\Controller;

use Common\Controller\AdminBase;
use Wxapp\Service\TemplateService;

class TempController extends AdminBase {

    public function index(){
        $this->display();
    }

    public function getList(){
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $lists = M('WxappTemplate')->page($page, $limit)->select();
        $total = M('WxappTemplate')->count();
        foreach($lists as &$item){
            $info = M('WxappAppinfo')->where(['id' => $item['appinfo_id']])->find();
            $item['appid'] = $info['appid'];
            $item['mp_appid'] = $info['office_appid'];
            $item['description'] = str_replace("\n", "<br>", $item['description']);
        }
        $data = [
            'lists' => $lists ? $lists : [],
            'page' => $page,
            'limit' => $limit,
            'total' => $total
        ];
        $this->ajaxReturn(self::createReturn(true, $data, 'ok'));
    }

    public function temp(){
        $this->display();
    }

    public function getTemp(){
        $id = I('get.id');
        $data = M('WxappTemplate')->where(['id' => $id])->find();
        $this->ajaxReturn(self::createReturn(true, $data, 'ok'));
    }

    public function addEditTemp(){
        $Model = M('WxappTemplate');
        if (I('post.id')) {
            $result = $Model->data(I('post.'))->save();
        } else {
            $result = $Model->add(I('post.'));
        }
        if ($result) {
            $this->ajaxReturn(self::createReturn(true, '', '操作成功'));
        } else {
            $this->ajaxReturn(self::createReturn(false, '', $Model->getError()));
        }
    }

    public function delTemp(){
        $id = I('post.id');
        $result = M('WxappTemplate')->where(['id' => $id])->delete();
        if ($result) {
            $this->ajaxReturn(self::createReturn(true, $result, 'ok'));
        } else {
            $this->ajaxReturn(self::createReturn(false, '', '删除失败'));
        }
    }

    public function send(){
        $id = I('get.id');
        $data = M('WxappTemplate')->where(['id' => $id])->find();
        $this->assign('data', $data);
        $this->display();
    }

    public function doSend(){
        $openid = I('post.openid');
        if(!$openid){
            $this->ajaxReturn(self::createReturn(true, null, '缺少参数：openid'));
        }
        $params = I('post.params');
        $id = I('post.id');
        $data = [];
        foreach($params as $k => $param){
            $data[$k] = ['value' => $param];
        }
        $name =M('WxappTemplate')->where(['id' => $id])->getField('name');
        $res = TemplateService::sendUniformTemplate($name, $openid, $data);
        $this->ajaxReturn($res);
    }

    public function choose(){
        $this->display();
    }
}