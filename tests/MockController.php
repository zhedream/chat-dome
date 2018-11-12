<?php

namespace app\controllers;

use core\Controller;
use core\Request;
use core\DB;
use app\Models\Test;
use app\Models\Heart;
use app\Models\Comment;

class MockController extends Controller {

    public function users() {
        // 20个账号
        for ($i = 0; $i < 20; $i++) {
            $email = rand(50000, 99999999999) . '@126.com';
            $password = md5('123123');
            $name = getChar(2);
            DB::exec("INSERT INTO users (email,name,password) VALUES(?,?,?)", [$email, $name, $password]);
        }
    }


}

?>