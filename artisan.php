<?php
define('ROOT', dirname(__FILE__) . '/'); // 根目录
define("ACCESS", true);// 入口标识
// var_dump(ROOT);die;
require_once ROOT . "core/Loader.php";
require_once ROOT . "core/CoreFn.php";
core\App::initDir();
if (php_sapi_name() != 'cli')
    die('使用错误');

// var_dump($argv);

if ($argv[1] == 'serve') {
    echo 'http://0.0.0.0:9999';
    exec("php -S 0.0.0.0:9999 -t public/");
}
if ($argv[1] == 'index') {
    exec("php public/index.php static index");
    echo '更新完毕 http://www.my.com/index.html';
}
if ($argv[1] == 'contents') {
    exec("php public/index.php static contents");
    echo 'content更新完毕 http://www.my.com';
}

if ($argv[1] == 'q') {
    if ($argv[2] == 'mail')
        exec("php public/index.php queue sendmail", $data);

    if ($argv[2] == 'other')
        exec("php public/index.php queue sendmail", $data);

}

if ($argv[1] == 'mock') {
    if ($argv[2] == 'comments')
        exec("php public/index.php mock comments", $data);

    if ($argv[2] == 'other')
        exec("php public/index.php mock sendmail", $data);

}

use libs\Make;
if($argv[1] == 'make'){
    $make = new Make;
    // var_dump($make);die;;
    if ($argv[2] == 'C'){
        
        $make->controller($argv[3]);
        echo $make->cdir;
    }
    if ($argv[2] == 'M'){
        if(isset($argv[4]))
            return $make->model($argv[3],$argv[4]);
        else
            $make->model($argv[3]);
        
        echo $make->mdir;
    }
    if($argv[2] == 'G'){
        
        if(isset($argv[4]))
            $make->group($argv[3],$argv[4]);
        else
            $make->group($argv[3]);

    }

}




?>