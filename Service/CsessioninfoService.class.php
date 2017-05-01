<?php
/**
 * author: zhlhuang <zhlhuang888@foxmail.com>
 */
namespace Wxapp\Service;

use System\Service\BaseService;

class CsessioninfoService extends BaseService {
    const TABLE_NAME = 'WxappSessioninfo';

    public function __construct() {
    }


    public function insert_csessioninfo($params) {

//        $insert_sql = 'insert into cSessionInfo set uuid = "' . $params['uuid'] . '",skey = "' . $params['skey'] . '",create_time = "' . $params['create_time'] . '",last_visit_time = "' . $params['last_visit_time'] . '",open_id = "' . $params['openid'] . '",session_key="' . $params['session_key'] . '",user_info=\'' . $params['user_info'] . '\'';
//        $mysql_insert = new mysql_db();
//
//        return $mysql_insert->query_db($insert_sql);
        $insert_data = [
            'uuid' => $params['uuid'],
            'skey' => $params['skey'],
            'create_time' => $params['create_time'],
            'last_visit_time' => $params['last_visit_time'],
            'open_id' => $params['openid'],
            'session_key' => $params['session_key'],
            'user_info' => $params['user_info']
        ];

        return M(self::TABLE_NAME)->add($insert_data);
    }


    public function update_csessioninfo_time($params) {
//        $update_sql = 'update cSessionInfo set last_visit_time = "' . $params['last_visit_time'] . '" where uuid = "' . $params['uuid'] . '"';
//        $mysql_update = new mysql_db();
//
//        return $mysql_update->query_db($update_sql);
        $update_data = [
            'last_visit_time' => $params['last_visit_time']
        ];

        return M(self::TABLE_NAME)->where(['uuid' => $params['uuid']])->save($update_data);
    }


    public function update_csessioninfo($params) {
//        $update_sql = 'update cSessionInfo set session_key= "' . $params['session_key'] . '",create_time = "' . $params['create_time'] . '" ,last_visit_time = "' . $params['last_visit_time'] . '",skey = "' . $params['skey'] . '",user_info=\'' . $params['user_info'] . '\' where uuid = "' . $params['uuid'] . '"';
//        $mysql_update = new mysql_db();
//
//        return $mysql_update->query_db($update_sql);
        $update_data = [
            'session_key' => $params['session_key'],
            'create_time' => $params['create_time'],
            'last_visit_time' => $params['last_visit_time'],
            'skey' => $params['skey'],
            'user_info' => $params['user_info'],
        ];

        return M(self::TABLE_NAME)->where(['uuid' => $params['uuid']])->save($update_data);
    }


    public function delete_csessioninfo($open_id) {
//        $delete_sql = 'delete from cSessionInfo where open_id = "' . $open_id . '"';
//        $mysql_delete = new mysql_db();
//
//        return $mysql_delete->query_db($delete_sql);
        return M(self::TABLE_NAME)->where(['open_id' => $open_id])->delete();
    }


    public function delete_csessioninfo_by_id_skey($params) {
//        $delete_sql = 'delete from cSessionInfo where uuid = "' . $params['uuid'] . '"';
//        $mysql_delete = new mysql_db();
//
//        return $mysql_delete->query_db($delete_sql);
        return M(self::TABLE_NAME)->where(['uuid' => $params['uuid']])->delete();
    }


    public function select_csessioninfo($params) {
        /*
        $select_sql = 'select * from cSessionInfo where uuid = "' . $params['uuid'] . '" and skey = "' . $params['skey'] . '"';
            $mysql_select = new mysql_db();
            $result = $mysql_select->select_db($select_sql);
            if ($result !== false && !empty($result)) {
                $arr_result = array();
                while ($row = mysql_fetch_array($result)) {
                    $arr_result['id'] = $row['id'];
                    $arr_result['uuid'] = $row['uuid'];
                    $arr_result['skey'] = $row['skey'];
                    $arr_result['create_time'] = $row['create_time'];
                    $arr_result['last_visit_time'] = $row['last_visit_time'];
                    $arr_result['open_id'] = $row['open_id'];
                    $arr_result['session_key'] = $row['session_key'];
                    $arr_result['user_info'] = $row['user_info'];
                }

                return $arr_result;
            } else {
                return false;
            }
        */
        return M(self::TABLE_NAME)->field('id,uuid,skey,create_time,last_visit_time,open_id,session_key,user_info')->where([
            'uuid' => $params['uuid'],
            'skey' => $params['skey']
        ])->find();
    }


    public function get_id_csessioninfo($open_id) {
        /*
          $select_sql = 'select uuid from cSessionInfo where open_id = "' . $open_id . '"';
         $mysql_select = new mysql_db();
         $result = $mysql_select->select_db($select_sql);
         if ($result !== false && !empty($result)) {
             $id = false;
             while ($row = mysql_fetch_array($result)) {
                 $id = $row['uuid'];
             }

             return $id;
         } else {
             return false;
         }
        */
        $res = M(self::TABLE_NAME)->field('id,uuid,open_id')->where([
            'open_id' => $open_id,
        ])->find();
        if ($res) {
            return $res['uuid'];
        } else {
            return false;
        }
    }


    public function check_session_for_login($params) {
        $result = M(self::TABLE_NAME)->where(['open_id' => $params['open_id']])->find();
        if ($result) {
            $create_time = strtotime($result['create_time']);
            if (!$create_time) {
                return false;
            } else {
                $now_time = time();
                if (($now_time - $create_time) / 86400 > $params['login_duration']) {
                    //$this->update_csessioninfo($params);
                    return true;
                } else {
                    return true;
                }
            }
        } else {
            return true;
        }
    }


    public function check_session_for_auth($params) {
        $result = $this->select_csessioninfo($params);
        if (!empty($result) && $result !== false && count($result) != 0) {
            $now_time = time();
            $create_time = strtotime($result['create_time']);
            $last_visit_time = strtotime($result['last_visit_time']);
            if (($now_time - $create_time) / 86400 > $params['login_duration']) {
                //$this->delete_csessioninfo_by_id_skey($params);
                return false;
            } else {
                if (($now_time - $last_visit_time) > $params['session_duration']) {
                    return false;
                } else {
                    $params['last_visit_time'] = date('Y-m-d H:i:s', $now_time);
                    $this->update_csessioninfo_time($params);

                    return $result['user_info'];
                }
            }
        } else {
            return false;
        }
    }


    public function change_csessioninfo($params) {
        if ($this->check_session_for_login($params)) {
            $uuid = $this->get_id_csessioninfo($params['openid']);
            if ($uuid) {
                $params['uuid'] = $uuid;
                $res = $this->update_csessioninfo($params);
                if ($res) {
                    return $uuid;
                } else {
                    return false;
                }
            } else {
                return $this->insert_csessioninfo($params);
            }
        } else {
            return false;
        }
    }
}