<?php
namespace Wxapp\Helper;

class Util {
    private static $postPayload = null;

    public static function getHttpHeader($headerKey) {
        $headerKey = strtoupper($headerKey);
        $headerKey = str_replace('-', '_', $headerKey);
        $headerKey = 'HTTP_' . $headerKey;

        return isset($_SERVER[$headerKey]) ? $_SERVER[$headerKey] : '';
    }

    public static function writeJsonResult($obj, $statusCode = 200) {
        header('Content-type: application/json; charset=utf-8');

        http_response_code($statusCode);
        echo json_encode($obj, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

        Logger::debug("Util::writeJsonResult => [{$statusCode}]", $obj);
    }

    public static function getPostPayload() {
        if (is_string(self::$postPayload)) {
            return self::$postPayload;
        }

        return file_get_contents('php://input');
    }

    public static function setPostPayload($payload) {
        self::$postPayload = $payload;
    }

    //数组转XML
    public static function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";

        return $xml;
    }

    //将XML转为array
    public static function xmlToArray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $values;
    }

    public static function sign($data, $sign_key) {
        ksort($data);
        $str = '';
        foreach ($data as $key => $value) {
            $str .= $key . "=" . $value . '&';
        }
        $str .= 'key=' . $sign_key;

        return strtoupper(md5($str));
    }
}
