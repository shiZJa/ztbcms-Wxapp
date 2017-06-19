<?php
namespace Wxapp\Service;

use System\Service\BaseService;
use Wxapp\Lib\HttpUtil;

class TemplateService extends BaseService {
    const TABLE_NAME = 'WxappTemplateFrom';

    static function addTemplateFrom($openid, $from_id, $from_type) {
        $data = [
            'openid' => $openid,
            'from_id' => $from_id,
            'from_type' => $from_type,
            'create_time' => time()
        ];
        $res = M(self::TABLE_NAME)->add($data);
        if ($res) {
            return self::createReturn(true, $res, '');
        } else {
            return self::createReturn(false, '', '添加失败');
        }
    }

    static function sendTemplate($openid, $template_id, $from_id, $page_url, $data, $color = '', $emphasis_keyword) {
        $data = [
            'touser' => $openid,
            'template_id' => $template_id,
            'from_id' => $from_id,
            'page' => $page_url,
            'data' => $data,
            'color' => $color,
            'emphasis_keyword' => $emphasis_keyword
        ];

        $token_res = WxappService::getAccessToken();
        if ($token_res['status']) {
            $token = $token_res['data'];
            $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $token;
            $http = new HttpUtil();
            return json_decode($http->http_post($url, $data),1);
        } else {
            return $token_res;
        }
    }
}
