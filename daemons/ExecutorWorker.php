<?php
/**
 * Created by PhpStorm.
 * User: zmw
 * Date: 2015/8/8
 * Time: 20:22
 */

require_once dirname(dirname(__FILE__)).'/components/MessageQueue.php';
require_once dirname(dirname(__FILE__)).'/models/DBUnACKQueue.php';

class ExecutorWorker {

    public static function run() {
        while(true) {
            $messageQueue = MessageQueue::getInstance();
            $message = $messageQueue->getMessage();
            if (false == $message) {
                print "no message in message queue\n";
                sleep(20);
            }


            /*
             * Write code here to process the $message
             *
             *
             *
             *
             *
             *
             *
             *
             *
             */


            /*
             * When finish process the $message,,ack the message
             */
            $ret = DBUnACKQueue::ackMessage($message);
            if (false == $ret) {
                print "failed to ack message: $message\n";
                sleep(10);
            } else {
                print "finish ack: $message\n";
            }
        }
    }

}//end of class

$executor = new ExecutorWorker();
$executor->run();


