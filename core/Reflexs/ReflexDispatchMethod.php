<?php

namespace core\Reflexs;

use core\ReflexMethod;

class ReflexDispatchMethod extends ReflexMethod
{

    /**
     * 传入路由参数 与 依赖注入
     * @param $args
     */
    // public $injection = false;
    
    protected function _before_invokeArgs(&$args) {

        $routeVars = \core\Request::getRouteVar();
        $args = array_merge_recursive($args, $routeVars);
        $data = [];
        foreach ($this->paramNames as $key => $val) {

            if ($val['typeName']) {
                    // 储存 注入 请求类
                $tem = new $val['typeName'];
                $data[] = $tem;
            } else {
                $data[] = current($args);
                next($args);
            }
        }
        $args = $data;
    }

    // 记录 调用的 请求类 或 子类
    protected function _after_analysis_hasType($typeName){
        // _before_invokeArgs 存在 new 两次的 问题
        $tem = new $typeName;
        if($tem instanceof \core\Request) {
            // dd($tem);
            $this->$injection = true;
            \core\Request::setDisRequest($tem);
        }
    }

    // analysis 解决 二次钩子 无依赖注入 情况
    protected function _after_analysis(){
        if($this->$injection===false || $this->$injection == false){

            // dd(!$this->injection,false); //  !$this->$injection  $this->$injection == false $this->$injection === false  这是什么BUG
            \core\Request::setDisRequest(new \core\Request);
        }
    }


}