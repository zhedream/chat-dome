<?php

namespace core;

use \Predis\Client as redis;

RD::getRD();

class RD {

    private static $_redis = null;
    private static $_instance = null;

    private function __construct() {
        self::client();
    }

    private function __clone() {
    }

    private static function client() {
        if (self::$_redis === NULL) {
            // echo '<br>client<br>';
            // $conf = (include ROOT."Config/config.php")['_redis'];
            $conf = $GLOBALS['config']['redis'];

            try {
                return self::$_redis = new redis([
                    'scheme' => $conf['scheme'],
                    'host' => $conf['host'],
                    'port' => $conf['port'],
                ]);
            } catch (PDOException $e) {
                echo "_redis连接失败！" . $e->getMessage();
            }
        }
        return 1;
    }

    /**
     * 获取_redis对象
     */
    public static function getRD() {
        if (self::$_redis === NULL)
            self::client();
        return self::$_redis;
    }

    public static function getInstance(){
        if (self::$_instance === NULL){
            self::$_instance = new self;
            return self::$_instance;
        }
        return self::$_instance;
    }

    /**
     * _redis String 缓存
     * 1.键
     * 2.过期时间（秒）
     * 3.值 匿名函数 return 数据
     * 4. 覆盖 返回
     * 5. json 输出
     */
    public static function chache(string $key, int $minutes, callable $call, $cover = false ,$json = false) {

        // 返回 1 0
        $key = "String:" . $key;
        if (!$cover && self::$_redis->exists($key)) {
            if(!$json)
                return json_decode(self::$_redis->get($key), true); // 存在键 则返回
            return self::$_redis->get($key); // 存在键 则返回

        }
//        dd($key);
        $data = $call();
        // dd($data);
        if($data){
            $str = json_encode($data);
            self::$_redis->setex($key, $minutes, $str);
            if(!$json)
                return json_decode(self::$_redis->get($key), true);
            return self::$_redis->get($key);
        }
        return false;
    }

    /**
     * 时效等待 等待时间过期 如发邮件 60s 只能一次
     *
     * 1. 键: 时效类型名称
     * 2. key
     * 3. 内容信息
     * 4. 时效时间 int 60
     * return 是否等待 存在则返回FLASE 不存在则设置存在返回 值
     */
    public static function waitOut(string $type, string $key, $message = true, int $minutes = 60) {

        $message = json_encode($message);
        $key = "String:timeout:" . $type . ":" . ($key);

        if (self::$_redis->exists($key)) {
            return false;
        }
        return self::$_redis->setex($key, $minutes, $message);
    }

    /**
     * 设置 消息 时效
     * 1. 消息类型 名称
     * 2. key
     * 3. 数据
     * 4. 过期时间
     */
    public static function setTimeOut(string $type, string $key, $message = true, int $minutes = 60) {

        $message = json_encode($message);
        $key = "String:timeout:" . $type . ":" . ($key);

        return self::$_redis->setex($key, $minutes, $message);

    }

    /**
     * 抓取(确认) 时效 消息
     * 1. 类型 名称
     * 2. 消息
     * 3. 是否弹出 bool
     */
    public static function getTimeOut(string $type, string $key, bool $pop = false) {

        // $message = json_encode( $message);
        $key = "String:timeout:" . $type . ":" . ($key);
        if (self::$_redis->exists($key)) {
            $data = self::$_redis->get($key);
            if ($pop)
                self::$_redis->del($key);
            return json_decode($data, true);
        }
        return false;

    }

    /**
     * 删除时效 信息
     * 1. 时效 类型
     * 2. 键
     * return
     */
    public static function delTimeOut(string $type, string $key, bool $pop = false) {
        $key = "String:timeout:" . $type . ":" . ($key);
        return self::$_redis->del($key);
    }

    /**
     * 加入_redis List 消息队列
     * 1. 键:队列名称
     * 2. 消息
     * 3. 过期时间 int -1
     * return 该键队列长度
     */
    public static function iqueue(string $key, $message, int $minutes = -1) {
        $key = "List:" . $key;
        return self::$_redis->lpush($key, json_encode($message));
    }

    /**
     * 弹出队列
     * 1. 键: 队列名称
     * return
     */
    public static function oqueue(string $key) {
        $key = "List:" . $key;
        return json_decode(self::$_redis->rpop($key), true);

    }

    /**
     * 阻塞弹出队列
     * 1. 键: 队列名称
     * return mixed
     */
    public static function boqueue(string $key) {
        $key = "List:" . $key;
        return json_decode(self::$_redis->brpop($key, 0)[1], true);
    }

    /**
     * @param $type
     * @param $key
     * @param $val
     * @return mixed
     */
    public static function setHash($type, $key, $val){

        $hash = "Hash:" . $type;
        return self::$_redis->hset($hash,$key,$val);

    }

    /**
     * @param $type
     * @param $key
     * @return mixed
     */
    public static function getHash($type, $key){

        $hash = "Hash:" . $type;
        return self::$_redis->hget ($hash,$key);

    }

    public static function sortZSet($set){
        $data = self::$_redis->zrange($set,0,-1);
        // dd($data);
        foreach ($data as $key => $value) {
            self::$_redis->zadd($set,$key,$value);
        }
    }

    public static function delMatch($key){

        $data = self::$_redis->keys($key);

        foreach ($data as $key => $value) {
            self::$_redis->del($value);
        }
    }

}


?>