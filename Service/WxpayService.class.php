<?php
namespace Wxapp\Service;

use Shop\Service\BaseService;
use Wxapp\Helper\Util;
use Wxapp\Lib\HttpUtil;

class WxpayService extends BaseService {
    /**
     * 创建微信支付预支付订单
     *
     * @param        $openid       用户openid
     * @param        $out_trade_no 订单交易单号
     * @param        $total_fee    订单价格，单位分
     * @param string $body         交易简介
     * @return array
     */
    static function createOrder($openid, $out_trade_no, $total_fee, $body = '小程序微信支付') {
        $app = new CappinfoService();
        $appInfo = $app->select_cappinfo();
        $data = [
            'appid' => $appInfo['appid'],
            'mch_id' => $appInfo['mch_id'],
            'nonce_str' => md5(time()),
            'body' => $body,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee,
            'spbill_create_ip' => get_client_ip(),
            'notify_url' => U('index'),
            'trade_type' => 'JSAPI',
            'openid' => $openid,
        ];
        $data['sign'] = Util::sign($data, $appInfo['key']);
        $http = new HttpUtil();

        $res = Util::xmlToArray($http->http_post('https://api.mch.weixin.qq.com/pay/unifiedorder',
            Util::arrayToXml($data)));
        if ($res['result_code'] && $res['result_code']) {
            return self::createReturn(true, $res['prepay_id'], $res['return_msg']);
        } else {
            return self::createReturn(false, $res, $res['return_msg']);
        }
    }
}