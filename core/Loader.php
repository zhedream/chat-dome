<?php
require_once ROOT . "/vendor/autoload.php"; // 加载 vendor

Loader::$myclassDir = ROOT . "/libs/myClass/";
Loader::$is_debug = 0;
spl_autoload_register('Loader::autoload');
spl_autoload_register("Loader::ForClass");

class Loader {
    public static $myclassDir = "/Vendor/class/";
    public static $is_debug = true;

    /**
     * 判断是否属于类
     * 1.对象
     * 2.类名
     */
    static function isObjfromClass($obj, $className) {
        // var_dump();
        return ($obj instanceof $className);
    }
    // for myframe

    /**
     * 使用完整的路径命名空间
     */
    public static function autoload($class) {
        $path = str_replace('\\', '/', $class);
        if (file_exists(ROOT . $path . '.php')) {
            require_once(ROOT . $path . '.php');
            if (self::$is_debug)
                echo "<br>正在自动加载类" . __FUNCTION__ . ":{$class}<br>";
        }
    }

    public static function ForClass($class) {

        $path = str_replace('\\', '/', $class);
        // echo ROOT . $path . '.class.php'."--autoload3--<br>";
        if (file_exists(ROOT . $path . '.class.php')) {
            require_once(ROOT . $path . '.class.php');
            echo "<br>正在自动加载类" . __FUNCTION__ . ":{$class}<br>";
        }
    }

    /**
     * 递归自动加载 myclass类:void
     * 1.dirCodeCount('指定路径范围',类名):path
     */
    public static function auto_load($className) {
        $path = dirCodeCount(self::$myclassDir, $className);
        // echo $path."<br>";
        if (file_exists($path)) {
            if (self::$is_debug)
                echo "正在自动加载类" . __FUNCTION__ . ":{$className}<br>";
            include_once $path; //不加 抑制符  报错说明 到最后 都找不到 正确的类   require_once
        } else {
            if (self::$is_debug)
                echo "未找到{$className}类<br>";
        }
    }

// 加载类结束

}

?>
<?php


function substr_ab($str, $a = 0, $b = 0) {
    $len = strlen($str);
    $b = $b > 0 ? $b : $len;
    $str2 = "";
    for ($i = $a; $i < $b && $i < strlen($str); $i++)
        $str2 .= $str[$i];

    // echo $str2;
    return $str2;
}

?>

