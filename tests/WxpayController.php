<?php

namespace app\controllers;

use Yansongda\Pay\Pay;
use Endroid\QrCode\QrCode;
use libs\Log;

class WxpayController {
    protected $config = [
        'app_id' => 'wx426b3015555a46be', // 公众号 APPID
        'mch_id' => '1900009851',
        'key' => '8934e7d15453e97507ef794cf7b0519d',

        // 通知的地址
        'notify_url' => APP_URL.'/wxpay/notify',
    ];

    // 调用微信接口进行支付
    public function pay() {
        // phpinfo();
        $order = [
            'out_trade_no' => time(),
            'total_fee' => '1', // **单位：分**
            'body' => 'test body - 测试',
        ];

        // 调用接口
        $pay = Pay::wechat($this->config)->scan($order);
        // var_dump($pay);die;
        // 打印返回值 
        echo $pay->return_code, '<hr>';
        echo $pay->return_msg, '<hr>';
        echo $pay->appid, '<hr>';
        echo $pay->result_code, '<hr>';
        echo $pay->code_url, '<hr>';     // 支付码
        ob_clean();
        $qrCode = new QrCode($pay->code_url);
        header('Content-Type: ' . $qrCode->getContentType());
        echo $qrCode->writeString();
    }

    public function notify() {

        $loger = new Log('wxpay_notify');
        $pay = Pay::wechat($this->config);
        $loger->log('收到请求');
        try {
            $data = $pay->verify(); // 是的，验签就这么简单！

            if ($data->result_code == 'SUCCESS' && $data->return_code == 'SUCCESS') {
                $str = '共支付了：' . $data->total_fee . '分';
                $str .= '订单ID：' . $data->out_trade_no;
                $loger->log($str);
            }

        } catch (Exception $e) {
            $loger->log('异常');
            // $loger->log('异常'.$e->getMessage());
            // var_dump( $e->getMessage() );
        }

        $pay->success()->send();
    }
}