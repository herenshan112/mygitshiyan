<?php
namespace app\english\model;
use think\Model;
/*
*友情链接数据模型
*/
/**
* 
*/
class Search extends Model
{
	
	/*
	*首页搜索关键字列表
	*/
	public function index_search($page=10){
		return $this->where(array('ss_set'=>1))->order(array('ss_sum'=>'desc','ss_id'=>'desc'))
					->limit($page)
					->select();
	}
	
}
?>