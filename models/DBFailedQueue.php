<?php
/**
 * Created by PhpStorm.
 * User: zmw
 * Date: 2015/8/8
 * Time: 14:33
 */

require_once dirname(dirname(__FILE__)).'/components/DBConnect.php';
require_once dirname(dirname(__FILE__)).'/configs/KeyNamespace.php';

class DBFailedQueue {
    /*
     * @brief get an message from failed queue
     * @param no param
     * @return $ret,image id
     *          bool false, in case of any error
     */
    public function getMessage() {
        $redis = DBConnect::getRedisServer();
        $ret = $redis->blPop(KeyNamespace::FAILED_QUEUE,10);
        if (false == $ret) {
            print "failed to get message from failed queue\n";
            return false;
        }
        return $ret;
    }

    /*
     * @brirf delete message fomr failed queue
     * @param $imageId
     * @return bool true,when success
     *              false, when failed
     */
    public function delMessage($imageId) {
        $redis = DBConnect::getRedisServer();
        $ret = $redis->lRem(KeyNamespace::FAILED_QUEUE, $imageId, 1);
        if (false == $ret) {
            print "failed to delete message from failed queue\n";
            return false;
        }
        return true;
    }
}