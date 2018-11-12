<?php
// phpinfo();die;
ini_set('display_errors', 'On'); // Off
ini_set('date.timezone', 'PRC');
ini_set('session.gc_maxlifetime', "1800"); // 秒
// ini_set("session.cookie_lifetime","3600"); // 秒
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://127.0.0.1:6379?database=3');
session_start();
define("ACCESS", true);// 入口标识
define('ROOT', dirname(__FILE__) . '/../'); // 根目录
define("lm", "<br>"); // <br>
require_once ROOT . "/core/App.php"; // 核心APP 入口
require_once ROOT . "/core/CoreFn.php"; // 核心辅助全局函数

//var_dump(ROOT . "/route/web.php");die();
core\App::run(isset($argv) ? $argv : []);
