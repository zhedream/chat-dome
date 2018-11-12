<?php

use core\Response;

/**
 *  加载视图
 *  参数一、加载的视图的文件名
 *  参数二、向视图中传的数据
 */
function view($viewFileName, $data = []) {

    // 解压数组成变量
    // ob_clean();
    extract($data);
    $path = str_replace('.', '/', $viewFileName) . '.html';
    // 

    if (!file_exists(ROOT . 'views/' . $path))
        throwE('view()引入的视图文件不存在', 'view');
    require(ROOT . 'views/' . $path);
    // die;
}


/**
 *  获取当前 URL 上所有的参数，并且还能排除掉某些参数
 *  参数：要排除的变量
 *  模式：1.
 */
function getUrlParams($model = 1, $cover = [], $except = []) {
    // ['odby','odway']
    // 循环删除变量
    $gets = $_GET;

    if ($model == 1) {
        foreach ($except as $v) {
            unset($gets[$v]);
        }
        foreach ($cover as $key => $value) {
            $gets[$key] = $value;
        }
        $data = [];
        foreach ($gets as $key => $value) {
            $data[] = $key . '=' . $value;
        }
        return $data;
    }

    if ($model == 2) {

        foreach ($except as $v) {
            unset($gets[$v]);
        }

        $str = '';
        foreach ($gets as $k => $v) {
            $str .= "$k=$v&";
        }

        return $str;

    }


}

/**
 *
 * php 跳转
 */
function redirect($url) {
    header('Location:' . $url);
    // exit;
}

/**
 * 返回上一个页面
 */
function back() {
    redirect($_SERVER['HTTP_REFERER']);
}

/**
 * 提示消息的函数
 * type 0:alert
 * 1. 消息
 * 2. 类型
 * 3. 地址
 * 4. 跳转时间 type 1 使用
 */
function message($message, $type, $url, $seconds = 5) {
    if ($type == 0) {
        echo "<script>alert('{$message}');location.href='{$url}';</script>";
        exit;

    } else if ($type == 1) {
        // 加载消息页面
        ob_clean();
        view('common.success', [
            'message' => $message,
            'url' => $url,
            'seconds' => $seconds
        ]);
    } else if ($type == 2) {
        // 把消息保存到 SESSION
        $_SESSION['_MESS_'] = $message;
        // 跳转到下一个页面
        redirect($url);
    }
}

function getChar($num)  // $num为生成汉字的数量
{
    $b = '';
    for ($i = 0; $i < $num; $i++) {
        // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
        $a = chr(mt_rand(0xB0, 0xD0)) . chr(mt_rand(0xA1, 0xF0));
        // 转码
        $b .= iconv('GB2312', 'UTF-8', $a);
    }
    return $b;
}

/**
 * 获取当前路由 名称
 */
function routeName() {
    return core\Route::$routeName;
}

/**
 * 生产url
 * 1. 路由名称
 * 2. 路由参数
 */
function Route($name, $data = [], $full = false) {

    $router = core\Route::getInstance();
    return $router->makeUrl($name, $data, $full);

}

/**
 * 过滤XSS（在线编辑器填写的内容不能使用该函数过滤）
 * 1. 过滤内容
 */
function e($content) {
    return htmlspecialchars($content);
}

/**
 *  使用 htmlpurifer 选择过滤(性能慢只用于富文本)
 * 1. 过滤内容
 */
