<?php
// 框架核心应用层
$application = function($name) {
    echo "this is a {$name} application::APP\n";
};
 
// 前置校验中间件
$auth = function($handler,$request) {
        echo "{$request} need a auth middleware::前置1\n";
        return $handler($handler,$request);
};
// 前置校验中间件2
$auth2 = function($handler,$request) {
    echo "{$request} need a auth middleware::前置1\n";
    die;
    return $handler($handler,$request);
};
// 前置过滤中间件
$filter = function($handler,$request) {
        echo "{$request} need a filter middleware::前置3\n";
        return $handler($handler,$request);
    };
 
// 后置日志中间件
$log = function($handler,$request)  {
    // die;
    $return = $handler($handler,$request);
    // die;
    echo "{$request} need a log middleware::后置1\n";
    return $return;
};

$log2 = function($handler,$request)  {
    // die;
    $return = $handler($handler,$request);
    // die;
    echo "{$request} need a log middleware::后置2\n";
    return $return;
};


 
// 中间件栈
$stack = [];
 
// 打包
function pack_middleware($handler, $stack){   

    // return function($request) use($handler, $stack){
    
        foreach (array_reverse($stack) as $key => $middleware) 
        {
            // var_export($middleware);die;
            $handler = $middleware($handler,$request); // 把 handler( APP ) 放入 一个 中间件 打包 返回 新的 中间件们 + APP 的 新栈堆 
        }
        return $handler; // 返回 调用 栈堆
    // };
}
 
// 注册中间件
// 这里用的都是全局中间件，实际应用时还可以为指定路由注册局部中间件
$stack[] = $auth;
$stack[] = $auth2;
$stack[] = $log;
$stack[] = $log2;
$stack[] = $filter;
 
$run = pack_middleware($application, $stack);
// var_export($run);die;
$run();
// $run('Laravle');
