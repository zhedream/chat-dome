<?php

namespace libs;

use \Swift_SmtpTransport;
use \Swift_Mailer;
use \Swift_Message;

Mail::client();

class Mail {


    static $mailer = null;
    public $mail;

    function __construct() {
        self::client();
        // $this->$mail = self::$mailer;
    }

    static function client() {
        if (self::$mailer === NULL) {

            $conf = $GLOBALS['config']['email'];
            try {
                // 设置邮件服务器账号
                $transport = (new \Swift_SmtpTransport($conf['host'], $conf['port']))// 邮件服务器IP地址和端口号
                ->setUsername($conf['username'])// 发邮件账号
                ->setPassword($conf['password']);      // 授权码
                // 创建发邮件对象
                self::$mailer = new \Swift_Mailer($transport);

            } catch (PDOException $e) {
                echo "邮箱服务连接失败！" . $e->getMessage();
            }
        }
    }

    static function getEM() {
        // if (self::$mailer === NULL) self::db();
        if (self::$mailer === NULL) {
            // echo '2<br>';
            self::client();
            return self::$mailer;
        } else {
            // echo '3<br>';
            return self::$mailer;
        }
    }

    /**
     *
     *
     */
    function send($title, $content, $to) {
        $conf = $GLOBALS['config']['email'];
        // 创建邮件消息
        $message = new \Swift_Message();
        $message->setSubject($title)// 标题
        ->setFrom([$conf['username'] => $conf['name']])// 发件人
        ->setTo([$to => '亲爱的者之梦用户'])// 收件人
        ->setBody($content, 'text/html');     // 邮件内容及邮件内容类型
        // 发送邮件
        return self::$mailer->send($message);
    }

    /*  */


}


?>