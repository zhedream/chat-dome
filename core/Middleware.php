<?php

namespace core;

use core\Reflexs\ReflexMiddlewareMethod; // 自定义 反射类

class Middleware {

    static $stack;

    public function run($app) {

        $request = \core\Request::getDisRequest();
        // make 中间件栈

        $pipes = $this->makeStack($request);

        $firstSlice = function () use ($app) {
            $app();
            // echo "<br> EXEC :: Application." . '<br>';
        };

        $pipes = array_reverse($pipes);
        // 去皮
        $go = array_reduce($pipes, $this->getSlice($request), $firstSlice);
        $go();

    }

    public function makeStack($request) {
        // dd($request);
        $routeInfo = $request->getCurrentRouteInfo();
        // dd($routeInfo['middlewares']);
        $middlewares = include ROOT . "Config/middleware.php";
        
        $pipes = $middlewares['middleware']; // 全局中间件


        $routeMiddlewares = $middlewares['routeMiddleware']; // 路由中间件
        foreach ($routeInfo['middlewares'] as $key => $val) {
            if(!$routeMiddlewares[$val])
                throwE('不存在路由中间件:'.$val);
            $pipes[] = $routeMiddlewares[$val];
        }

        // dd($overall);
        // dd($pipes); 

        $this->_before_return_makeStack($pipes);
        return $pipes;

    }

    function getSlice($request) {

        return function ($stack, $pipe) use ($request) {
            // dd($stack);
            return function () use ($stack, $pipe, $request) {
                // echo $pipe."----";
                $p = new $pipe;
                // 反射 与 依赖注入 未完待续
                $ref = new ReflexMiddlewareMethod ($pipe, 'handle'); // 反射 方法
                // 注入 request 单例 OR  传递
                return $ref->invokeArgs($p, [$stack, $request]);
            };
        };
    }

    /**
     * 更改pipes
     * @param &$pipes
     */
    function _before_return_makeStack(&$pipes) {
    }


}