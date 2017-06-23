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
     * @param $params
     * @return mixed
     */
    public function insert_cappinfo($params) {
        $insert_data = [
            'appid' => $params['appid'],
            'secret' => $params['secret'],
            'qcloud_appid' => $params['qcloud_appid'],
            'ip' => $params['ip']
        ];

        return M(self::TABLE_NAME)->add($insert_data);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function update_cappinfo($params) {
        $update_data = [
            'login_duration' => $params['login_duration'],
            'session_duration' => $params['session_duration'],
            'session_duration' => $params['session_duration'],
            'secret' => $params['secret']
        ];
        M(self::TABLE_NAME)->where(['appid' => $params['appid']])->save($update_data);
    }

    /**
     * @return mixed
     */
    public function delete_cappinfo() {
    }


    /**
     * @return array|bool
     */
    public function select_cappinfo() {
        return M(self::TABLE_NAME)->find();
    }

    static function getAppInfo($appid = null) {
        if ($appid) {
            $where['appid'] = $appid;
        }
        $res = M(self::TABLE_NAME)->where($where)->find();
        if ($res) {
            return self::createReturn(true, $res, '');
        } else {
            return self::createReturn(false, '', '');
        }
    }
}