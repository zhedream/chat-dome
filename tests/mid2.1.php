<?php
// require_once __DIR__.'/vendor/autoload.php';
interface Middleware{
    public static function go(Closure $next);
}
 
class testOne implements Middleware{
    public static function go(Closure $next)
    {
        echo "测试一";
        $next();
        // TODO: Implement handle() method.
    }
}
 
class testTwo implements Middleware{
    public static function go(Closure $next)
    {
        echo "测试二";
        $next();
    }
}
 
 
function goFun($step,$className){
    return function () use ($step,$className){
        return $className::go($step);
    };
}
 
 
function thenTwo(){
    $steps = ['testOne','testTwo'];
    $prepare = function (){
        echo "我是要做的操作";
    };
    $go = array_reduce($steps,'goFun',$prepare);
    $go();
}
thenTwo();
