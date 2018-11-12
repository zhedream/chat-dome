<?php

namespace core;

use core\Route;

Request::run();

class Request {

    protected static $rawData;
    protected static $routeVar = [];
    protected static $posts = [];
    protected static $gets = [];
    protected static $allData; // 数据
    protected static $disRequest; // 控制器 注入的请求类

    static $currentRouteInfo;

    public $test = "这是Request 测试变量";

    function __construct() {
        $this->_before_construct();

        // throwE('qwe');
        $a = self::getRawData();
        self::$currentRouteInfo = Route::$currentRouteInfo;
        // dd(self::$routeVar);
        $this->_after_construct();
    }

    /**
     * 获取原始数据
     * @return mixed
     */
    public static function getRawData() {
        if (self::$rawData)
            return self::$rawData;
        else {

            self::$rawData = file_get_contents('php://input');
            return self::$rawData;
        }
    }

    public static function run() {
        // dd($_SERVER);
        self::getRouteVar();
        self::getRawData();
        self::getAll();
    }

    /**
     * @return mixed
     */
    public static function getDisRequest() {
        return self::$disRequest;
    }

    /**
     * @param mixed $disRequest
     */
    public static function setDisRequest($disRequest) {
        self::$disRequest = $disRequest;
    }

    /**
     * @return array
     */
    public function getCurrentRouteInfo(): array {
        return self::$currentRouteInfo;
    }

    public function getMethod(){
        return Route::$method;
    }


    // 钩子 函数
    protected function _before_all() {
    }

    protected function _after_call() {
    }

    protected function _before_construct() {
    }

    protected function _after_construct() {
    }

    function all() {
        return self::$allData;
    }

    /**
     * 获取数据 GET POST
     */
    public static function getAll() {
        // self::_before_all();

        if(self::$allData){
            return self::$allData;
        }
        
        self::$gets = $_GET;
        self::$posts = $_POST;
        
        if (Route::$method == 'POST') {
            $posts = self::$posts;
            $gets = self::$gets;
            $all = array_merge_recursive($gets, $posts);
            self::$allData = $all;
        } else if (Route::$method == 'GET')
        self::$allData = self::$gets;

        // self::_after_all();
    }

    /**
     * 获取路由参数
     * @return array
     */
    public static function getRouteVar() {

        if (self::$routeVar)
            return self::$routeVar;
        else {
            $path = Route::$currentRouteInfo;
            $matches2 = Route::$currentRouteVar;
            preg_match_all('/\{(.*)\}/U', $path['url'], $matches);
            foreach ($matches[1] as $key => $value) {
                self::$routeVar[$value] = $matches2[$key + 1];
            }
            return self::$routeVar;
        }

    }


    public function __get($name) {

        if (Route::$method == 'POST') {

            if (isset(self::$allData[$name]))
                return self::$allData[$name];
            return null;

        } else if (Route::$method == 'GET') {

            if (self::$allData[$name])
                return self::$allData[$name];
            return null;
        }
    }

    function setAllData($key,$val){
            self::$allData[$key] = $val;
    }
    function unsetAllData($key){
        unset(self::$allData[$key]);
    }
    function popAllData($key){
            $data = self::$allData[$key];
            unset(self::$allData[$key]);
            return $data;
    }

    /**
     * @return array
     */
    public function getPosts() {
        return self::$posts;
    }

    /**
     * @return array
     */
    public function getGets() {
        return self::$gets;
    }


}


?>