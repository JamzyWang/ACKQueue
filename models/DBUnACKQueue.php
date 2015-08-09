<?php
/**
 * Created by PhpStorm.
 * User: zmw
 * Date: 2015/8/8
 * Time: 14:33
 */

require_once dirname(dirname(__FILE__)).'/components/DBConnect.php';
require_once dirname(dirname(__FILE__)).'/configs/UnACKQueueConfig.php';
require_once dirname(dirname(__FILE__)).'/configs/KeyNamespace.php';

class DBUnACKQueue {

    /*
     * @brief check the unackqueue, if the time is out the limit ,then move message to failed message
     * @param no param
     * @return no return
     */
    public static function checkQueue() {
        $redis = DBConnect::getRedisServer();
        $messageArray = $redis->lRange(KeyNamespace::UN_ACK_QUEUE, 0, -1);
        foreach ($messageArray as $message) {
            $executeTime = substr($message, strlen(KeyNamespace::UN_ACK_QUEUE)+1, strlen($message));
            $lastTime = time() - (integer) $executeTime;
            if ($lastTime > UnACKQueueConfig::TIME_INTERVAL) {
                //ignore the return
                DBUnACKQueue::moveMessageToFailedQueue($message);
            }
        }
    }

    /*
     * @brief move message to failed queue
     * @param string $message
     * @return bool true, when success
     *              false, when failed
     */
    public static function moveMessageToFailedQueue($message) {
        $redis = DBConnect::getRedisServer();
        $ret = $redis->multi()
            ->lPush(KeyNamespace::FAILED_QUEUE, $message)
            ->lRem(KeyNamespace::UN_ACK_QUEUE, $message, 1)
            ->exec();
        if ( (false ===$ret[0]) || (false === $ret[1]) ) {
            print "failed to move message to failed queue\n";
            return false;
        }
        return true;
    }

    /*
     * @brief confirm message:delete message from unackqueue
     * @param string $message
     * @return bool false, when failed
     *              true, when success
     */
    public static function ackMessage($message) {

        $redis = DBConnect::getRedisServer();
        $ret = $redis->lRem(KeyNamespace::UN_ACK_QUEUE, $message, 1);
        if (false == $ret) {
            print "failed to ackMessage\n";
            return false;
        }
        return true;
    }

}//end of calss