function hpe($content) {
    // 一直保存在内存中(直到脚本执行结束)
    static $purifier = null;
    if ($purifier === null) {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('core.Encoding', 'utf-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('Cache.SerializerPath', ROOT . 'cache');
        $config->set('HTML.Allowed', 'div,b,strong,i,em,a[href|title],ul,ol,ol[start],li,p[style],br,span[style],img[width|height|alt|src],*[style|class],pre,hr,code,h2,h3,h4,h5,h6,blockquote,del,table,thead,tbody,tr,th,td');
        $config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,margin,width,height,font-family,text-decoration,padding-left,color,background-color,text-align');
        $config->set('AutoFormat.AutoParagraph', TRUE);
        $config->set('AutoFormat.RemoveEmpty', TRUE);
        $purifier = new \HTMLPurifier($config);
    }
    return $purifier->purify($content);
}

function csrf() {
    if (!isset($_SESSION['_token'])) {
        // 生成一个随机的字符串
        $_token = md5(rand(1, 99999) . microtime());
        $_SESSION['_token'] = $_token;
    }
    return $_SESSION['_token'];
}

// 生成令牌隐藏域
function csrf_field() {
    $csrf = isset($_SESSION['_token']) ? $_SESSION['_token'] : csrf();
    echo "<input type='hidden' name='_token' value='{$csrf}'>";
}

/**
 * 抛出 异常
 * 1. 异常信息
 * 2. 抛出异常的函数名
 */
function throwE($str, $fn = 'throwE') {
    ob_clean();
    // 所有调用点
    $AllCall = debug_backtrace();
    // jj($AllCall);
    $LocationCall = []; // 定位的异常点
    foreach ($AllCall as $key => $value) {
        if ($value['function'] == $fn) {
            $LocationCall['file'] = $value['file'];
            $LocationCall['line'] = $value['line'];
        }
    }
    // jj($LocationCall);

    // 抛出的异常点
    $ThrowCall = [];
    $ThrowCall['file'] = $AllCall[0]['file'];
    $ThrowCall['line'] = $AllCall[0]['line'];
    // jj($ThrowCall);
    try {
        throw new \Exception($str, 0); // 处理错误信息 的 对象
    } catch (\Exception $e) {
        // echo "<hr>出错文件:&nbsp".$file."<hr>";
        // echo "错误信息:&nbsp".$e->getMessage()."<hr>";
        // echo "错误行号:&nbsp".$line."<hr>";
        view('exception', ['Message' => $e->getMessage(), 'AllCall' => $AllCall, 'ThrowCall' => $ThrowCall, 'LocationCall' => $LocationCall]);
        die;
    }
}

function jj($data, $option = true) {
    ob_clean();
    $callinfo = debug_backtrace()[0];
    $file = $callinfo['file'];
    $line = $callinfo['line'];
    // var_dump($callinfo);
    // array_unshift($data,$aa);
    // var_dump($data, gettype($data));
    // throwE('','');
    // die;
    if (gettype($data) == 'boolean')
        die('boolean:' . $data . "\r\n<br>file:{$file},<br>line:{$line}");
    if (gettype($data) == 'string ')
        die('string :' . $data . "\r\n<br>file:{$file},<br>line:{$line}");

    $data['jj-callinfo'] = ['file' => $file, 'line' => $line];

    if ($option)
        echo json_encode($data, true);
    else
        echo json_encode($data);

    die;

}

function dd($data, $clean = true) {
    $AllCall = debug_backtrace();
    $ThrowCall = [];
    $ThrowCall['file'] = $AllCall[0]['file'];
    $ThrowCall['line'] = $AllCall[0]['line'];
    if ($clean)
        ob_clean();
    var_dump($data, $ThrowCall);
    die;

}

function env($key, $val = '') {

    $conf = include ROOT . "/env.php";
    $keys = array_keys($conf);
    if (in_array($key, $keys))
        return $conf[$key];
    return $val;
}

function config($name) {

    return $GLOBALS['config'][$name];
}

/**
 * 引入CSS
 * 1. 路径 css/XX.css
 * 2. 是否包含 标签
 */
function includeCss($path, $tag = true) {
    // $path = str_replace('.', '/', $path) . '.css';
    $str = file_get_contents(ROOT . "public/" . $path);
    if ($tag)
        return "<style>{$str}</style>";
    return $str;
}

/**
 * 注意 引入 的 js 不会执行 php 代码块
 */
function includeJs($path, $tag = true) {
    // $path = str_replace('.', '/', $path) . '.js';
    $str = file_get_contents(ROOT . "public/" . $path);
    if ($tag)
        return "<script>{$str}</script>";
    return $str;
}

function response() {
    return Response::getInstance();
}


?>