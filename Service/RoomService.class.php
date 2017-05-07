<?php
namespace Wxapp\Service;

use System\Service\BaseService;

class RoomService extends BaseService {
    const TABLE_NAME = 'WxappRoom';

    const IS_MASTER = 1;
    const IS_NO_MASTER = 0;

    /**
     * 加入聊天室操作
     *
     * @param $client_id
     * @param $room_id
     * @return array
     */
    static function joinRoom($client_id, $room_id) {
        $room = M(self::TABLE_NAME)->where(['id' => $room_id])->find();
        if ($room) {
            $conversation_id = $room['conversation_id'];
            $where = [
                'clinet_id' => $client_id,
                'conversation_id' => $conversation_id
            ];
            $is_exist = M(self::TABLE_NAME)->where($where)->find();
            $data = [
                'client_id' => $client_id,
                'conversation_id' => $conversation_id,
                'room_name' => $room['room_name'],
            ];

            if ($is_exist) {
                $data['update_time'] = time();
                $res = M(self::TABLE_NAME)->where(['id' => $is_exist['id']])->save($data);
            } else {
                $data['create_time'] = time();
                $res = M(self::TABLE_NAME)->add($data);
            }
            if ($res) {
                return self::createReturn(true, $room, $res);
            } else {
                return self::createReturn(false, '', '加入失败');
            }

        } else {
            return self::createReturn(false, '', '没有查到该聊天室');
        }
    }

    /**
     * 获取聊天室列表
     *
     * @param     $where
     * @param int $page
     * @param int $limit
     * @param     $order
     * @return array
     */
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
     * 创建聊天室
     *
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
            'master' => self::IS_MASTER
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

    /**
     * 后去聊天室成员
     *
     * @param $conversation_id
     * @return array
     */
    static function getRoomUsers($conversation_id) {
        $clients = M(self::TABLE_NAME)->field('client_id')->where(['conversation_id' => $conversation_id])->select();
        $ids = [];
        foreach ($clients as $key => $val) {
            $ids[] = $val['client_id'];
        }
        $res = M(UserinfoService::TABLE_NAME)->where(['open_id' => ['in', $ids]])->select();

        return self::createReturn(true, $res ? $res : [], '');
    }
}