<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Service;

use System\Service\BaseService;

class CappinfoService extends BaseService {

    const TABLE_NAME = 'WxappAppinfo';

    public function __construct() {
    }

    /**
     * 获取app消息
     *
     * @param null $appid
     * @return array
     */
    static function getAppInfo($appid = null) {
        $where=[];
        if ($appid) {
            $where['appid'] = $appid;
        }
        $res = M(self::TABLE_NAME)->where($where)->order('is_default desc')->find();
        if ($res) {
            return self::createReturn(true, $res, '');
        } else {
            return self::createReturn(false, '', '');
        }
    }
}