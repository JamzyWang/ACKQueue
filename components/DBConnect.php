<?php
/**
 * Created by PhpStorm.
 * User: zmw
 * Date: 2015/8/8
 * Time: 14:59
 */


require_once dirname(dirname(__FILE__)).'/configs/DBConnectConfig.php';

class DBConnect {

    private static $_instance = null;

    public static function getRedisServer() {
        if (null === self::$_instance) {
            self::$_instance = new Redis();
            self::$_instance->connect(DBConnectConfig::HOST, DBConnectConfig::PORT);
        }
        return self::$_instance;
    }

    public static function resetRedisServer() {

    }

    public static function closeRedisServer() {
        self::$_instance->close();
    }
}