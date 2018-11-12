<?php
// header("Content-type:image/png");
	// 图片类
class Image{

public static $info=[];
	//(检测 是否图片):bool/int 1.图片路径:string
public static function isImage($filePath){
	// var_dump($filePath);
	$types = '|.gif|.jpeg|.png|.bmp'; //定义检查的图片类型
	if(is_file(iconv('utf-8','gbk',$filePath))){
		// echo "存在文件";
		if ($info = @getimagesize($filePath)){

			 $ext = image_type_to_extension($info['2']);
			var_dump($info);
			var_dump($types);
			var_dump($ext);
            echo stripos($types,$ext);
            self::$info['isImage']=true;
			return stripos($types,$ext);
  		}
		
	}else{
		self::$info['isImage']=false;
		return 0;
	}
}

public static function getImageInfo($path){

        
    $pathinfo = fn::path_info($path);
    fn::auto_iconv($path);//反转编码
    $info = getimagesize($path);
    $ext_dot = image_type_to_extension($info['2']);
    $ext = image_type_to_extension($info['2'],false);
    
    $imgInfoArr['filename'] = $pathinfo['filename'];
    $imgInfoArr['basename'] = $pathinfo['basename'];
    $imgInfoArr['extension'] = $pathinfo['extension'];
    $imgInfoArr['dirname'] = $pathinfo['dirname'];
    $imgInfoArr['width'] = $info[0];
    $imgInfoArr['height'] = $info[1];
    $imgInfoArr['bits'] = $info['bits'];
    $imgInfoArr['ext'] = $ext;
    $imgInfoArr['.ext'] = $ext_dot;
    $imgInfoArr['ext_dot'] = $ext_dot;
    // var_dump($pathinfo,$imgInfoArr,$info,$ext);
    return $imgInfoArr;
}

