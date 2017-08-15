<?php
namespace app\publicclass;
use think\Db;
/*
*上传公共类
*/
/**
* 
*/
class uploadfile
{
	
	static function Upload($filecon){
		header('content-type:text/html charset:utf-8');
		header('content-type:text/html charset:utf-8');
		//$dir_base = "./upload/shop/"; //文件上传根目录

		$weburl=$_SERVER['HTTP_HOST'];   //获取当前域名

		//没有成功上传文件，报错并退出。
		if(empty($_FILES)) {
			echo "/Public/admin/images/onError.gif|1";
			exit(0);
		}
		$output = "<textarea>";
		$index = 0;	//$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
		foreach($_FILES as $file){
			$upload_file_name = $filecon['filenam'] . $index;//对应index.html FomData中的文件命名
		//	$filename = $_FILES[$upload_file_name]['name'];
		//	$gb_filename = iconv('gb2312','utf-8',$filename);	//名字转换成gb2312处理
			$filemjk=$_FILES[$upload_file_name]['name'];
			//$filemjk=pathinfo[basename];
			$suffix = strtolower(substr($_FILES[$upload_file_name]['name'],-4));

			$result = basename($filemjk,$suffix); //源文件名

			$dingdanhao = date("Y-m-dH-i-s");
			$dingdanhao = str_replace("-","",$dingdanhao);
			$dingdanhao .= rand(1000,999999);
			
			

			$gb_imgfilename='';
			if($filecon['nametrue'] != 1){
				$gb_imgfilename = iconv('gb2312','utf-8',$filemjk);	//名字转换成gb2312处
			}else{
				$fileimgnu=$dingdanhao;
				$imgfilename=$fileimgnu.$suffix;
				$gb_imgfilename = iconv('gb2312','utf-8',$imgfilename);	//名字转换成gb2312处
			}


			$datefile=date('Y_m_d').'/';

			$movefile=$filecon['dir_base'].$datefile;
			
			if (!is_dir($movefile)) mkdir($movefile, 0777); //上传目录不存在则创建

			//$output.=$filecon['dir_base'];
			//if (!is_dir($filecon['dir_base'].date('Y_m_d').'/')) {mkdir($filecon['dir_base'].date('Y_m_d').'/', 0777);}; //上传目录不存在则创建
		
				$isMoved = 0;  //默认上传失败
				$MAXIMUM_FILESIZE = $filecon['filesize'] * 1024;  	//文件大小限制	1K = 1 * 1024 B;
				$rEFileTypes = "/^\.(".$filecon['classtype']."){1}$/i"; 
				

				if($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE){
					if(preg_match($rEFileTypes, strrchr($gb_imgfilename, '.'))){
						if(@move_uploaded_file ( $_FILES[$upload_file_name]['tmp_name'], $movefile.$gb_imgfilename)){
							$isMoved = 3;
						}else{
							$isMoved = 4;
						}		//上传文件
					}else{
						$isMoved = 2;
					}
				}else{
					$isMoved = 1;
				}

			if($isMoved == 3){
				
				$output .= $datefile.$gb_imgfilename."|".$isMoved;
			}else {
				$output .= "/Public/admin/images/onError.gif"."|".$isMoved;
			}
			$index++;
		}
		echo $output."</textarea>";
	}


	//上传组件
	static function Uploadfileary($filecon){
		header('content-type:text/html charset:utf-8');
		header('content-type:text/html charset:utf-8');
		//$dir_base = "./upload/shop/"; //文件上传根目录

		$weburl=$_SERVER['HTTP_HOST'];   //获取当前域名

		//没有成功上传文件，报错并退出。
		if(empty($_FILES)) {
			echo "/Public/admin/images/onError.gif|1";
			exit(0);
		}
		$output = "<textarea>";
		$index = 0;	//$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
		foreach($_FILES as $file){
			$upload_file_name = $filecon['filenam'] . $index;//对应index.html FomData中的文件命名
		//	$filename = $_FILES[$upload_file_name]['name'];
		//	$gb_filename = iconv('gb2312','utf-8',$filename);	//名字转换成gb2312处理
			$filemjk=$_FILES[$upload_file_name]['name'];
			//$filemjk=pathinfo[basename];
			$suffix = strtolower(substr($_FILES[$upload_file_name]['name'],-4));

			$result = basename($filemjk,$suffix); //源文件名

			$dingdanhao = date("Y-m-dH-i-s");
			$dingdanhao = str_replace("-","",$dingdanhao);
			$dingdanhao .= rand(1000,999999);
			
			
			$gb_imgfilename='';

			if($filecon['nametrue'] != 1){
				$gb_imgfilename = iconv('gb2312','utf-8',$filemjk);	//名字转换成gb2312处
			}else{
				$fileimgnu=$dingdanhao;
				$imgfilename=$fileimgnu.$suffix;
				$gb_imgfilename = iconv('gb2312','utf-8',$imgfilename);	//名字转换成gb2312处
			}


			$datefile=date('Y_m_d').'/';

			$movefile=$filecon['dir_base'].$datefile;
			
			if (!is_dir($movefile)) mkdir($movefile, 0777); //上传目录不存在则创建


			//文件不存在才上传
			//if(!file_exists($filecon['dir_base'].$gb_imgfilename)) {
				$isMoved = 0;  //默认上传失败
				//$MAXIMUM_FILESIZE = $filecon['filesize'] * 1024 * 1024; 	//文件大小限制	1M = 1 * 1024 * 1024 B;
				$MAXIMUM_FILESIZE = $filecon['filesize'] * 1024;  	//文件大小限制	1K = 1 * 1024 B;
				$rEFileTypes = "/^\.(".$filecon['classtype']."){1}$/i"; 
				//if ($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE && 
				//	preg_match($rEFileTypes, strrchr($gb_imgfilename, '.'))) {	
				//	$isMoved = @move_uploaded_file ( $_FILES[$upload_file_name]['tmp_name'], $filecon['dir_base'].$gb_imgfilename);		//上传文件
				//}
				$fisizr=$_FILES[$upload_file_name]['size'];
				if($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE){
					if(preg_match($rEFileTypes, strrchr($gb_imgfilename, '.'))){
						if(@move_uploaded_file ( $_FILES[$upload_file_name]['tmp_name'], $movefile.$gb_imgfilename)){
							$isMoved = 3;
						}else{
							$isMoved = 4;
						}		//上传文件
					}else{
						$isMoved = 2;
					}
				}else{
					$isMoved = 1;
				}

			//}else{
			//	$isMoved = true;//已存在文件设置为上传成功
			//}



			if($isMoved == 3){
				//输出图片文件<img>标签
				//注：在一些系统src可能需要urlencode处理，发现图片无法显示，
				//    请尝试 urlencode($gb_filename) 或 urlencode($filename)，不行请查看HTML中显示的src并酌情解决。
				//$output .= "http://".$weburl.$filecon['dir_base'].$gb_imgfilename;
				$output .= $datefile.$gb_imgfilename."|".$isMoved."|".$fisizr.',';
			}else {
				$output .= "/Public/images/onError.gif"."|".$isMoved."|".$gb_imgfilename;
			}
			$index++;
		}
		echo $output."</textarea>";
	}
}
?>