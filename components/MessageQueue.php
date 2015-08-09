<?php
/**
 * Created by PhpStorm.
 * User: zmw
 * Date: 2015/8/8
 * Time: 15:25
 */

require_once dirname(dirname(__FILE__)).'/models/DBMessageQueue.php';

class MessageQueue {

    private static $_instance = null;

    /*
     * @brief get a instance of Message Queue,singleton
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new MessageQueue();
        }
        return self::$_instance;
    }

    /*
     * @brief add an message into Message Queue
     * @param string $imageID, the unique id of image
     * @return bool true, when success
     *         bool false, when failed
     */
    public function addMessage($imageID) {
        if (!is_string($imageID)) {
            print "type error\n";
            return false;
        }
        $res = DBMessageQueue::addMessage($imageID);
        if (false == $res) {
            print "failed to insert message into Message Queue\n";
            return false;
        } else {
            return true;
        }
    }

    /*
     * @brief get an message from Message Queue
     * @param no param
     * @return string $res, the unique id of image
     *         bool false, in case of error
     */
    public function getMessage() {
        $res = DBMessageQueue::getMessage();
        if (false == $res) {
            print "failed to get message from Message Queue\n";
            return false;
        } else {
            return $res;
        }
    }

    /*
     * @brief delete an message from Message Queue
     * @param string $imageId, the unique id of image
     * @ return bool true, when success
     *          bool false, when failed
     */
    public function delMessage($imageId) {
        if (is_string($imageId)) {
            print "type error\n";
            return false;
        }
        $res = DBMessageQueue::delMessage($imageId);
        if (false == $res) {
            print "failed to delete message from Message Queue\n";
            return false;
        } else {
            return true;
        }
    }

}//end of class