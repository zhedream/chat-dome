<?php

namespace app\controllers;

use core\Request;

class ChatController extends Controller {

    // 显示列表
    function index() {

        view('chat.index');
    }

}

?>