<?php

namespace core\Reflexs;

use core\ReflexMethod;

class ReflexMiddlewareMethod extends ReflexMethod
{

    /**
     * 传入路由参数 与 依赖注入
     * @param $args
     */
    protected function _before_invokeArgs(&$args) {
        // dd($args[0]);
        // echo count($args);
        $data = [];
        foreach ($this->paramNames as $key => $val) {
            // var_dump($val['typeName']);
            if ($val['typeName']) {
                $data[] = array_shift($args);
            } else {
                $data[] = array_pop($args);
            }
        }
        // die;
        $args = $data;
    }



}