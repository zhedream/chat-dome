<?php

namespace core;

class Controller {

    public $smarty;

    function __construct() {
        // $this->smarty = new \Smarty;
        // // $this->smarty->template_dir = APP_PATH."\/view/".PLAT;
        // $this->smarty->template_dir = ROOT . "\/views/";
        // $this->smarty->compile_dir = ROOT . "\/views_c/";
        // $this->smarty->caching = false;
        // $this->smarty->cache_dir = ROOT . "\/cache/";
        // $this->smarty->left_delimiter = "<{";
        // $this->smarty->right_delimiter = "}>";
    }

    function assign($name, $data) {
        $this->smarty->assign($name, $data);
    }

    function display($name) {
        $this->smarty->display($name);
    }

    public function __call($name, $arr) {

        echo "控制器" . __CLASS__ . "不存在方法" . $name . lm;

    }

    public static function __callstatic($name, $arr) {

        echo "控制器" . __CLASS__ . "不存在静态方法" . $name . lm;

    }
}


?>