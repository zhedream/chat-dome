<?php

namespace libs;

class Log {

    private $fp = null;

    public function __construct($fileName) {
        $this->fp = fopen(ROOT . 'logs/' . $fileName . '.log', 'a');
    }

    /**
     *
     */
    public function log($content) {

        fwrite($this->fp, str_repeat('-', 100) . "\r\n" . date("Y-m-d H:i:s") . "\r\n" . "\r\n" . $content . "\r\n");
    }


}


?>