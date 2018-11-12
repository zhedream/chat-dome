<?php

namespace libs;

use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;

class AliSms{

    function send($phone,$code){

        // RAM -> sms
        $config = [
            'accessKeyId'    => '',
            'accessKeySecret' => '',
        ];

        $client  = new Client($config);
        $sendSms = new SendSms;
        $sendSms->setPhoneNumbers($phone);
        $sendSms->setSignName('');
        $sendSms->setTemplateCode('');
        $sendSms->setTemplateParam(['code' => $code]);
        $sendSms->setOutId('demo');
        return $client->execute($sendSms);

    }
}