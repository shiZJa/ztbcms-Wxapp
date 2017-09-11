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

    static function updateAppInfo($data) {
        $is_exist = M(self::TABLE_NAME)->where(['appid' => $data['appid']])->find();
        if ($is_exist) {
            $res = M(self::TABLE_NAME)->save($data);
        } else {
            $res = M(self::TABLE_NAME)->add($data);
        }
        if ($res) {
            return self::createReturn(true, $data, '更新资料成功');
        } else {
            return self::createReturn(false, $data, '没有资料更新');
        }
    }

    /**
     * 获取app消息
     *
     * @param null $appid
     * @return array
     */
    static function getAppInfo($appid = null) {
        $where = [];
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