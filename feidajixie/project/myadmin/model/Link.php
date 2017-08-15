<?php
namespace app\myadmin\model;
use think\Model;
/*
*友情链接数据模型
*/
/**
* 
*/
class Link extends Model
{
	
	/*
	*列表
	*/
	public function linklist(){
		return $this->order(array('id'=>'desc'))
					->paginate(10);
	}
	/*
	*判断该网站是否已经存在
	*/
	public function jumplinkurl($url=''){
		if(isset($url)){
			$jmplnk=$this->field('linkurl')->where(array('linkurl'=>$url))->find();
			if($jmplnk){
				return true;
			}
			return false;
		}
		return false;
	}
}
?>