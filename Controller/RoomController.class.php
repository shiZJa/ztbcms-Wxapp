<?php
namespace Wxapp\Controller;


use Wxapp\Service\RoomService;

class RoomController extends BaseController {
    /**
     * 创建聊天会话
     */
    public function createRoom() {
        $client_id = I('post.client_id');
        $room_name = I('post.room_name');
        $conversation_id = I('post.conversation_id');
        $res = RoomService::createRoom($client_id, $room_name, $conversation_id);
        $this->ajaxReturn($res);
    }

    public function myRoomList() {
        $client_id = $this->userInfo['openId'];
        $where = [
            'client_id' => $client_id
        ];
        $page = I('page', 1);
        $limit = I('limit', 10);
        $res = RoomService::myRoomList($where, $page, $limit, 'id desc');
        $this->ajaxReturn($res);
    }
}