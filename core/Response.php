<?php

namespace core;


class Response {

    private static $_instance = null; // 实例化单例 对象

    public static $errInputMessages = [];

    private function __construct() {
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

    /**
     * @param $filePath
     * @param $fileName
     */
    function download($filePath, $fileName) {
        // 告诉浏览器这是一个二进程文件流
        Header("Content-Type: application/octet-stream");
        // 请求范围的度量单位
        Header("Accept-Ranges: bytes");
        // 告诉浏览器文件尺寸
        Header("Accept-Length: " . filesize($filePath));
        // 开始下载，下载时的文件名
        Header("Content-Disposition: attachment; filename=" . $fileName);

        // 读取服务器上的一个文件并以文件流的形式输出给浏览器
        readfile($filePath);
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @param int $expire
     */
    function WithCookie($name, $value, $expire = 60, $path = '/') {
        $conf = config('cookie');
//        dd($conf);
        return setcookie($name, $value, time() + $expire, $conf['path'], $conf['domain'], $conf['secure']);
    }

    function getInputErrs(){
        return self::$errInputMessages;
    }
    function setInputErrs($data){
        self::$errInputMessages = $data;
    }


}


?>