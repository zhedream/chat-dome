<?php

namespace app\controllers;

use Yansongda\Pay\Pay;
use libs\Log;
use core\Request;
use app\Models\Order;
use app\Models\User;
use libs\Snowflake;

class AlipayController {
    // 配置
    // nrxcae3004@sandbox.com
    public $config = [
        'app_id' => '2016091200490918',
        // 支付宝公钥
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsC8u/BAbbFVOy1spqvW0mESXUsVYk0iBREZ9++gA1XNUoKbAh/eaeq0EFHGugAtDT8GHWK3/nPOENHZBriREuzRyVEOZ6MHEli32IhCR6k5hOyMvCGfG0/zJnGwJ41YroqJHynyn5PjYid+iG2ScOELvuWraUWXFDdGXFASc9rv5l5cQwuYUYB+gqm4tPuX1OArdNTkoxaqSv0oLzZkB1JCtSHOXAAhUk2lLWcBeql4jxvyuh2kcnCGzflBoG0K1qF9m6SGgqXgDQ8odLdzMb9Y3r4SBbr87Qa0FBz6U/h/7hpCJU9m9sCH0XsTUAlU6kb47PHetfRPwE34VynySJQIDAQAB',
        // 商户应用密钥
        'private_key' => 'MIIEowIBAAKCAQEAtl/1KgrN+NzwYI/hBq7HT97rdhJXEvnzEjyoCyxJv55CFPn3hjo+dXe0PIorY5quIvu/IdSEL0DrIIySBPto5BfNfKCmtOc8oZG/f2/jAySSlKRYPBEs7yVef5deEG4r6SgFBlb6CFRmJn9A3WtD872VQO6AjiC8mzEiiIm0Dq+a1cZIyovmE8SoFsBKaek1rOIUfb9aJDD1NvefWwKnXx+oZZ7YhY86rrbekhwDcUalHbpSUsjCknMU/AVEFf2y/ki5Q5Z7kY0WNcyCstSYvJuZUo3ez8tzamdHZ0Mf6trIjFSmadvD46chaHZ20x2Wa/ds79M5HtHHJoEbSd6SFQIDAQABAoIBAFl67VQKZxLSfFI0ZckcmggTLN4Kk5Ro9J0fC6gnu6t7n5qhJpRCIYELEXCerjk5nHTnpdiYZ56zsGmQ7tfo7obzMswSGpkp13LCiv2gzPYuzIiHtg8KskxHvnzFrM5M79h+3TBGHnlVx6TdzNqWlYmSnBd2rbaOU1ulmPb68VA+fmMLrUCDBniwJpNrf8Jh3uOO7vU8HAp83dtUgHTq2zYoVSPQtZt9UnK8hM0VMyfKOnoOaWv1UJEPHqmxEddbj32/8+ms458sNIMc2g2sAgxfvWPHqX6Lvk9Nt723z4EJZqE8wSj1cfrs1bovhElWZgtyygIUch3xQMPi9Q88ecECgYEA4x4OI/wqC8e0XyO6fHikZ9lV/KxoYNZ5OdnUX51WS8z2V9wt0PO2Vmhj+8ZJTYPnS5M0XaQ2BPfu8VBgJv+pDQLYinYeRtpMfnMFjQUK2rdvUdpMxzDPTKtst9ltrv74pUh/ZDyNVKNZeLuEOhY53EDt+dNU7ZG9gGgGgpQEUFECgYEAzZFH7tN9xAHj0Xes7/6VTtJ1O0zuu4i0aMMpIFBUEoI78zAXbZ4qBOyRGatpfYtfQIo4u+ft8107RUDaNlBJZmpkqlG+EK6PT194plCpotImWYvIg53D1VLzJfdLZV7fAvXZA10P7gi751cCMC8PHzlOHQiFvtZqwXwC1UN1WIUCgYEAwnSJRuYwWcWy+YJtuQTSPtgmdyBmfgMj6BRJcVQU/vGOOcuarrz78R+P+5HaUTQOZPa0bziZx8dAHfzjVoCvDSTSojpf0eo2dE2nAwa+NGW6Oirece2oj8x2WTMgZiSIX3ujFv+BQmZZxLVIkTNWdu5g0vXOUVnnFnn6mPKCfwECgYBQ7dY87uQ/a2MOTyg1X6vWWUKv8uy1xe8Io3SodRd0JfOGHTPMAw2V3LCPQ42HUHxSg1gsmfVy7wxrikmeQmNzP4WcDAxgsuhWnkZ4a58tK8DPVhm9vzme3UY+dyomoX/4wWMLUPL5ilS3keiZoZ05dK0M/xLwe6eRvsm6vhEPpQKBgAZ2ZDJGPA0eUkbs3QSdoY2pFQFq1HEBWSaMtdXML11IKOLbI68OuWfupkXolDCTpErCSCcuy7lLR/Z7kHIiywWH/TvDHO+xwIyU05Ael9FG0U2qLnSuqewUWyKr9Pi+OUdX1oIFZrLRNjw2Ln8p3Ic1jkYO9mxo8WVToXrodURm',

        // 通知地址
        'notify_url' => APP_URL.'/alipay/notify',
        // 跳回地址
        'return_url' => APP_URL.'/alipay/return',

        // 沙箱模式（可选）
        'mode' => 'dev',
    ];

