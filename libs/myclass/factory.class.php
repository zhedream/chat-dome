<?php

class factory{

    private static $obj = [];

    static function getinstance($ClassName,$singleton = false){

        if($singleton){

            if(!isset(self::$obj[$ClassName]))
                self::$obj[$ClassName] = new $ClassName;
            else {
                return self::$obj[$ClassName];
            }
        }

        return new $ClassName;

    }

    static function getinstanceSingle(){
        if(!isset(self::$obj[$ClassName]))
                self::$obj[$ClassName] = new $ClassName;
        else {
            return self::$obj[$ClassName];
        }

    }

}

?>