<?php

namespace core;

use core\Request;
use core\Middleware; // 中间件
use core\ReflexMethod; // 自定义 反射类
use core\Reflexs\ReflexDispatchMethod; // 自定义 反射类

class Route {

    private static $self = null;

    private function __construct() {
    }

    private function __clone() {
    }

    static function getInstance() {
        if (self::$self === null)
            self::$self = new self;
        return self::$self;
    }
    
    static $csrfPass = ['alipay.notify', 'wxpay.notify','user.doavatar']; //csrf 白名单
    static $method; // 请求方式
    static $pathinfo; // 请求路径
    
    static $lastUrl; // 命名中转
    static $posts = [];
    static $gets = []; //保存GET 路由
    static $map = []; // 名称映射

    static $currentRouteInfo = []; //
    static $currentRouteVar = []; //

    static $routeName; // 请求路由 名称

    static $middlewareNames = []; // 注册的中间件

    static $startLock=false; // 开启加锁
    static $lockMap = []; // 锁映射/权限映射

    static function initDispatch() {
        // goto a; // 原始路由
        self::$method = $_SERVER['REQUEST_METHOD'];
        // self::$pathinfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        if(!isset($_SERVER['PATH_INFO']) || $_SERVER['PATH_INFO']==''){
            self::$pathinfo = '/';
        }else{
            self::$pathinfo = $_SERVER['PATH_INFO'];
        }

        list($controller,$ac) = self::getDispatch();
        // dd($controller);
        $controller = new $controller;
        
        $ref = new ReflexDispatchMethod ($controller,$ac); // 反射 方法
        // dd($ref);
        $app = function()use($ref,$controller){
            $ref->invokeArgs($controller);
        };

