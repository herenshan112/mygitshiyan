<?php
namespace app\english\model;
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
	*底部友情链接列表
	*/
	public function footlink($page=10){
		return $this->where(array('linkset'=>1))->order(array('id'=>'desc'))
					->limit($page)
					->select();
	}
	
}
?>