    // 跳转到支付宝
    public function pay(Request $req, $id) {
        // $money = $req->all()['money'];
        $order = new Order;
        // dd($order->get($req->all()['sn']));
        $o = $order->findBysn($req->all()['sn']);
        // dd($o);

        $menu = [
            'out_trade_no' => $o['sn'],    // 本地 购物订单 ID
            'total_amount' => $o['money'],    // 支付金额（单位：元）
            'subject' => 'MyFrame 充值：' . $o['money'] . '元', // 支付标题
        ];

        // 跳转到支付宝
        $alipay = Pay::alipay($this->config)->web($menu);
        $alipay->send();
    }

    // 支付完成跳回
    public function return() {
        // 验证数据是否是支付宝发过来
        $data = Pay::alipay($this->config)->verify();


        echo '<h1>支付成功！</h1> <hr>';

        var_dump($data->all());

    }

    // 接收支付完成的通知
    public function notify() {

        $loger = new Log('alipay_notify');

        $alipay = Pay::alipay($this->config);
        try {

            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 这里需要对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；

            if ($data->trade_status == 'TRADE_SUCCESS' || $data->trade_status == 'TRADE_FINISHED') {
                $user = new User;
                $order = new Order;
                ob_start();
                Order::$pdo->beginTransaction();

                $o = $order->findBysn($data->out_trade_no);
                $u = User::findOne("select * from users where id=?", [$o['user_id']]);

                $o['state'] = 1;
                $u['money'] = $u['money'] + (int)$o['money'];

                $res1 = $order->exec_update($o, ['id' => $o['id']]); // 更新订单状态
                $res2 = $user->exec_update($u, ['id' => $o['user_id']]); // 更新余额

                if ($res1 && $res2) {
                    Order::$pdo->commit();
                }

                $str = ob_get_contents();
                file_put_contents(ROOT . '/logs/log' . '.html', $str);
                ob_clean();
            }

            $a = '订单ID：' . $data->out_trade_no . "\r\n";
            $a .= '支付总金额：' . $data->total_amount . "\r\n";
            $a .= '支付状态：' . $data->trade_status . "\r\n";
            $a .= '商户ID：' . $data->seller_id . "\r\n";
            $a .= 'app_id：' . $data->app_id . "\r\n";
            $loger->log($a);


        } catch (\Exception $e) {
            $loger->log('支付请求异常' . $e->getMessage());
        }

        // 回应支付宝服务器（如何不回应，支付宝会一直重复给你通知）
        $alipay->success()->send();
    }

    // 退款
    public function refund(Request $req, $id) {
        // 生成唯一退款订单号（以后使用这个订单号，可以到支付宝中查看退款的流程）
        $flaker = new Snowflake(1023);
        $refundNo = $flaker->nextId();

        $sn = $req->all()['sn'];

        $loger = new Log('alipay_notify');
        $order = new Order;
        $o = $order->findBysn($sn);

        try {
            $menu = [
                'out_trade_no' => $o['sn'],    // 退款的本地订单号
                'refund_amount' => $o['money'],           // 退款金额，单位元
                'out_request_no' => $refundNo,     // 生成 的退款订单号
            ];

            // 退款
            $ret = Pay::alipay($this->config)->refund($menu);

            if ($ret->code == 10000) {
                $o['state'] = 2;
                $order->exec_update($o, ['id' => $o['id']]);
                $loger->log('申请退款成功！,退款单号：' . $refundNo);
                echo '申请退款成功！';
            } else {
                $loger->log('申请退款失败,msg:' . $ret . '退款单号：' . $refundNo);
                var_dump($ret);
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $loger->log('申请退款错误,msg:' . json_encode($ret) . '退款单号：' . $refundNo);
        }
    }
}