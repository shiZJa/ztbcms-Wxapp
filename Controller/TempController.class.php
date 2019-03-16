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
            $this->ajaxReturn(self::createReturn(false, '', ''));
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
        $res = TemplateService::sendUniformTemplate($id, $openid, $data);
        if($res['status'] == 'ok'){
            $this->ajaxReturn(self::createReturn(true, null, '发送成功'));
        }else{
            $this->ajaxReturn(self::createReturn(true, null, '发送失败'));
        }
    }
}