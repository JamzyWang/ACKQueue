<?php
/**
 * Created by PhpStorm.
 * User: zmw
 * Date: 2015/8/8
 * Time: 14:32
 */

require_once dirname(dirname(__FILE__)).'/components/DBConnect.php';
require_once dirname(dirname(__FILE__)).'/configs/KeyNamespace.php';
require_once dirname(dirname(__FILE__)).'/configs/UnACKQueueConfig.php';

class DBMessageQueue {

/*
 * @brief add an message(image id) into message list
 * @param $imageId, the unique image id
 * @return bool true, when success
 *              false,in case of fail
 */
  public static function addMessage($imageId) {
      //这里可以根据imageId的值生成别的ID
      $redis = DBConnect::getRedisServer();
      $ret = $redis->lPush(KeyNamespace::MESSAGE_QUEUE, $imageId);
      if (false === $ret) {
          print "failed insert into Message Queue\n";//生产环境中这里可以用来打印日志
          return false;
      }
      return true;
    }

  /*
   * @brief get an message from message list and insert it into un_ack_queue
   * @param no param
   * @return string $res, the image id
   *         bool false, when failed
   */
  public static function getMessage() {
      $redis = DBConnect::getRedisServer();
      $length = $redis->lLen(KeyNamespace::MESSAGE_QUEUE);
      if (0 === $length) {
          return false;
      }
      $imageIdArray = $redis->lRange(KeyNamespace::MESSAGE_QUEUE, $length-1, $length-1);//return an array
      $imageId = $imageIdArray[0];
      $executeTime = (string)time();
      $ackImageID = $imageId . ":" . $executeTime ;
      $redis->lPush(KeyNamespace::UN_ACK_QUEUE, $ackImageID);
      $redis->lRem(KeyNamespace::MESSAGE_QUEUE, $imageId, 1);

      return $ackImageID;
    }

    public static function getFirstMessage() {
        $redis = DBConnect::getRedisServer();
        $length = $redis->lLen(KeyNamespace::MESSAGE_QUEUE);
        if (0 === $length) {
            return false;
        }
        $imageIdArray = $redis->lRange(KeyNamespace::MESSAGE_QUEUE, $length-1, $length-1);//return an array
        $imageId = $imageIdArray[0];
        return $imageId;
    }
  /*
   * @brief delete an message from message list
   * @param $imageId, the unique image id
   * @return bool true, when success
   *         bool false, when failed
   */
   public static function delMessage($imageId) {
       $redis = DBConnect::getRedisServer();
       $ret = $redis->lRem(KeyNamespace::MESSAGE_QUEUE, $imageId, 1);
       if (1 === $ret) {
           return true;
       }
       if (false == $ret) {
           print "failed to delete message from message list\n";
           return false;
       }
    }
/*
 * @brief get the length of message list
 * @param no param
 * @return int $res, length of message list
 *         bool false, when failed
 */
    public static function getMessageQueueLen() {
        $redis = DBConnect::getRedisServer();
        $ret = $redis->lSize(KeyNamespace::MESSAGE_QUEUE);
        if (false === $ret) {
            print "failed to get message list length \n";
            return false;
        }
        return $ret;
    }
}
