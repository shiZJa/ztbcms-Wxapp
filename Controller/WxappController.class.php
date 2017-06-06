<?php
namespace Wxapp\Controller;

use Common\Controller\AdminBase;

/**
 * 微信后台类
 *
 * Class WxappController
 * @package Wxapp\Controller
 */
class WxappController extends AdminBase{

    /**
     * 展示设置页面
     */
    function setting(){
        $this->display();
    }

    /**
     * 获取微信小程序参数
     */
    function getSettings(){
        //获取表字段
        $tablename = C('DB_PREFIX') . "wxapp_appinfo";
        $Model = M('wxappAppinfo');
        $fields = $Model->query("show full fields from $tablename");

        //获取记录
        $setting = $Model->find();

        //关联字段和值
        foreach ($fields as $k => $v){
            $v['value'] = $setting[$v['field']];
            $fields[$k] = $v;
        }

        $this->ajaxReturn(createReturn(true,$fields));
    }

    /**
     * 设置微信小程序参数
     */
    function doSetting(){
        $Model = M('wxappAppinfo');

        $result = $Model->data(I('post.'))->save();

        if($result){
            $this->success("设置更新成功");
        }else {
            $this->error("没有更新或者更新失败");
        }
    }
}