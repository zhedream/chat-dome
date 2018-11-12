<?php

namespace app\controllers;

use core\Controller;
use core\Request;
use core\DB;
use libs\Upload;

class UploadContronller extends Controller {

    function index() {
        // code
        view('upload.index');
    }

    function readfile(Request $req, $id) {

        // extract($req->all()); // 解压 变量
        $md5 = $_GET['md5'];
        $u =  Upload::getInstance();

        echo $u->readyfile($md5);

    }

    function upload(Request $req, $id) {
        // $_FILES['face'];
        extract($req->all());
        var_dump($_POST);
        var_dump($_FILES);
        // echo 'success';


        /**
         *
         * 1. 检测 文件MD5 是否 秒传
         * 2. 检测 断点续传 判断分片
         * 3. 根据 断点信息 上传
         * 4.
         */

        $slice = $_FILES['slice'];

        /* 保存每个分片 */

        $dir = ROOT . 'temp/';
        is_dir($dir) OR mkdir($dir, 0777, true);

        move_uploaded_file($slice['tmp_name'], ROOT . 'temp/' . $curSlice);

        $redis = RD::getRD();


        $uploadedCount = $redis->incrby('file_slice_count:' . $MD5, 1); // 分片 计数
        echo $uploadedCount;
        $redis->zadd('file_slices:' . $MD5, $index, $curSlice); // 分片存储
        // die;

        if($uploadedCount == $totalSlices){
            echo '完毕';
            // 以追回的方式创建并打开最终的大文件
            $fp = fopen(ROOT.'public/uploads/big/'.$MD5.'.'.$fileType, 'a');
            // 循环所有的分片
            for($i=1; $i<=$totalSlices; $i++)
            {   
                $file = $MD5.'_'.$totalSlices.'_'.$i;
                // 读取第 i 号文件并写到大文件中
                fwrite($fp, file_get_contents(ROOT.'temp/'.$file));
                // 删除第 i 号临时文件
                unlink(ROOT.'temp/'.$file);
            }
            // 关闭文件
            fclose($fp);
            // 从 redis 中删除这个文件对应的编号这个变量
            $redis->zremrangebyrank('file_slices:' . $MD5,0,$uploadedCount); //del 分片存储
            $redis->del('file_slice_count:' . $MD5); // 删除分片 计数

            RD::setHash('files:'.$MD5, 'url', '/uploads/big/'.$MD5.'.'.$fileType); // 文件 map   合成分片 存储
            RD::setHash('files:'.$MD5, 'path', ROOT.'public/uploads/big/'.$MD5.'.'.$fileType); // 文件 map   合成分片 存储
        }

    }


}

?>