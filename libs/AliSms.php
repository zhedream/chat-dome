<?php

namespace libs;

use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;

class AliSms{

    function send($phone,$code){

        // RAM -> sms
        $config = [
            'accessKeyId'    => 'LTAItvg3o94MTDE6',
            'accessKeySecret' => 'nC2XFF30fy3KItFrYHdWIzeJm4HPL0',
        ];

        $client  = new Client($config);
        $sendSms = new SendSms;
        $sendSms->setPhoneNumbers($phone);
        $sendSms->setSignName('觅风启航');
        $sendSms->setTemplateCode('SMS_126260035');
        $sendSms->setTemplateParam(['code' => $code]);
        $sendSms->setOutId('demo');
        return $client->execute($sendSms);

    }
}