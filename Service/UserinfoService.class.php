<?php
namespace Wxapp\Service;

use System\Service\BaseService;

class UserinfoService extends BaseService {
    const TABLE_NAME = 'WxappUserinfo';

    /**
     * 更新用户信息
     *
     * @param      $info
     * @param null $appid
     * @return array
     */
    static function updateInfo($info, $appid = null) {
        $data = [
            'open_id' => $info['openId'],
            'nick_name' => $info['nickName'],
            'gender' => $info['gender'],
            'language' => $info['language'],
            'city' => $info['city'],
            'province' => $info['province'],
            'country' => $info['country'],
            'avatar_url' => $info['avatarUrl'],
            'appid' => $appid
        ];

        $is_exist = M(self::TABLE_NAME)->where(['open_id' => $data['open_id']])->find();
        if ($is_exist) {
            $data['update_time'] = time();
            $res = M(self::TABLE_NAME)->where(['id' => $is_exist['id']])->save($data);
        } else {
            $data['create_time'] = time();
            $res = M(self::TABLE_NAME)->add($data);
        }

        return self::createReturn(true, $res, '');
    }
}