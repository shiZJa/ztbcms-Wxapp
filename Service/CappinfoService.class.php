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
        //$insert_sql = 'insert into cAppinfo SET appid = "' . $params['appid'] . '",secret = "' . $params['secret'] . '",qcloud_appid = "' . $params['qcloud_appid'] . '",ip="' . $params['ip'] . '"';
        // $mysql_insert = new mysql_db();
        // return $mysql_insert->query_db($insert_sql);
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
//        $update_sql = 'update cAppinfo set login_duration = ' . $params['login_duration'] . ',session_duration=' . $params['session_duration'] . ',$secret = "' . $params['secret'] . '" where appid = "' . $params['appid'] . '"';
//        $mysql_update = new mysql_db();
//
//        return $mysql_update->query_db($update_sql);
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
//        $delete_sql = 'delete from cAppinfo';
//        $mysql_delete = new mysql_db();
//
//        return $mysql_delete->query_db($delete_sql);
    }


    /**
     * @return array|bool
     */
    public function select_cappinfo() {
        /*
        $select_sql = 'select * from cAppinfo';
         $mysql_select = new mysql_db();
         $result = $mysql_select->select_db($select_sql);
         if ($result !== false && !empty($result)) {
             $arr_result = array();
             while ($row = mysql_fetch_array($result)) {
                 $arr_result['appid'] = $row['appid'];
                 $arr_result['secret'] = $row['secret'];
                 $arr_result['login_duration'] = $row['login_duration'];
                 $arr_result['session_duration'] = $row['session_duration'];
                 $arr_result['qcloud_appid'] = $row['qcloud_appid'];
                 $arr_result['ip'] = $row['ip'];
             }

             return $arr_result;
         } else {
             return false;
         }
        */
        return M(self::TABLE_NAME)->find();
    }

    static function getAppInfo() {
        $res = M(self::TABLE_NAME)->find();
        if ($res) {
            return self::createReturn(true, $res, '');
        } else {
            return self::createReturn(false, '', '');
        }
    }
}