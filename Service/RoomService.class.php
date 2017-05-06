<?php
namespace Wxapp\Service;

use System\Service\BaseService;

class RoomService extends BaseService {
    const TABLE_NAME = 'WxappRoom';

    static function myRoomList($where, $page = 1, $limit = 10, $order) {
        $lists = M(self::TABLE_NAME)->where($where, $page, $limit)->order($order)->select();
        $total = M(self::TABLE_NAME)->where($where)->count();
        $res = [
            'lists' => $lists ? $lists : [],
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'page_count' => ceil($total / $limit)
        ];

        return self::createReturn(true, $res, 'msg');
    }

    /**
     * @param $client_id
     * @param $room_name
     * @param $conversation_id
     * @return array
     */
    static function createRoom($client_id, $room_name, $conversation_id) {
        $is_exist = M(self::TABLE_NAME)->where(['conversation_id' => $conversation_id])->find();
        $data = [
            'client_id' => $client_id,
            'room_name' => $room_name,
            'conversation_id' => $conversation_id,
        ];
        if ($is_exist) {
            //如果已经存在该会话
            $data['update_time'] = time();
            $res = M(self::TABLE_NAME)->where(['conversation_id' => $conversation_id])->save($data);
        } else {
            $data['create_time'] = time();
            $res = M(self::TABLE_NAME)->add($data);
        }
        if ($res) {
            return self::createReturn(true, $res, 'ok');
        } else {
            return self::createReturn(false, '', '操作失败');
        }
    }
}