	// fn.添加水印():bool. 1.图片路径 2.水印路径 3.是否随机名 4.是否覆盖图片(同名/原图片)
public static function printPicW($file,$file2,$isRandName=0,$isCover=0){

    if(image::is_resource_GD($file)){
        ;//空语句 占位
    }else {
        # code...
    
            // 判断 字符串编码
        $encode = mb_detect_encoding($file, array("UTF-8","GBK","GB2312","ASCII","BIG5"));
            if ($encode=="CP936"){
                $file = iconv("GBK","UTF-8",$file);
            }
            
        $path = $file;
        $path2 = $file2;
        if(@getimagesize(iconv('utf-8','gbk',$file)) || getimagesize($file)  && @getimagesize(iconv('utf-8','gbk',$file2))){
            $picType = fn::str_LastType($file);
            $picType = ($picType=='jpg') ? 'jpeg':$picType;
            // $picType = ($picType=='bmp') ? 'wbmp':$picType; // GD库 不支持 bmp 格式 的 图片
            $imagecreatefromType = "imagecreatefrom".$picType;
            $imageType = "image".$picType; // 输出 底图
            $fileName = fn::path_info($file)['filename'];// 增强版pathinfo

            $fileDirName = fn::path_info($file)['dirname'].'/';
            if($isRandName){
                $fileName = uniqid();
            }
                // 水印
            $picType2 = fn::str_LastType($file2);
            $picType2 = ($picType2=='jpg') ? 'jpeg':$picType2;
            $imageType2 = "image".$picType2; // 输出 底图
            $imagecreatefromType2 = "imagecreatefrom".$picType2;

            $gd_img = $imagecreatefromType(iconv('utf-8','gbk',$path));
            $gd_img2 = $imagecreatefromType2(iconv('utf-8','gbk',$path2));

        }else {
            echo "图片有问题<br>";
            return 0;
        }
    }
        //获取画布的宽度和高度
    $imgW = imagesx($gd_img);
    $imgH = imagesy($gd_img);

    list($b_w,$b_h) = getimagesize(iconv('utf-8','gbk',$path));
    list($f_w,$f_h) = getimagesize(iconv('utf-8','gbk',$path2));
    
		$i=0;
		$newFileName = $fileDirName.$fileName.'.'.fn::str_LastType($file);
        
        
    if(!$isCover){
        $newFileName = $fileDirName.$fileName.'.1.'.fn::str_LastType($file);
        // while (@is_file(iconv('utf-8','gbk',$newFileName))) {
        //     $newFileName = $fileDirName.$fileName.'.'.++$i.'.'.fn::str_LastType($file);
        // }
	}
	
    ob_clean();
            // 合成 
            // imagecopymerge
	if(imagecopy($gd_img,$gd_img2,$b_w-$f_w, $b_h-$f_h, 0, 0, $f_w, $f_h)){
		// echo "合成水印成功<br>";
		// self::$error['imagecopy']=true;
		if($imageType($gd_img,iconv('utf-8','gbk',$newFileName)))// 底图格式输出保存
		{
			// echo "成功输出合成图片<br>";
            // self::$error['imageTypeEcho']=true;


    
			return $newFileName;
			// imagepng($gd_img,iconv('utf-8','gbk',$newFileName));
		}
		
	}else {
		// self::$error['imagecopy']=false;
	}
	

}
	// fn.图片剪裁():$path. 1.图片路径|图片句柄,2.3,截取开始 坐标,4.5,截取宽高,
public static function imageCut($path,$Tx,$Ty,$BimgW,$BimgH,$newPath='',$isCover=false){
		if(getimagesize($path)==false)
			return false;
		$picType = fn::str_LastType($path);
        $picType = ($picType=='jpg') ? 'jpeg':$picType;
        // $picType = ($picType=='bmp') ? 'wbmp':$picType; // GD库 不支持 bmp 格式 的 图片
        $imagecreatefromType = "imagecreatefrom".$picType;
        $imageType = "image".$picType; // 输出 底图

        $dirname = fn::path_info($path)['dirname'];// 增强版pathinfo
        $basename = fn::path_info($path)['basename'];// 增强版pathinfo
        $extension = fn::path_info($path)['extension'];// 增强版pathinfo
        $filename = fn::path_info($path)['filename'];// 增强版pathinfo


		$i=0;
        // $newFileName = $dirname.$filename.'.'.$extension;
        $newFileName = $dirname.$filename.'.1.'.$extension;
    if(0){
        // !$isCover
        while (@is_file(iconv('utf-8','gbk',$newFileName))) {
            $newFileName = $dirname.$filename.'.'.++$i.'.'.$extension;
        }
	}




        // list($dirname,$basename,$extension,$filename);
        var_dump(fn::path_info($path),$path,$filename,$newFileName);
        // exit;
    $Bpath = $path;
    $thumbW = $BimgW;
    $thumbH = $BimgH;
    $thumb = imagecreatetruecolor($thumbW,$thumbH);
    // $thumb = imagecreatefromjpeg($Bpath1);
    $Bimg = $imagecreatefromType($Bpath);
    list($BimgW,$BimgH) = getimagesize($Bpath);
    $per = 1;
    $backColor = imagecolorallocate($thumb,150,150,150); //背景色
    // imagefill($thumb, 0, 0, $backColor);
    $thumbWP = $BimgW*$per; // 缩略版大小
    $thumbHP = $BimgH*$per;
    var_dump($thumbWP,$thumbHP);
    // $Hx =702-200;// 3，4. 最终 缩略图部分 在 画板 位置
    // $Hy =512-250;
    
    // $Tx = 702-200;// 略缩图在 缩略版 位置
    // $Ty = 512-200;
    $BimgW = 150+100;//  宽
    $BimgH = 150; //  高
    
    $thumbWP = $BimgW*$per;// 略缩版大小 $Bimg 可以说 就是 这个 的大小
    $thumbHP = $BimgH*$per;
    imagecopyresampled($thumb,$Bimg,0,0,$Tx,$Ty,$thumbWP,$thumbHP,$BimgW,$BimgH);

    ob_clean();
     $imageType($thumb,$newFileName);
     $arr['obj']=$thumb;
     $arr['path'] = $newFileName;
	 return $arr;


}
	// fn.图片缩放():$path. 1.图片路径|图片GD句柄,2.缩放倍数int,3.覆盖否 || 微调
public static function imageSF($path,$per=1,$isCover=false){
		if(getimagesize($path)==false)
			return false;
		$picType = fn::str_LastType($path);
        $picType = ($picType=='jpg') ? 'jpeg':$picType;
        // $picType = ($picType=='bmp') ? 'wbmp':$picType; // GD库 不支持 bmp 格式 的 图片
        $imagecreatefromType = "imagecreatefrom".$picType;
        $imageType = "image".$picType; // 输出 底图
        
        $dirname = fn::path_info($path)['dirname'];// 增强版pathinfo
        $basename = fn::path_info($path)['basename'];// 增强版pathinfo
        $extension = fn::path_info($path)['extension'];// 增强版pathinfo
        $filename = fn::path_info($path)['filename'];// 增强版pathinfo
        
        $newFileName = $dirname.$filename.'.'.$extension;
        
    if(!$isCover){
        // !$isCover
        $newFileName = $dirname.$filename.'.1.'.$extension;
        // while (@is_file(iconv('utf-8','gbk',$newFileName))) {
        //     $newFileName = $dirname.$filename.'.'.++$i.'.'.$extension;
        // }
	}

    $Bpath = $path;
    list($BimgW,$BimgH) = getimagesize($Bpath);
    $thumb = imagecreatetruecolor($BimgW*$per,$BimgH*$per);
    $Bimg = $imagecreatefromType($Bpath);
    
    $thumbWP = $BimgW*$per;// 略缩版大小 $Bimg 可以说 就是 这个 的大小
    $thumbHP = $BimgH*$per;
    imagecopyresampled($thumb,$Bimg,0,0,0,0,$thumbWP,$thumbHP,$BimgW,$BimgH);

    $imageType($thumb,$newFileName);
    $arr['obj']=$thumb;
    $arr['path'] = $newFileName;// path
	return $arr;
    // 返回 图像 句柄 和 文件名
    // 是否直接输出 ， 返回 句柄 ， 保存文件path

    //  $imageType($thumb);
}
    // fn.缩略图. 1.原图路径,2.预宽度,3.预高度,4.是否平铺,
public static function imgthumb($path,$thumbW,$thumbH,$is_zoom=true){

    $imgInfoArr = image::getImageInfo($path);
    $Bimg = imagecreatefromjpeg($path);
    
    $BimgW = $imgInfoArr['width'];// 缩放(显示部分占比)
    $BimgH = $imgInfoArr['height'];
    $BimgWH = $BimgW/$BimgH ;//缩放比例
    if($is_zoom){
        if($thumbW > $thumbH){
            $thumbW =  $BimgWH *$thumbH;
        }else {
            $thumbH =  $thumbW/$BimgWH ;
        }
    }
    $thumb = imagecreatetruecolor($thumbW,$thumbH);
    $thumbWP =$thumbW; // 缩略版大小  放大缩小
    $thumbHP = $thumbH;
    imagecopyresampled($thumb,$Bimg,0,0,0,0,$thumbWP,$thumbHP,$BimgW,$BimgH);
    $arr[""] = "";
    // ob_clean();
    // imagepng($thumb);
    // imagejpeg($thumb);



}
    // fn.判断资源是否GD对象():bool. 1.资源resource.
public static function is_resource_GD($resource){
    @$resource_type = get_resource_type($resource);
    if($resource_type=="gd")
        return true;
    return false;
}


// image 类 结束
}





?>