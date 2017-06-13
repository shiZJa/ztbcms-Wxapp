<?php
namespace Wxapp\Service;

use Shop\Service\BaseService;
use Wxapp\Helper\Util;
use Wxapp\Lib\HttpUtil;

/**
 * Class WxpayService
 *
 * @package Wxapp\Service
 */
class WxpayService extends BaseService {

    public function pay_notify() {
        $post = I('post.');
        $request = file_get_contents("php://input");
        file_put_contents(C('UPLOADFILEPATH') . 'post.txt', json_encode($post));
        file_put_contents(C('UPLOADFILEPATH') . 'request.txt', json_encode($request));
    }

    /**
     * 获取微信支付的前端调用配置
     *
     * @param        $openid       用户openid
     * @param        $out_trade_no 订单交易单号
     * @param        $total_fee    订单价格，单位分
     * @param        $notify_url   回调地址
     * @param string $body         交易简介
     * @return array
     */
    static function getWxpayConfig($openid, $out_trade_no, $total_fee, $notify_url, $body = '小程序微信支付') {
        $order_res = self::createOrder($openid, $out_trade_no, $total_fee, $notify_url, $body);
        if ($order_res['status']) {
            $app = new CappinfoService();
            $appInfo = $app->select_cappinfo();
            $prepay_id = $order_res['data'];
            $data = [
                'appId' => $appInfo['appid'],
                'timeStamp' => time(),
                'nonceStr' => md5(time()),
                'package' => 'prepay_id=' . $prepay_id,
                'signType' => 'MD5'
            ];
            $data['paySign'] = Util::sign($data, $appInfo['key']);

            return self::createReturn(true, $data, 'ok');
        } else {
            return $order_res;
        }
    }

    /**
     * 创建微信支付预支付订单
     *
     * @param        $openid       用户openid
     * @param        $out_trade_no 订单交易单号
     * @param        $total_fee    订单价格，单位分
     * @param        $notify_url   回调地址
     * @param string $body
     *                             交易简介
     * @return array
     */
    static function createOrder($openid, $out_trade_no, $total_fee, $notify_url, $body = '小程序微信支付') {
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
            'notify_url' => $notify_url,
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