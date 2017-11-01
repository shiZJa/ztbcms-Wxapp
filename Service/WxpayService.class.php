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
    const CERT_PATH = SITE_PATH . 'cert/apiclient_cert.pem';
    const KEY_PATH = SITE_PATH . 'cert/apiclient_key.pem';

    /**
     * 退款请求
     *
     * @param $appid
     * @param $out_trade_no
     * @param $out_refund_no
     * @param $total_fee
     * @param $refund_fee
     * @return array
     */
    public function refund($appid, $out_trade_no, $out_refund_no, $total_fee, $refund_fee) {
        $appInfo = CappinfoService::getAppInfo($appid)['data'];
        $data = [
            'appid' => $appInfo['appid'],
            'mch_id' => $appInfo['mch_id'],
            'nonce_str' => md5(time()),
            'out_trade_no' => $out_trade_no,
            'out_refund_no' => $out_refund_no,
            'total_fee' => $total_fee,
            'refund_fee' => $refund_fee,
        ];
        $data['sign'] = Util::sign($data, $appInfo['key']);
        $http = new HttpUtil();
        $post_res = $http->http_post_ssl('https://api.mch.weixin.qq.com/secapi/pay/refund', Util::arrayToXml($data),
            self::CERT_PATH, self::KEY_PATH);
        $res = Util::xmlToArray($post_res);
        self::updateWxpayRefundInfo($res);
        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS') {
            //退款成功
            return self::createReturn(true, $res, '退款成功');
        } else {
            return self::createReturn(false, $res, $res['return_msg']);
        }
    }

    public function pay_notify($callback) {
        $request = file_get_contents("php://input");
        $res = Util::xmlToArray($request);
        file_put_contents(C('UPLOADFILEPATH') . 'request.txt', json_encode($res));
        if ($res['result_code'] == 'SUCCESS' && $res['return_code'] == 'SUCCESS') {
            $sign = $res['sign'];
            unset($res['sign']);
            $appInfo = CappinfoService::getAppInfo($res['appid'])['data'];
            $local_sign = Util::sign($res, $appInfo['key']);
            if ($local_sign == $sign) {
                //签名成功
                self::updateWxpayOrderInfo($res);
                $callback($res);
                $return = ['return_code' => 'SUCCESS', 'return_msg' => 'ok'];
            } else {
                $return = ['return_code' => 'FAIL', 'return_msg' => '签名错误'];
            }
            file_put_contents(C('UPLOADFILEPATH') . 'res_xml.txt', json_encode($return));
            $res_xml = Util::arrayToXml($return);
            echo $res_xml;
        }
    }

    /**
     * 获取微信支付的前端调用配置
     *
     * @param        $appid        小程序appid
     * @param        $openid       用户openid
     * @param        $out_trade_no 订单交易单号
     * @param        $total_fee    订单价格，单位分
     * @param        $notify_url   回调地址
     * @param string $body         交易简介
     * @return array
     */
    static function getWxpayConfig($appid, $openid, $out_trade_no, $total_fee, $notify_url, $body = '小程序微信支付') {
        $order_res = self::createOrder($appid, $openid, $out_trade_no, $total_fee, $notify_url, $body);
        if ($order_res['status']) {
            $appInfo = CappinfoService::getAppInfo($appid)['data'];
            if (!$appInfo['key']) {
                //如果没有支付的秘钥，证明商家没有设置微信支付
                return ['status' => false, 'data' => [], 'code' => 501, 'msg' => '商家没有微信支付'];
            }
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
     * @param        $appid        小程序appid
     * @param        $openid       用户openid
     * @param        $out_trade_no 订单交易单号
     * @param        $total_fee    订单价格，单位分
     * @param        $notify_url   回调地址
     * @param string $body
     *                             交易简介
     * @return array
     */
    static function createOrder($appid, $openid, $out_trade_no, $total_fee, $notify_url, $body = '小程序微信支付') {
        $appInfo = CappinfoService::getAppInfo($appid)['data'];
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

    static function updateWxpayRefundInfo($data) {
        $is_exist = M('WxappPayRefund')->where(['out_refund_no' => $data['out_refund_no']])->find();
        if ($is_exist) {
            //如果存在
            $res = M('WxappPayRefund')->where(['id' => $is_exist['id']])->save($data);
        } else {
            $res = M('WxappPayRefund')->add($data);
        }
        if ($res) {
            return self::createReturn(true, '', '');
        } else {
            return self::createReturn(false, '', '');
        }
    }

    static function updateWxpayOrderInfo($data) {
        $is_exist = M('WxappPayOrder')->where(['out_trade_no' => $data['out_trade_no']])->find();
        if ($is_exist) {
            //如果存在
            $res = M('WxappPayOrder')->where(['id' => $is_exist['id']])->save($data);
        } else {
            $res = M('WxappPayOrder')->add($data);
        }
        if ($res) {
            return self::createReturn(true, '', '');
        } else {
            return self::createReturn(false, '', '');
        }
    }
}