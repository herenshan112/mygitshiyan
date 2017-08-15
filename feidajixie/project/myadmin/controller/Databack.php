<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\publicclass\share;

/**
* 数据库备份还原操作
*/
class Databack extends Controller
{
	
	public function _initialize()
	{
		if(!jumpsession()){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("登陆超时！请重新登陆系统！！！");top.location.href="'.url('login/index').'"</script>';
        }
        $this->assign('sesval',session('admin'));
	}
	
	public function index(){
		/*$memcache = new \Memcache; //创建一个memcache对象
		$memcache->connect('127.0.0.1',11211);
		$getval=$memcache->get('ceshis');
		if($getval){
			echo $getval;
		}else{
			$memcache->set('ceshis',250,false,10);
			echo 123;
		}*/
		$ijk=1;
		$tabelist=Db::query('SHOW TABLE STATUS');
		$tablist="<div class='back_kj_top'><label><span class='back_tle1'>选择</span><span class='back_tle2'>表名</span><span class='back_tle3'>记录数</span><span class='back_tle4'>大小</span><span class='back_tle5'>操作</span></label><label><span class='back_tle1'>选择</span><span class='back_tle2'>表名</span><span class='back_tle3'>记录数</span><span class='back_tle4'>大小</span><span class='back_tle5 back_noline'>操作</span></label></div>";
		foreach ($tabelist as $key => $valtabel) {
			//var_dump($valtabel);
			if($ijk%2 != 0){
                $tablist.=" <div class='back_kj_foot'>";
            }
            $tablist.="<div class='back_kj_foot_label'><span class='back_tle1'><input type='checkbox' name='backname[]' id='backname[]' value='".$valtabel['Name']."' checked ></span>";
            $tablist.="<span class='back_tle2'>".$valtabel['Name']."</span>";
            $tablist.="<span class='back_tle3'>".$valtabel['Rows']."</span>";
            $tablist.="<span class='back_tle4'>";
            $tablist.=tablesum($valtabel['Data_length']+$valtabel['Index_length']);
            $tablist.="</span><span class='back_tle5";
            if($ijk%2 == 0){
                $tablist.=" back_noline";
            }
            $tablist.="'><a onclick=strubegin('".$valtabel['Name']."')>结构</a><a onclick=optbegin('".$valtabel['Name']."')>优化</a><a onclick=repabegin('".$valtabel['Name']."')>修复</a></span>";
            $tablist.="</div>";
            if($ijk%2 == 0){
                $tablist.=" </div>";
            }

           	$ijk++;
		}
		$this->assign('tablist',$tablist);
		return $this->fetch();
	}
	/*
	*优化数据库
	*/
	public function opttabel(){
		$tabelname=post_get('tabelname');
		if($tabelname){
			$tabecho=Db::query("OPTIMIZE TABLE ".$tabelname);
			
			if($tabecho['0']['Msg_type'] == 'status'){
				echo json_encode(array('code'=>1,'msg'=>'优化完成'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>$tabecho['0']['Msg_text']));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'没有找到数据表'));
		}
	}
	/*
	*修复表
	*/
	public function repairtabel(){
		$tabelname=post_get('tabelname');
		if($tabelname){
			$tabecho=Db::query("REPAIR TABLE ".$tabelname);
			//var_dump($tabecho);
			if($tabecho['0']['Msg_type'] == 'status'){
				echo json_encode(array('code'=>1,'msg'=>'修复完成'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>$tabecho['0']['Msg_text']));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'没有找到数据表'));
		}
	}
	//表结构
    public function tabelbast(){
        $tabelname=post_get('tabelname');
        if($tabelname){
        	$list=Db::query("SHOW CREATE TABLE ".$tabelname);
        	//var_dump($list);
        	$sql="<div class='back_kj_loklst'>";
	        foreach ($list as $url){ 
	            $sql.="<font color=#0033FF>".$url['Table']." </font>表的结构：<br>";
	            $sql.=self::backtypebest($url['Create Table']);
	        }
	        $sql.="</div>";
	        echo json_encode(array('code'=>1,'msg'=>$sql));
        }else{
			echo json_encode(array('code'=>0,'msg'=>'没有找到数据表'));
		}
        
        
    }
    //数据库表字符替换
	static function backtypebest($bestsql){
		$prefix=",";
		$pertwo="ENGINE";
		$tbnamed = str_replace($pertwo, "<br>ENGINE", $bestsql);
		$tbname = str_replace($prefix, ",<br>", $tbnamed);
		return $tbname;
	}
	/*
	*备份表结构
	*/
	public function backtabelall(){
		$backurl=Db::name('config')->field('id,databack')->order(array('id'=>'desc'))->find();
		if($backurl['databack'] != ''){
			$fileurl=$backurl['databack'].'/'.date('YmdHis').rand(1000,9999);
		}else{
			$fileurl=date('YmdHis').rand(1000,9999);
		}
		$echosql='';
		if(share::addfileurl($fileurl) == 1){
			$tabellist=Db::query('SHOW TABLES');
			$tabzida='Tables_in_'.config('database.database');
			foreach ($tabellist as $key => $valtabel) {
				$list=Db::query('SHOW CREATE TABLE '.$valtabel[$tabzida]);
				foreach ($list as $valjg) {
					$echosql.="-- 表的结构：".share::typebestth(config('database.prefix'),'#^@_',$valjg['Table'])." --\r\n";
	                $echosql.=share::typebestth(config('database.prefix'),'#^@_',$valjg['Create Table']);
	                $echosql.=";-- <xjx> --\r\n\r\n";
				}
			}
			$myfile=fopen(getcwd().'/'.$fileurl.'/All_tabels_'.config('database.database').'.txt','w');
			if($myfile){
				if(fwrite($myfile, $echosql)){
					echo json_encode(array('code'=>1,'msg'=>'数据库'.config('database.database').'表结构备份完成！','filename'=>$fileurl));
				}else{
					echo json_encode(array('code'=>0,'msg'=>'备份失败！文件没有写入权限！'));
				}
				fclose($myfile);
			}else{
				echo json_encode(array('code'=>0,'msg'=>'备份失败！文件没有写入权限！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'备份失败！目录没有写入权限！'));
		}
	}
	/*
	*单表备份
	*/
	public function onetabelback(){
		$tabelname=post_get('tabelname');
		$fileurl=post_get('fileurl');
		if($tabelname){
			if($fileurl){
				$onetbval='';
				$onetbvl='';
				$list=Db::query("SELECT * FROM ".$tabelname);
				if($list){
					$onetbvals="-- 表的数据：".share::typebestth(config('database.prefix'),'#^@_',$tabelname)." --\r\n";
					//$onetbval.="INSERT INTO `".share::typebestth(config('database.prefix'),'#^@_',$tabelname)."` VALUES\r\n";
					foreach ($list as $k=>&$v){
						
$keywod='(';
						$onetbvl.='(';
						foreach($v as $key=>$val){
							
						$keywod.=$key.',';
						

		                    if(is_numeric($val)){  
		                        $onetbvl.="'".$val."',";  
		                    }else if(is_null($val)){  
		                        $onetbvl.='NULL,';  
		                    }else{  
		                        //$sqlk.="'".addslashes($val)."'"; 
		                        $onetbvl.="'".addslashes($val)."',"; 
		                    }  
		                }
						$onetbvl=mb_substr($onetbvl, 0, -1);
                		$onetbvl.="),\r\n";
                		$keywod=mb_substr($keywod, 0, -1);
                		$keywod.=')';

					}
					$onetbvl=mb_substr($onetbvl, 0, -3);
					$onetbval=$onetbvals."INSERT INTO `".share::typebestth(config('database.prefix'),'#^@_',$tabelname)."` ".$keywod." VALUES\r\n".$onetbvl;
            		$onetbval.=";-- <xjx> --\r\n\r\n";
            		//echo $keywod;
				}else{
					$onetbval="-- 表的数据：".share::typebestth(config('database.prefix'),'#^@_',$tabelname)." --\r\n;-- <xjx> --\r\n\r\n";
				}
				$myfile=fopen(getcwd().'/'.$fileurl.'/'.$tabelname.'.txt','w');
				if($myfile){
					if(fwrite($myfile, $onetbval)){
						echo json_encode(array('code'=>1,'msg'=>'数据表'.$tabelname.'表备份完成！','filename'=>$fileurl));
					}else{
						echo json_encode(array('code'=>0,'msg'=>'数据表'.$tabelname.'备份失败！文件没有写入权限！'));
					}
					fclose($myfile);
				}else{
					echo json_encode(array('code'=>0,'msg'=>'备份失败！文件没有写入权限！'));
				}
				
			}else{
				echo json_encode(array('code'=>0,'msg'=>'备份失败！没有保存路径！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'备份失败！没有数据表！'));
		}
	}
	/*
	*还原数据库
	*/
	public function huanyuan(){
		$nextflie=post_get('nextfilename');
		$alltabels='';
		$filenameval='';
		$filelit=Db::name('config')->order(array('id'=>'desc'))->find();
		if($filelit['databack'] != ''){
			$backconurl=getcwd().'/'.$filelit['databack'];
			if($nextflie){
				$backconurl.='/'.$nextflie;
			}
			if(is_dir($backconurl)){
				$dh = opendir($backconurl);
				while (($file = readdir($dh)) !== false) {
					if($file != '..' & $file != ''){
						if(!strstr($file,'All_tabels_')){
							if($nextflie != '' && $nextflie != '.'){
								if(is_file($backconurl.'/'.$file)){
									$filenameval[]=array(
										'filenam'			=>				str_replace('.txt','',$file),
										'filetype'			=>				1
									);
								}else{
									$filenameval[]=array(
										'filenam'			=>				str_replace('.txt','',$file),
										'filetype'			=>				0
									);
								}
							}else{
								if($file != '.' & $file != ''){
									if(is_file($backconurl.'/'.$file)){
										$filenameval[]=array(
											'filenam'			=>				str_replace('.txt','',$file),
											'filetype'			=>				1
										);
									}else{
										$filenameval[]=array(
											'filenam'			=>				str_replace('.txt','',$file),
											'filetype'			=>				0
										);
									}
								}
							}
							
						}else{
							$alltabels=$file;
						}
						
					}
				}
				
			}
		}else{
			if($nextflie){
				$backconurl.=getcwd().'/'.$nextflie;
			}else{
				$backconurl.=getcwd();
			}
			if(is_dir($backconurl)){
				$dh = opendir($backconurl);
				while (($file = readdir($dh)) !== false) {
					if(!strstr($file,'All_tabels_')){
						if($nextflie != '' && $nextflie != '.'){
							if(is_file($backconurl.'/'.$file)){
								$filenameval[]=array(
									'filenam'			=>				str_replace('.txt','',$file),
									'filetype'			=>				1
								);
							}else{
								$filenameval[]=array(
									'filenam'			=>				str_replace('.txt','',$file),
									'filetype'			=>				0
								);
							}
						}else{
							if($file != '.' & $file != ''){
								if(is_file($backconurl.'/'.$file)){
									$filenameval[]=array(
										'filenam'			=>				str_replace('.txt','',$file),
										'filetype'			=>				1
									);
								}else{
									$filenameval[]=array(
										'filenam'			=>				str_replace('.txt','',$file),
										'filetype'			=>				0
									);
								}
							}
						}
					}else{
						$alltabels=$file;
					}
				}
			}
		}
		//var_dump($filenameval);
		$this->assign('tabellst',$filenameval);
		$this->assign('nextflie',$nextflie);
		$this->assign('alltabels',$alltabels);
		return $this->fetch();
	}
	/*
	*生成备份目录
	*/
	public function backfileurl(){
		$backurl=Db::name('config')->field('id,databack')->order(array('id'=>'desc'))->find();
		if($backurl['databack'] != ''){
			$fileurl=$backurl['databack'].'/'.date('YmdHis').rand(1000,9999);
		}else{
			$fileurl=date('YmdHis').rand(1000,9999);
		}
		if(share::addfileurl($fileurl) == 1){
			echo json_encode(array('code'=>1,'msg'=>'备份目录创建完成，开始写入备份文件！','filename'=>$fileurl));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'备份失败！目录没有写入权限！'));
		}
	}
	/*
	*单表写入副本
	*/
	public function onelbackfb(){
		$tabelname=post_get('tabelname');
		$fileurl=post_get('fileurl');
		$wreval='';
		$keywod='';
		$onetbvl='';
		$insetsql='';
		if($tabelname){
			if($fileurl){
				$wreval="DROP TABLE IF EXISTS `".share::typebestth(config('database.prefix'),'#^@_',$tabelname)."`;\r\n";
				$list=Db::query('SHOW CREATE TABLE '.$tabelname);
	            $wreval.=share::typebestth(config('database.prefix'),'#^@_',$list[0]['Create Table']);
	            $wreval.=";\r\n";
				$listval=Db::query("SELECT * FROM ".$tabelname);
				if($listval){
					
					$jbdq=0;
					$jbq=count($listval)-1;
					foreach ($listval as $k=>&$v){
						
						$onetbvl.='(';
						$keywod='(';
						foreach($v as $key=>$val){
							$keywod.=$key.',';
							if(is_numeric($val)){  
		                        $onetbvl.="'".$val."',";  
		                    }else if(is_null($val)){  
		                        $onetbvl.='NULL,';  
		                    }else{  
		                        
		                        $onetbvl.="'".addslashes($val)."',"; 
		                    }  
						}

						$onetbvl=mb_substr($onetbvl, 0, -1);
						$onetbvl.="),";
						if($jbq == $k || ($k+1) % 10 == 0 ){
							$onetbvl=mb_substr($onetbvl, 0, -1);
							$onetbvl.=";\r\n";
						}

						
						$keywod=mb_substr($keywod, 0, -1);
						$keywod.=')';
						$jbdq++;
						
						if($jbdq >= 10){
							if($jbq >10){
								$insetsql.="INSERT INTO `".share::typebestth(config('database.prefix'),'#^@_',$tabelname)."` ".$keywod." VALUES ".$onetbvl."\r\n";
							}else{
								$insetsql="INSERT INTO `".share::typebestth(config('database.prefix'),'#^@_',$tabelname)."` ".$keywod." VALUES ".$onetbvl."\r\n";
							}
							$onetbvl='';
							$jbdq=0;
						}
					}
					$insetsql.="INSERT INTO `".share::typebestth(config('database.prefix'),'#^@_',$tabelname)."` ".$keywod." VALUES ".$onetbvl."\r\n";
				
					$wreval.=$insetsql;
            		
            		
				}
				
				//echo $wreval;
				$fedizhi=getcwd().'/'.$fileurl.'/'.$tabelname.'.txt';
				echo share::writefile($fedizhi,$tabelname,$wreval);
				//$myfile=fopen($fedizhi,'w');
				//echo json_encode(array('code'=>0,'msg'=>$fedizhi));
				//echo 1;
			}else{
				echo json_encode(array('code'=>0,'msg'=>'备份失败！没有保存路径！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'备份失败！没有数据表！'));
		}
	}
	/*
	*单表还原
	*/
	public function onetbhuanya(){
		$tabelname=post_get('tbnam');
		$fileurl=post_get('tburl');
		$zxsqlery='';
		$setpd=0;
		if($tabelname){
			if($fileurl){
				$backurl=Db::name('config')->field('id,databack')->order(array('id'=>'desc'))->find();
				if($backurl['databack'] != ''){
					$fileurl=getcwd().'/'.$backurl['databack'].'/'.$fileurl;
				}else{
					$fileurl=getcwd().'/'.$fileurl;
				}
				if(is_dir($fileurl)){
					$fileurl.='/'.$tabelname.'.txt';
					if(is_file($fileurl)){
						$readtxt=fopen($fileurl, 'r');
						if($readtxt){
							$txtcont=fread($readtxt, filesize($fileurl));
							fclose($readtxt);
							if($txtcont != ''){
								$insetary=explode("\r\n", $txtcont);
								foreach ($insetary as $key => $value) {
									if($value != ''){
										$zxsqljg=(share::execurte_sql($value));
										if($zxsqljg['code'] == 0){
											$zxsqlery[]=array('error_sql'=>$zxsqljg['msg']);
											$setpd=1;
										}
									}
								}
								if($setpd == 1){
									echo json_encode(array('code'=>1,'msg'=>'还原完成！','infor'=>$zxsqlery));
								}else{
									echo json_encode(array('code'=>1,'msg'=>'还原完成！','infor'=>''));
								}
							}else{
								echo json_encode(array('code'=>1,'msg'=>'还原完成！没有任何操作！','infor'=>''));
							}
						}else{
							echo json_encode(array('code'=>0,'msg'=>'还原失败！还原文件读取失败！'));
						}
					}else{
						echo json_encode(array('code'=>0,'msg'=>'还原失败！没有找到数据表保存文件！'));
					}
				}else{
					echo json_encode(array('code'=>0,'msg'=>'还原失败！没有找到数据表保存目录！'));
				}
			}else{
				echo json_encode(array('code'=>0,'msg'=>'还原失败！没有找到保存路径！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'还原失败！没有数据表！'));
		}
	}



}
?>