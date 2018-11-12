<?php

namespace app\controllers;

use core\Controller;
use core\Request;
use core\DB;
use core\RD;
use app\Models\Temp;

class StaticController extends Controller {

    function index() {
        $name = 'User';
        ob_start();
        view('index', ['name' => $name]);
        $str = ob_get_contents();
        // 生成静态页
        file_put_contents(ROOT . '/public/index.html', $str);
        // 清空缓冲区
        ob_clean();
    }

}

?>