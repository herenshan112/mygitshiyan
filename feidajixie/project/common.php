<?php
use think\Db;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/*
*加密算法
*/
function jiamimd5($code,$appkey=''){
	if(isset($appkey)){
		if($appkey != ''){
			return md5(md5(md5($code).md5($appkey)).$appkey);
		}
		return md5(md5(md5($code).md5(config('app_key'))).config('app_key'));
	}
	return md5(md5(md5($code).md5(config('app_key'))).config('app_key'));
}

// 定义一个函数获取客户端IP地址
function getIP(){
    global $ip;
    if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if(getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else $ip = "127.0.0.1";
    return $ip;
}
/*
*获取提交数值
*/
function post_get($name){
	if($name != ''){
		if(isset($_POST[$name])){
			return $_POST[$name];
		}else{
			if(isset($_GET[$name])){
				return $_GET[$name];
			}else{
				return '';
			}
		}
	}else{
		return '';
	}
}
//判断表的大小
function tablesum($num){
	$bastfh=array('B','KB','MB','GB','TB');
	for($key=0;$key<count($bastfh);$key++){
		if($num>=pow(2,10*$key)-1){ //1023B 会显示为 1KB
			$bastsize=(ceil($num/pow(2,10*$key)*100)/100).' '.$bastfh[$key];
		}	 
	}
	return $bastsize;
}
//栏目树
function LinearArray($cate, $delimiter = '———', $parent_id = 0, $level = 10){
		$arr=array();
		foreach ($cate as $val) {
			if($val['pty_fatherid'] == $parent_id){
				//$val['level'] = $level + 1;
				$val['level'] = $level + 5;
				if($parent_id == 0){
					$val['delimiter']='';
				}else{
					$val['delimiter']=$delimiter;
				}
                
                $arr[] = $val;
                $arr = array_merge($arr, LinearArray($cate, $delimiter, $val['pty_id'], $val['level']));
			}
		}
		return $arr;
}
function toLevend($cate, $delimiter = '———', $parent_id = 0, $level = 0){
	$arr=array();
	foreach ($cate as $val) {
		if($val['pty_fatherid'] == $parent_id){
			$val['level'] = $level + 1;
			//$val['level'] = $level + 5;
			if($parent_id == 0){
				$val['delimiter']='';
			}else{
				$val['delimiter'] = str_repeat($delimiter, $level);
			}
            
            //$val['delimiter']=$delimiter;
            $arr[] = $val;
            $arr = array_merge($arr, toLevend($cate, $delimiter, $val['pty_id'], $val['level']));
		}
	}
	return $arr;
}


/*
*获取网站配置
*/
function websetcont(){
	$valjump=Db::name('config')->order(array('id'=>'desc'))->find();
	return $valjump;
}

/*
*栏目类型导航
*/
function wuxianxj($arycont,$parent_id=0,$level = 0){
	$sescv=session('admin');
	$html='';
	foreach ($arycont as $valty) {
		if(is_array($valty)){
			if($valty['pty_fatherid'] == $parent_id){
				$html.='<li><label><i class="openclost icon-folder-close-alt" style="margin-left: '.$level.'px;"></i>';
				$html.='<em>'.$valty['pty_title'].' [ID:'.$valty['pty_id'].'<b class="icon-unlock lock_color shuojin_ont"></b>]</em>';
				$html.='<span><a class="icon-eye-open" title="预览" href="#"></a>';
				if($valty['onetwo'] != 0){
					if(in_array(10, explode('|',$sescv['grdval']))){
						$html.='<a class="icon-tasks C_tables" title="内容" href="'.url($valty['methodse'],array('action'=>'lis','tyid'=>$valty['pty_id'])).'" data-index="16" data-url="'.url('pagecont/index').'"></a>';
					}
				}
				
				//$html.='<a class="icon-tasks C_tables" title="内容"  data-index="16"></a>';
				if(in_array(9, explode('|',$sescv['grdval']))){
					$html.='<a class="icon-sitemap" title="添加子类" href="'.url('pagetype',array('action'=>'add','sonid'=>$valty['pty_id'])).'"></a>';
				}
				if(in_array(11, explode('|',$sescv['grdval']))){
					$html.='<a class="icon-edit" title="修改" href="'.url('pagetype',array('action'=>'eite','id'=>$valty['pty_id'])).'"></a>';
				}
				if(in_array(12, explode('|',$sescv['grdval']))){
					$html.="<a class='icon-trash' title='删除'' onclick=deltall(".$valty['pty_id'].",'deltcont','fukj','".url('delttypes')."')></a>";
				}
				$html.='</span></label>';
				$html.=wuxianxj($arycont,$valty['pty_id'],$level+10);
				$html.='</li>';
			}
		}
	}
	return $html ? '<ul>'.$html.'</ul>' : $html ;
}
/*
*删除分类
*/
function delttype($lmary,$tyid){
	$idval=$tyid.',';
	foreach ($lmary as $valty) {
		if($valty['pty_fatherid'] == $tyid){
			$idval.=$valty['pty_id'].',';
			$idval.=delttype($lmary,$valty['pty_id']);
		}
	}
	//$idval.=$tyid;
	return rtrim($idval,',');
}
/*
*获取栏目模版地址
*/
function gettypeurl($tyidv){
	if($tyidv != 0){
		$fileurl=Db::name('pagetype')
					->alias('p')
					->field('p.pty_id,p.pty_filetype,t.id,t.mobelurl')
					->join('__TYPES__ t','p.pty_filetype = t.id','LEFT')
					->where(array('pty_id'=>$tyidv))
					->find();
		if($fileurl){
			//$mbname=filetyzz($fileurl['mobelurl']);
			return filetyzz($fileurl['mobelurl']);
		}else{
			return 'pagecont';
		}
	}else{
		return 'pagecont';
	}
}
/*
*文件类型组装
*/
function filetyzz($fileurl){
	if($fileurl){
		$mbname=explode('.',$fileurl);
		return '/template/'.$mbname[0];
	}
	return 'index';
}
/*
*表默认数据
*/
function gettabelmrval($tabname){
	if($tabname){
		$tablist=Db::query('SHOW COLUMNS FROM `'.$tabname.'`');
		//var_dump($tablist);
		$fhmod=array();
		foreach ($tablist as $mdkey => $valmd) {
			$fhmod[]=array(
				'title'			=>			$valmd['Field'],
				'value'			=>			$valmd['Default'],
				'type'			=>			$valmd['Type']
				//$valmd['Field']		=>		$valmd['Default']
			);
		}

		if(empty($fhmod)){
			return 0;
		}
		$fhmods=array();
		foreach ($fhmod as $key => &$value) {
			if(strpos($value['type'],'varchar') !== false){
				if($value['value'] == "0"){
					$fhmods[$value['title']]='';
				}else{
					$fhmods[$value['title']]=$value['value'];
				}
			}else{
				$fhmods[$value['title']]=$value['value'];
			}
			
		}
		if(empty($fhmods)){
			return 0;
		}
		return $fhmods;
	}else{
		return 0;
	}
}
/*
*获取一周日期
*/
function get_riqi($week){
        $whichD=date('w',strtotime($week));
        $weeks=array();
        for($i=1;$i<8;$i++){
            if($i<$whichD){
                $date=strtotime($week)-($whichD-$i)*24*3600;
            }else{
                $date=strtotime($week)+($i-$whichD)*24*3600;
            }
            $weeks[$i]=date('Y-m-d',$date);
         
        }
        return $weeks;
}

/*
*获取栏目子类
*/
function get_suntype($tyid_fast=0){
	$arrval=array();
	$tyls=Db::name('pagetype')->where(array('pty_set'=>1,'pty_fatherid'=>$tyid_fast))->select();
	foreach ($tyls as $keyty => $valty) {
		$arrval[]=$valty['pty_id'];
		$arrval=array_merge($arrval,get_suntype($valty['pty_id']));
	}
	return $arrval;
}
//截取字符串
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){

  if(function_exists("mb_substr")){

     if($suffix){

      return mb_substr($str, $start, $length, $charset)."...";

     }else{

      return mb_substr($str, $start, $length, $charset);

     }

  }elseif(function_exists('iconv_substr')) {

       if($suffix){

            return iconv_substr($str,$start,$length,$charset)."...";

       }else{

        return iconv_substr($str,$start,$length,$charset);

       }

  }

    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";

    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";

    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";

    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

    preg_match_all($re[$charset], $str, $match);

    $slice = join("",array_slice($match[0], $start, $length));

    if($suffix){ 

        return $slice."...";

    }else{

        return $slice;

    }

}

//字符替换
function daitizifu($strkey='网站程序',$string='网站程序'){
	return mb_ereg_replace($strkey,'<font color="#ff0000">'.$strkey.'</font>',$string);
}
/*
*查询转换链接
*/
function seaclokurl($tyid=0){
	$djl=Db::name('pagetype')->field('pty_id,pty_fatherid')->where('pty_id',$tyid)->find();
	if($djl){
		if($djl['pty_fatherid'] != 0 && $djl['pty_fatherid'] != 5){
			$tyid=$djl['pty_fatherid'];
		}
	}
	switch ($tyid) {
		case 1:
		case 4:
		case 9:
			return url('index/about');
			break;
		case 2:
		case 14:
		case 15:
		case 16:
			return url('product/details');
			break;
		case 3:
		case 11:
		case 12:
		case 13:
			return url('news/details');
			break;
		case 7:
			return url('myserver/details');
			break;
		case 8:
			return url('place/details');
			break;
		case 10:
			return url('styleing/details');
			break;
		case 17:
			return url('video/details');
			break;
		default:
			return url('news/details');
			break;
	}
}