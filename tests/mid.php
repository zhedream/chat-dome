<?php
// 框架核心应用层
$application = function() {
    echo "this is a Application\n";
};
 
// 前置校验中间件
$auth = function($handler) {
    return function($name) use ($handler) {
        echo $name++."::auth\n";
        echo "{$name} need a auth middleware::前置1\n";
        return $handler($name);
    };
};
 
// 前置过滤中间件
$filter = function($handler) {
    return function($name) use ($handler) {
        echo $name++."::filter\n";
        echo "{$name} need a filter middleware::前置2\n";
        return $handler($name);
    };
};
 
// 后置日志中间件
$log = function($handler) {
    return function($name) use ($handler) {

        echo $name++."::log\n";
        $return = $handler($name);
        // die;
        echo "{$name} need a log middleware::后置1\n";
        return $return;
    };
};
 
// 中间件栈
$stack = [];
 
// 打包
function pack_middleware($handler, $stack)
{
    foreach (array_reverse($stack) as $key => $middleware) 
    {
        $handler = $middleware($handler); // 把 handler( APP ) 放入 一个 中间件 打包 返回 新的 中间件们 + APP 的 新栈堆 
    }
    return $handler; // 返回 调用 栈堆
}
 
// 注册中间件
// 这里用的都是全局中间件，实际应用时还可以为指定路由注册局部中间件
$stack[] = $auth;
$stack[] = $filter;
$stack[] = $log;
 
$run = pack_middleware($application, $stack);
// var_export($run);die;
$run(0);
