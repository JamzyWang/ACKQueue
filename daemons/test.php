<?php
/**
 * Created by PhpStorm.
 * User: zmw
 * Date: 2015/8/8
 * Time: 20:46
 */

require_once dirname(dirname(__FILE__)).'/components/MessageQueue.php';

$messageQueue = MessageQueue::getInstance();

for ($i=0; $i<100; $i++) {
    $message = uniqid();
    $ret = $messageQueue->addMessage($message);
    if (false == $ret) {
        print $message;
        print " insert failed\n";
    } else {
        print $message;
        print " success \n";
    }
}
