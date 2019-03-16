<?php
namespace Wxapp\Service;

use System\Service\BaseService;
use Wxapp\Lib\HttpUtil;

class TemplateService extends BaseService {
    const TABLE_NAME = 'WxappTemplateFrom';

    static function updateTemplateFrom($id, $result) {
        $res = M(self::TABLE_NAME)->where(['id' => $id])->save(['result' => $result, 'send_count' => 1]);
        if ($res) {
            return self::createReturn(true, $res, 'ok');
        } else {
            return self::createReturn(false, null, '没有更新');
        }
    }

    static function getTemplateFrom($openid) {
        $where = [
            'openid' => $openid,
            'send_count' => 0
        ];
        $res = M(self::TABLE_NAME)->where($where)->find();
        if ($res) {
            return self::createReturn(true, $res, 'ok');
        } else {
            return self::createReturn(false, null, '没有form数据');
        }
    }

    static function addTemplateFrom($openid, $form_id, $from_type, $appid = '') {
        $data = [
            'openid' => $openid,
            'form_id' => $form_id,
            'from_type' => $from_type,
            'create_time' => time(),
            'appid' => $appid
        ];
        $res = M(self::TABLE_NAME)->add($data);
        if ($res) {
            return self::createReturn(true, $res, '');
        } else {
            return self::createReturn(false, '', '添加失败');
        }
    }

    /**
     * 发送模板消息
     *
     * @param        $openid           接受用户openid
     * @param        $template_id      模板消息模板id
     * @param        $form_id          发送模板消息所需的formId
     * @param        $page_url         页面跳转地址
     * @param        $data             模板详细新消息
     * @param string $color
     * @param string $emphasis_keyword 模板需要放大的关键词，不填则默认无放大
     * @param string $appid            模板需要放大的关键词，不填则默认无放大
     * @return array
     */
    static function sendTemplate(
        $openid,
        $template_id,
        $form_id,
        $page_url,
        $data,
        $color = '',
        $emphasis_keyword = '',
        $appid = null
    ) {
        $data = [
            'touser' => $openid,
            'template_id' => $template_id,
            'form_id' => $form_id,
            'page' => $page_url,
            'data' => $data,
            'color' => $color,
            'emphasis_keyword' => $emphasis_keyword
        ];
        $token_res = WxappService::getAccessToken($appid);
        if ($token_res['status']) {
            $token = $token_res['data'];
            $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $token;
            $http = new HttpUtil();
            $res = json_decode($http->http_post($url, $data, true), 1);
            if ($res['errcode'] == 0) {
                //发送成功
                return self::createReturn(true, $res, '发送成功');
            } elseif ($res['errcode'] == '41029') {
                return self::createReturn(false, $res, '已经发送，请注意查收通知');
            } else {
                return self::createReturn(false, $res, '发送失败，请联系管理员');
            }
        } else {
            return $token_res;
        }
    }

    /**
     * 下发小程序和公众号统一的服务消息
     *
     * @param string $name          wxapp_template表name
     * @param string $openid
     * @param array $data
     * @param string $wxapp_url     跳转到小程序的地址
     * @param string $web_url       跳转到网页地址
     * @return mixed
     */
    static function sendUniformTemplate($name, $openid, $data, $wxapp_url = '', $web_url = ''){
        $temp = M('WxappTemplate')->where(['name' => $name])->find();

        $token_res = WxappService::getAccessToken($temp['appid']);
        if($token_res['status']){
            $token = $token_res['data'];
            $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/uniform_send?access_token=' . $token;
            $http = new HttpUtil();

            $appid = M('WxappAppinfo')->where(['id' => $temp['appinfo_id']])->getField('appid');
            if($temp['type'] == 1){
                $mp_appid = M('WxappAppinfo')->where(['id' => $temp['appinfo_id']])->getField('office_appid');
                if($mp_appid){
                    //发送公众号模板消息
                    $mp_template_msg = [
                        'appid' => $mp_appid,
                        'template_id' => $temp['template_id'],
                        'url' => $web_url,
                        'miniprogram' => [
                            'appid' => $appid,
                            'pagepath' => $wxapp_url
                        ],
                        'data' => $data
                    ];

                    $post_data = [
                        'touser' => $openid,
                        'mp_template_msg' => $mp_template_msg,
                    ];
                    $res = json_decode($http->http_post($url, $post_data, true), 1);
                }
            }else{
                $form_id = M('WxappTemplateFrom')->where(['appid' => $appid, 'openid' => $openid, 'send_count' => 0])->order('`create_time` DESC')->getField('form_id');
                if($form_id){
                    //发送小程序服务通知
                    $weapp_template_msg = [
                        'template_id' => $temp['template_id'],
                        'page' => $wxapp_url,
                        'form_id' => $form_id,
                        'data' => $data,
                        'emphasis_keyword' => '', //放大关键词: keyword1.DATA
                    ];
                    $post_data = [
                        'touser' => $openid,
                        'weapp_template_msg' => $weapp_template_msg,
                        'mp_template_msg' => []
                    ];
                    $res = json_decode($http->http_post($url, $post_data, true), 1);

                    if($res['errcode'] == 0){
                        M('WxappTemplateFrom')->where(['appid' => $appid, 'openid' => $openid, 'form_id' => $form_id])->setInc('send_count');
                    }
                }
            }

            if($res && $res['errcode'] == 0){
                //发送成功
                return self::createReturn(true, null, '发送成功');
            }else{
                return self::createReturn(false, $res, '发送失败');
            }
        }else{
            return $token_res;
        }
    }
}
