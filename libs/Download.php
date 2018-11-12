<?php

namespace libs;

class Download{

    private static $_instance = null; // 实例化单例 对象

    private function __construct() {
        $this->_ext = $GLOBALS['config']['upload']['allow_suffix'];
    }

    private function __clone() {
    }

    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
            return self::$_instance;
        }
        return self::$_instance;
    }

}