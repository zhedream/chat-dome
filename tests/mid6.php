<?php
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

class testTem implements Middleware{
    public static function go(Closure $next)
    {
        echo "测试A";
        $next();
        echo "测试B";
    }
}
class testThree implements Middleware{
    public static function go(Closure $next)
    {
        $next();
        echo "测试三";
    }
}
 
 
function goFun($step,$className){
    return function () use ($step,$className){
        return $className::go($step);
    };
}
 
 
function thenTwo(){
    $steps = ['testOne','testTem','testTwo','testThree'];
    $prepare = function (){
        echo "我是要做的操作";
    };
    $go = array_reduce($steps,'goFun',$prepare);
    $go();
}
thenTwo();
