<?php
namespace core;
if (!defined("ACCESS")) {
    echo "未经过 主入口<br>";
    header("location:../index.php");
}
require_once ROOT . "core/Loader.php";

use core\Route;

class App {

    public static function run($argv = []) {

        if (php_sapi_name() == 'cli') {
            self::initDir();
            self::initError();
            self::CliDispatch($argv);
            return;
        }
        self::initCharset();
        self::initDir();
        self::initError();
        Route::webInit();
        Route::initDispatch();
    }

    public static function websocketRun($argv = []) {

        if (php_sapi_name() == 'cli') {
            self::initDir();
            self::initError();
            self::CliDispatch($argv);
            return;
        }
        self::initCharset();
        self::initDir();
        self::initError();
        Route::webInit();
        Route::initDispatch();
    }
    public static function websocketConnect($e) {

        return function($e){
            
        };
    }
    public static function websocketClose($e) {

        return function($e){
            
        };
    }
    public static function websocketMessage($e) {

        return function($e){
            
        };
    }

    private static function initCharset() {
        header("Content-type:text/html;charset=utf-8");
    }

    public static function initDir() {

        //目录斜线
        define("DS", DIRECTORY_SEPARATOR);
        //主程序目录
        define("APP_PATH", ROOT . "app" . DS);
        //PUBLIC目录
        define("PUBLIC_PATH", ROOT . "public" . DS);
        //UPLOAD目录
        define("UPLOAD_PATH", ROOT . "uploads" . DS);
        //配置目录
        define("CONFIG_PATH", ROOT . "Config" . DS);

        $GLOBALS['config'] = include CONFIG_PATH . "/config.php";
        $conf = $GLOBALS['config'];
        // 网站网址
        define("APP_URL", $conf['APP_URL']);

    }

    private static function initError() {

        @ini_set("display", 1);
        @ini_set("error_reporting", "E_ALL");

    }

    /**
     * cli 路由
     * 1. 控制器
     * 2. 方法
     * 3. 之后为其他参数会被传递到 方法
     */
    private static function CliDispatch($argv) {

        if (php_sapi_name() == 'cli') {
            $controller = ucfirst($argv[1]) . 'Controller';
            $action = $argv[2];
        } else {
            if (isset($_SERVER['PATH_INFO'])) {
                $pathInfo = $_SERVER['PATH_INFO'];
                $pathInfo = explode('/', $pathInfo);
                $controller = ucfirst($pathInfo[1]) . 'Controller';
                $action = $pathInfo[2];
            } else {
                $controller = 'IndexController';
                $action = 'index';
            }
        }
        $fullController = 'app\\controllers\\' . $controller;
        $_C = new $fullController;
        $_C->$action(array_slice($argv, 3));
    }

}


?>