<?php
namespace Wxapp\Service;

use System\Service\BaseService;
use Wxapp\Lib\HttpUtil;

class QrcodeService extends BaseService {
    /**
     * 获取小程序码，有限制的永久小程序码
     *
     * @param       $path
     * @param       $width
     * @param bool  $auto_color
     * @param array $line_color
     * @param null  $appid
     * @return string
     */
    static function getwxacode($path, $width, $auto_color = false, $line_color = [], $appid = null) {
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=" . WxappService::getAccessToken($appid)['data'];
        $data = [
            'path' => $path,
            'width' => $width,
            'auto_color' => $auto_color,
            'line_color' => $line_color
        ];
        $http = new HttpUtil();
        $res = $http->http_post($url, $data, true);

        return $res;
    }

    /**
     * 无限制小程序码
     *
     * @param       $scene
     * @param       $width
     * @param bool  $auto_color
     * @param array $line_color
     * @param null  $appid
     * @return string
     */
    static function getwxacodeunlimit($scene, $width, $auto_color = false, $line_color = [], $appid = null) {
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . WxappService::getAccessToken($appid)['data'];
        $data = [
            'scene' => $scene,
            'width' => $width,
            'auto_color' => $auto_color,
            'line_color' => $line_color
        ];
        $http = new HttpUtil();
        $res = $http->http_post($url, $data, true);

        return $res;
    }

    /**
     * 小程序有限制二维码
     *
     * @param      $path
     * @param      $width
     * @param null $appid
     * @return string
     */
    static function createwxaqrcode($path, $width, $appid = null) {
        $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=" . WxappService::getAccessToken($appid)['data'];
        $data = [
            'path' => $path,
            'width' => $width
        ];
        $http = new HttpUtil();
        $res = $http->http_post($url, $data, true);

        return $res;
    }
}