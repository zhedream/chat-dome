<?php
// 上传类
class Uploader{
        // 单里 模式  上传类 值需要一个
    private static $_obj = null;
    private function ____construct(){}
    private function __clone(){} 

    public static function GetObj(){

        if(self::$_obj==null){
            self::$_obj = new self;
        }    
        return self::$_obj;
    }
// fn.上传文件():string. 1. 表单post name,允许类型，保存路径
public static function upfile($file_name,$allow_type,$savedir="upload/"){

    $llqchar = "utf-8";
    $fwqchar = "gbk";

    
	$files=$_FILES[$file_name];//
		
		if($files['error']==0){
				// 获取后缀名
				$filetype = pathinfo($files['name'],PATHINFO_EXTENSION); // 获取文件类型
				$filename = pathinfo($files['name'],PATHINFO_FILENAME); // 获取文件名	

					// 允许后缀
				if(in_array($filetype,$allow_type)){
						//判断目录 是否存在 不存在 创建 目录;
					if(!file_exists($savedir)){
						mkdir($savedir,0777,true);
					}

					if(move_uploaded_file($files["tmp_name"],$savedir.$filename.".".$filetype)){
						
						echo "单文件上传成功:".$files['error']."<br>";
						return $savedir.$filename.".".$filetype; // 返回 一个 文件名
					}
				
				}

			}
			else {
				echo "单文件"."上传错误:".$files['error']."<br>";
				$upFileInfo[$files['name']]['error']=$files['error'];
			
			}
	
}

}









?>