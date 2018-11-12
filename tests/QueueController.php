<?php

namespace app\controllers;

use core\DB;
use core\RD;
use libs\Log;
use libs\Mail;

class QueueController {

    function sendmail() {
        echo '开始进程';
        $loger = new Log('queue-sendmail');
        $mailer = new Mail;
        ini_set('default_socket_timeout', -1);
        while (1) {
            $message = RD::boqueue('sendmail');
            list($email, $password, $code) = $message;
            $content = "
            点击以下链接进行激活：<br> 点击激活：
            <a href='http://lhz.tunnel.echomod.cn/user/active?code={$code}'>
            http://lhz.tunnel.echomod.cn/user/active?code={$code}</a><p>
            如果按钮不能点击，请复制上面链接地址，在浏览器中访问来激活账号！</p>";

            $state = $mailer->send('注册激活', $content, $email);
            if ($state) {

                $a = RD::setTimeOut('userActive', $code, $message, 60 * 30);
                $loger->log('成功向: ' . $email . ' 发送注册激活邮件:' . $state . ':启动激活程序:' . $a);
            }


        }
    }

}

?>