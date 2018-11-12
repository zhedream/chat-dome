<?php

namespace libs;

use core\RD;
//Uploader::getInstance();

class Upload {


    private $_root = UPLOAD_PATH;
    private $_ext;

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

    public function getExt() {
        return $this->_ext;
    }

    /**
     * 读取 服务器 文件 存储状态
     * 
     * 1. 文件md5
     * 
     * @return {
     *  1. 可以秒传 返回 url
     *  2. 可以续传 返回 存在分片 index
     *  3. 没有记录 返回 
     * 
     * }
     */
    function readyfile($md5) {
        // extract($req->all());
        $redis = RD::getRD();
        
        $exis = $redis->hexists('Hash:files:' . $md5, 'url');

        if ($exis) {

            $url = RD::getHash('files:' . $md5, 'url');
            return json_encode([
                'err' => '1',
                'msg' => '可以秒传',
                'data' => $url
            ]);

        }
        // echo 'file_slices:' . $md5;
        $indexs = $redis->zrangebyscore('file_slices:' . $md5, 0, 9999, 'withscores');
        $indexs = array_values($indexs);

        if ($indexs) {

            return json_encode([
                'err' => '2',
                'msg' => '断点续传',
                'data' => $indexs
            ]);

        }

        return json_encode([
            'err' => '3',
            'msg' => '完整上传',
        ]);

    }


}


