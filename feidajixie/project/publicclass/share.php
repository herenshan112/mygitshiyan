<?php
namespace app\publicclass;
use think\Db;
/**
* 公共类文件
*/
class share 
{
	
	/*
	*创建目录
	*/
	static function addfileurl($fileurl='backbest'){
		//$fileurl=getcwd().'/'.$fileurl;
		if(!is_dir($fileurl)){
			self::addfileurl(dirname($fileurl));
			if(!mkdir($fileurl, 0777)){
				return 0;
			}
		}
		return 1;
	}

	//数据库表字符替换
	static function typebestth($prefix,$listfix,$bestsql){
		$tbname = str_replace($prefix, $listfix, $bestsql);
		return $tbname;
	}

	/*
	*写入文件
	*/
	static function writefile($fielurl='',$tabelname='崬奋科技',$fielval=''){
		$myfile=fopen($fielurl,'w');
		if($myfile){
			if(fwrite($myfile, $fielval)){
				echo json_encode(array('code'=>1,'msg'=>'数据表'.$tabelname.'表备份完成！'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>'数据表'.$tabelname.'备份失败！文件没有写入权限！'));
			}
			fclose($myfile);
		}else{
			echo json_encode(array('code'=>0,'msg'=>'备份失败！文件没有写入权限！'));
		}
	}

	/*
	*执行sql语句
	*/
	static function execurte_sql($sql){
		$strsqlv=self::typebestth('#^@_',config('database.prefix'),$sql);
		if(Db::execute($strsqlv)){
			return array('code'=>1,'msg'=>'');
		}else{
			return array('code'=>0,'msg'=>$strsqlv);
		}
	}
}
?>