<?php
// 上传类
class Uploader{

    private static $_obj = null;
    private function ____construct(){}
    private function __clone(){} 

    public static function GetObj(){

        if(self::$_obj==null){
            self::$_obj = new self;
        }    
        return self::$_obj;
    }
// fn.上传文件():string. 1.
public static function upfile($file_name,$allow_type,$savedir="upload/",$ismany=0,$israndname=0){
	// method="POST" enctype="multipart/form-data"
	//$allow_type=array('jpeg','jpg','gif','png','bmp');// 添加 允许的  后缀
	//$savedir="upload/";// 存储的目录
    $llqchar = "utf-8";
    $fwqchar = "gbk";
		// 防止 忘记 jia '/' 
	if(gettype($saveDir)==='string'){
        if(substr($saveDir,-1)=='/'){
            $saveDir = iconv($llqchar,$fwqchar,$saveDir); //
        }else {
            $saveDir = iconv($llqchar,$fwqchar,$saveDir)."/";
        }
    }
    
	$files=$_FILES[$file_name];//
	// var_dump($files);


	
		// 判断 单文件 还是 多文件
	if(!$ismany){
		
		if($files['error']==0){
				// 获取后缀名
				$filetype = pathinfo($files['name'],PATHINFO_EXTENSION); // 获取文件类型
				$filename = pathinfo($files['name'],PATHINFO_FILENAME); // 获取文件名
				// iconc("utf8",$fwqchar,$files['name']); //乱码使用
				
				if($israndname){
					$filename = uniqid();
				}
					// 允许后缀
				if(in_array($filetype,$allow_type)){
						//判断目录 是否存在 不存在 创建 目录;
					if(!file_exists($savedir)){
						mkdir($savedir,0777,true);
					}

					if(move_uploaded_file($files["tmp_name"],$savedir.$filename.".".$filetype)){

						/*if(@getimagesize($savedir."/".$filename.".".$filetype)){
                                   $filename = $savedir."/".$filename.".".$filetype;
					               echo "上传成功！";

					           	}else{
					           	   // 如果文件格式不正确，那么就删除文件。
					           	   unlink($savedir."/".$filename.".".$filetype);
					           	   echo "文件不正确！";
					           	}*/
						
						echo "单文件上传成功:".$files['error']."<br>";
						return $savedir.$filename.".".$filetype; // 返回 一个 文件名
					}
				
				}

			}
			else {
				echo "单文件"."上传错误:".$files['error']."<br>";
				$upFileInfo[$files['name']]['error']=$files['error'];
			
			}

	}else{
			if(isset($_FILES[$file_name]))
		  foreach ($files['error'] as $key => $value) {
			if($value==0){
				// 获取后缀名
				//$lttype = pathinfo($fileArr['name'][$key],PATHINFO_EXTENSION);
				$filetype = pathinfo($files['name'][$key],PATHINFO_EXTENSION);
				$filename = pathinfo($files['name'][$key],PATHINFO_FILENAME);
				if($israndname){
					$filename = uniqid();
				}

				if(in_array($filetype,$allow_type)){
						
					if(!file_exists($savedir)){
						mkdir($savedir,0777,true);
						//判断目录 是否存在 不存在 创建 目录;
					}

					if(move_uploaded_file($files["tmp_name"][$key],$savedir.$filename.".".$filetype)){
						echo "第".($key+1)."文件"."上传成功:".$value."<br>";
						$upFilesInfos[$files['name'][$key]]=$value;
					}
				}
			}
			else{
				echo "第".($key+1)."文件"."上传错误:".$value."<br>";
				$upFilesInfos[$files['name'][$key]]=$value;
			
			}
		}
		var_dump($upFilesInfos);
		return $upFilesInfos;
	}
}

