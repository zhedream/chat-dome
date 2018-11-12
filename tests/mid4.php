<?php
interface Middleware
{
    public static function handle(Closure $next);
}

class VerifyCsrfToken implements Middleware
{
    public static function handle(Closure $next)
    {
        echo "(5)验证Csrf-Token".'<br>';
        $next();
    }
}

class ShareErrorsFromSession implements Middleware
{
    public static function handle(Closure $next)
    {
        echo "(4)如果session中有'errors'变量，则共享它".'<br>';
        $next();
    }
}

class StartSession implements Middleware
{
    public static function handle(Closure $next)
    {
        echo "(3)开启session，获取数据".'<br>';
        $next();
        echo "(7)保存数据，关闭session".'<br>';
    }
}

class AddQueuedCookiesToResponse implements Middleware
{
    public static function handle(Closure $next)
    {
        $next();
        echo "(8)添加下一次请求需要的cookie".'<br>';
    }
}

class EncryptCookies implements Middleware
{
    public static function handle(Closure $next)
    {
        echo "(2)对输入请求的cookie进行解密".'<br>';
        $next();
        echo "(9)对输出相应的cookie进行加密".'<br>';
    }
}

class CheckForMaintenanceMode implements Middleware
{
    public static function handle(Closure $next)
    {
        echo "(1)确定当前程序是否处于维护状态".'<br>';
        $next();
    }
}

class CheckLogin implements Middleware
{
    public static function handle(Closure $next)
    {
        echo "(1)确定请求是否登陆".'<br>';
        $next();
    }
}

function getSlice()
{
    return function($stack, $pipe)
    {
        return function() use ($stack, $pipe)
        {
            return $pipe::handle($stack);
        };
    };
}


function then()
{
    $pipes = [
        "CheckForMaintenanceMode",
        "EncryptCookies",
        "AddQueuedCookiesToResponse",
        "StartSession",
        "ShareErrorsFromSession",
        "VerifyCsrfToken",
        "CheckLogin"
    ];
    
    $firstSlice = function() {
        echo "(6)请求向路由器传递，返回响应.".'<br>';
    };

    $pipes = array_reverse($pipes);
    $go = array_reduce($pipes, getSlice(),$firstSlice);
    $go();
}
then();
?>