        self::disMiddleware($app);

    }

    /**
     * 获取 对象 与 方法
     */
    static function getDispatch(){

        // echo '<hr>';
        // echo self::$pathinfo;
        // echo '<hr>';
        // echo self::$method;
        // echo '<hr>';

        if (self::$method == 'GET') {
            // dd(self::$gets);
            foreach (self::$gets as $key => $value) {

                
                $patt = "/" . $value['patt'] . "/";
                // preg_match()
                $isMatched = preg_match($patt, self::$pathinfo, $matches);
                if ($isMatched) {
                    self::$currentRouteInfo = $value; // 路由信息
                    self::$currentRouteVar = $matches;// 路由变量
                    self::$routeName = isset($value['name']) ? $value['name'] : null;

                    return [$value['controller'],$value['action']];
                }
            }
        } else if (self::$method == 'POST') {

            foreach (self::$posts as $key => $value) {

                $patt = "/" . $value['patt'] . "/";
                $isMatched = preg_match($patt, self::$pathinfo, $matches);
                if ($isMatched) {
                    // var_dump($value,$matches);die;
                    self::$currentRouteInfo = $value; // 路由信息
                    self::$currentRouteVar = $matches;// 路由变量
                    self::$routeName = isset($value['name']) ? $value['name'] : null; // 路由名
                
                    return [$value['controller'],$value['action']];
                }
            }

        }

        view("error");
        die;
    }

    /**
     * 注册 get 路由
     * 1. 地址
     * 2. 分发控制器方法
     */
    static function get($url, $path) {
        $self = self::getInstance();

        foreach (self::$gets as $key => $value)
            if ($value['url'] == $url)
                throwE("GET路由地址( {$url} )已存在", 'get');

        list($controller, $action) = explode('@', $path);
        $controller = str_replace('/', '\\', $controller);

        $pathinfo = explode('/', $url);

        $name = str_replace('\\', '.', $controller).'.'.$action;

        // var_dump($pathinfo);
        $patt = "^";
        if (count($pathinfo) > 0 && $pathinfo[1] != "") {
            for ($i = 1; $i < count($pathinfo); $i++) {
                if (preg_match('/\{.*\}/', $pathinfo[$i], $matches))
                    $patt .= "\/(\d+)";
                else
                    $patt .= "\/" . $pathinfo[$i];
            }
            $patt .= "\/?$";
        } else {
            $patt = "^\/$";
        }

        // die;
        $lockKey = md5($url.$path);
        self::$gets[] = [
            'url' => $url,
            'controller' => $controller,
            'action' => $action,
            'name'=>$name,
            'patt' => $patt,
            'middlewares'=>self::$middlewareNames,
            'locked'=>self::$startLock,
            'lockKey'=>$lockKey,
            'lockName'=>$path,
        ];
        // return;
        // echo "<hr>";
        // echo "GET路由注册成功：url->( {$url} ),控制器->( {$controller} ),方法->( {$action}  ) 正则{$patt}<br>";
        // echo '<hr>';

        self::$lastUrl = [
            'method' => 'get',
            'url' => $url,
        ];
        // self::$lastUrl = &end(self::$gets);

        return $self;

    }

    /**
     * 注册 post 路由
     * 1. 地址
     * 2. 分发控制器方法
     */
    static function post($url, $path) {
        $self = self::getInstance();
        foreach (self::$posts as $key => $value)
            if ($value['url'] == $url)
                throwE("POST路由地址( {$url} )已存在", 'get');
        list($controller, $action) = explode('@', $path);
        $controller = str_replace('/', '\\', $controller);

        $pathinfo = explode('/', $url);
        $name = str_replace('\\', '.', $controller).'.'.$action;
        // var_dump($pathinfo);
        $patt = "^";
        if (count($pathinfo) > 0 && $pathinfo[1] != "") {
            for ($i = 1; $i < count($pathinfo); $i++) {
                if (preg_match('/\{.*\}/', $pathinfo[$i], $matches))
                    $patt .= "\/(\d+)";
                else
                    $patt .= "\/" . $pathinfo[$i];
            }

            $patt .= "\/?$";
        } else {
            $patt = "^\/$";
        }

        // die;
        $lockKey = md5($url.$path);
        self::$posts[] = [
            'url' => $url,
            'controller' => $controller,
            'action' => $action,
            'name'=>$name,
            'patt' => $patt,
            'middlewares'=>self::$middlewareNames,
            'locked'=>self::$startLock,
            'lockKey'=>$lockKey,
            'lockName'=>$path,
        ];
        // return;
        // echo "<hr>";
        // echo "POST路由注册成功：url->( {$url} ),控制器->( {$controller} ),方法->( {$action}  ) 正则{$patt}<br>";
        // echo '<hr>';

        self::$lastUrl = [
            'method' => 'post',
            'url' => $url,
        ];

        return $self;
    }

    /**
     *  加载视图
     *  参数一、加载的视图的文件名
     *  参数二、向视图中传的数据
     */
    static function view($viewFileName, $data = []) {

        view($viewFileName, $data);
    }

    /**
     * 为添加的路由 命名
     * 1. 名称
     */
    function name($name) {
        
        if(in_array($name,array_keys(self::$map))){
            throwE($name.':路由名称重复','name');
        }

        self::$map[$name] = self::$lastUrl;

        if (self::$lastUrl['method'] == 'get')
            self::$gets[count(self::$gets) - 1]['name'] = $name;
        else
            self::$posts[count(self::$posts) - 1]['name'] = $name;
        return self::getInstance();
    }

    /**
     * 生产路由 URL
     */
    function makeUrl($name, $data = [], $full = false) {

        // extract($data);
        if (!isset(self::$map[$name])) {
            throwE('不存在路由名称' . $name, 'Route'); // name -> Route
        }
        // var_dump( self::$map[$name]);
        $url = self::$map[$name]['url'];

        foreach ($data as $key => $val) {

            $patt = "/" . "\{$key\}" . "/";
            $url = preg_replace($patt, $val, $url);
        }

        if (preg_match('/\{.*\}/', $url, $matches)) {
            // dd($matches);
            throwE('请检查路由参数,存在未解析的路由参数'.$matches[0], 'Route');
        }

        if ($full) {
            $app_url = $GLOBALS['config']['APP_URL'];
            return $app_url . $url;
        }
        return ($url);

    }

    /**
     * 获取当前路由的名称  五路由返回false
     */
    function getRouteName(){
        var_dump(self::$map);
    }

    /**
     * 执行中间件 处理请求
     */
    static function disMiddleware($app){
        $middleware = new Middleware;
        $middleware->run($app);
    }

    /**
     * 处理数据
     */
    function disRequest(){

    }

    /**
     * 注册路由中间件
     */
    public static function middleware(){

        $args = func_get_args();
        // dd($args);
        $num = func_num_args();
        if ($num == 1) {

            if (is_string($args[0]))
                return call_user_func_array([__NAMESPACE__ . '\Route', "middlewareSingle"], $args);
        }else if($num == 2){

            if (is_array($args[0]) && is_callable($args[1]))
                return call_user_func_array([__NAMESPACE__ . '\Route', "middlewareMult"], $args);
        }

    }

    static function middlewareSingle($name){
        $mid = new Middleware;
        // echo '123';die;
        if (self::$lastUrl['method'] == 'get'){
            $index = count(self::$gets) - 1;
            self::$gets[$index]['middlewares'][] = $name;
        }
        else{
            $index = count(self::$posts) - 1;
            self::$posts[$index]['middlewares'][] = $name;
        }
        return self::getInstance();
    }

    static function middlewareMult($names,$call){
        $mid = new Middleware;
        self::$middlewareNames = $names;
        $call();
        self::$middlewareNames = [];
    }

    // web 缓存 map gets post
    public static function webInit(){
        
        $md5 = md5_file(ROOT."route/web.php");
        
        // $data = file_get_contents(ROOT."cache/webChache");
        $data = RD::chache('webChache',3600,function()use($md5){
            require_once ROOT . "/route/web.php"; // 注册路由 过期重新注册路由
            return array_merge_recursive(['md5'=>$md5],['map'=>self::$map],['gets'=>self::$gets],['posts'=>self::$posts]);
        });
        if($data){
            // var_dump($data);
            // echo '11';
            // $data = json_decode($data,true);
            if($md5==$data['md5']){
                // dd($data);
                self::$map = $data['map'];
                self::$gets = $data['gets'];
                self::$posts = $data['posts'];
                // var_dump(self::$gets,$data['gets']); die;
                // dd(self::$gets);
                return true;
            }
        }

        // echo '22';
        require_once ROOT . "/route/web.php"; // 注册路由
        // echo 'Route:370';
        // die;
        // $data = array_merge_recursive(['md5'=>$md5],['map'=>self::$map],['gets'=>self::$gets],['posts'=>self::$posts]);
        // file_put_contents(ROOT."cache/webChache",json_encode($data));
        $data = RD::chache('webChache',3600,function()use($md5){
            return array_merge_recursive(['md5'=>$md5],['map'=>self::$map],['gets'=>self::$gets],['posts'=>self::$posts]);
        },true);

        // die;
    }

    /**
     * RBAC
     * 为路由加锁
     */
    public static function lock(){
        $args = func_get_args();
        // dd($args);
        $num = func_num_args();
        if ($num == 1) {

            if (is_string($args[0]))
                return call_user_func_array([__NAMESPACE__ . '\Route', "lockSingle"], $args);
            if ( is_callable($args[0]))
                return call_user_func_array([__NAMESPACE__ . '\Route', "lockMult"], $args);
        }
        throwE('参数错误,请检查函数lock','lock');
    }

    // 单独设置锁
    static function lockSingle($names){
        
        return self::getInstance();
    }
    // 批量 添加锁
    static function lockMult($call){

        self::$startLock = true;
        $call();
        self::$middlewareNames = false;

    }
    // 锁名 权限名称
    function lockName($name){



        if(in_array($name,array_keys(self::$map))){
            throwE($name.':路由名称重复','name');
        }
        
        self::$lockMap[$name] = self::$lastUrl;

        if (self::$lastUrl['method'] == 'get')
            self::$gets[count(self::$gets) - 1]['lockName'] = $name;
        else
            self::$posts[count(self::$posts) - 1]['lockName'] = $name;

        return self::getInstance();
    }


}


?>