	// fn.上传 图片加水印():bool.	1.表单name:string,2允许类型array[]:string,3保存路径:string,/4.是否多图片bool,5.是否随机名bool,6.是否覆盖上传的同名图片bool./7.是否添加水印bool,8.水印图path:string,9.水印图是否覆盖原图.
public static function upPicPW($postName,$allowType,$saveDir="upload/",$isMany=false,$isRandName=0,$isCover=0,$isMark=false,$markPath='',$isWMark=0){
	// method="POST" enctype="multipart/form-data"
	//$allowType=array('jpeg','jpg','gif','png','bmp');// 添加 允许的  后缀
	//$saveDir="upload/";// 存储的目录
// var_dump(Uploader::upPicPW('img1',$allow_type,$savedir,false,0,1,true,'./upload/C_150.png',true)); // 单图
// var_dump( Uploader::upPicPW('img2',$allow_type,$savedir,true,0,0,true,'./upload/C_150.png',true)); //多图
	// 防止 忘记 jia '/' 
	if(gettype($saveDir)==='string'){
		if(substr($saveDir,-1)=='/'){
			$saveDir = iconv("utf-8",'gbk',$saveDir); //
        }else {
			$saveDir = iconv("utf-8",'gbk',$saveDir)."/";
        }
    }
	
	$files=$_FILES[$postName];//

	// 判断 单文件 还是 多文件
	if(!$isMany){
		// echo "单文件<br>";
		
		
		if($files['error']==0){
			// 获取后缀名
			$filetype = pathinfo($files['name'],PATHINFO_EXTENSION); // 获取文件类型
			$filename = fn::path_info($files['name'],PATHINFO_FILENAME)['filename']; // 获取文件名
			if($isRandName){
				$filename = uniqid();
			}
			
			$newFileName = iconv('utf-8','gbk',$saveDir).iconv('utf-8','gbk',$filename).".".$filetype;
			if(!$isCover){
				// echo "单上传不覆盖<br>";
				$i=0;
				while (@is_file(iconv('utf-8','gbk',$newFileName))) {
					$newFileName = iconv('utf-8','gbk',$saveDir).iconv('utf-8','gbk',$filename).'.'.++$i.'.'.$filetype;
				}
			}
			// 允许后缀
			if(in_array($filetype,$allowType)){
				//判断目录 是否存在 不存在 递归创建目录;
				if(!file_exists($saveDir)){
					mkdir($saveDir,0777,true);
				}
				
				if(move_uploaded_file($files["tmp_name"],$newFileName)){
					// echo "单上传成功<br>";
						
						if(@getimagesize($saveDir.iconv('utf-8','gbk',$filename).".".$filetype)){
							// echo $markPath."是图片<br>";
							if($isMark && image::isImage($markPath)){
								// echo "开始添加水印<br>";
								// echo $newFileName."<br>";
									// 原图 水印 随机 覆盖
								image::printPicW($newFileName,$markPath,0,$isWMark);// 添加水印
								// exit();
							}
							
							// var_dump($saveDir.$filename.".".$filetype);
							return $saveDir.$filename.".".$filetype; // 返回 一个 文件名

						}else{
							echo "上传错误的图片<br>";
							// 如果文件格式不正确,那么就删除文件。
							unlink($saveDir.iconv('utf-8','gbk',$filename).".".$filetype);
							return 0;
						}
						
					}
				
				}

			}
			else {
				echo "单文件"."上传错误:".$files['error']."<br>";
				$upFileInfo[$files['name']]['error']=$files['error'];
			
			}
			// 多图
	}else{
		// echo "多图<br>";
		// exit;
			if(isset($_FILES[$postName]))
		  foreach ($files['error'] as $key => $value) {
			if($value==0){
				// 获取后缀名
				$filetype = fn::path_info($files['name'][$key],PATHINFO_EXTENSION)['extension'];
				$filename = fn::path_info($files['name'][$key],PATHINFO_FILENAME)['filename']; // 获取文件名
				// var_dump($filename);
				
				if($isRandName){
					$filename = uniqid();
				}

				$newFileName = iconv('utf-8','gbk',$saveDir).iconv('utf-8','gbk',$filename).".".$filetype;
				if(!$isCover){
					// echo "多图上传覆盖<br>";
						$i=0;
						// iconv('utf-8','gbk',$newFileName)
					while (@is_file($newFileName)) {
						$newFileName = iconv('utf-8','gbk',$saveDir).iconv('utf-8','gbk',$filename).'.'.++$i.'.'.$filetype;
					}
				}

				if(in_array($filetype,$allowType)){
						
					if(!file_exists($saveDir)){
						mkdir($saveDir,0777,true);
						//判断目录 是否存在 不存在 创建 目录;
					}

					if(move_uploaded_file($files["tmp_name"][$key],$newFileName)){
						// echo "第".($key+1)."文件"."上传成功:".$value."<br>";
						if(@getimagesize($saveDir.iconv('utf-8','gbk',$filename).".".$filetype)){
							// echo "多图开始添加水印<br>";
							if($isMark && image::isImage($markPath)){
								echo $newFileName;
								
								echo image::printPicW($newFileName,$markPath,0,$isWMark);// 添加水印
								// exit();
							}

							$upFilesInfos[$files['name'][$key]]=$value;// 返回 一个 文件名

						}else{
							// 如果文件格式不正确,那么就删除文件。
							unlink($saveDir.iconv('utf-8','gbk',$filename).".".$filetype);
							$upFilesInfos[$files['name'][$key]]='wrong';
						}
					}
				}
			}
			else{
				echo "第".($key+1)."文件"."上传错误:".$value."<br>";
				$upFilesInfos[$files['name'][$key]]=$value;
			
			}
		}
		// var_dump($upFilesInfos);
		return $upFilesInfos;
	}
}





}









?>