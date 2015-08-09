<?php
/**
 * Created by PhpStorm.
 * User: zmw
 * Date: 2015/8/8
 * Time: 16:56
 */

require_once dirname(dirname(__FILE__)).'/models/DBUnACKQueue.php';

class CheckUnACKQueue {

    public static function run() {
        while(true) {
            DBUnACKQueue::checkQueue();
        }
    }

}//end of class

$executor = new CheckUnACKQueue();
$executor->run();