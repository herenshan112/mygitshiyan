<?php
namespace app\english\model;
use think\Model;
/*
*友情链接数据模型
*/
/**
* 
*/
class Pagetype extends Model
{
	
	/*
	*首页栏目列表
	*$tyid     栏目
	*$page     显示几条
	*/
	public function index_typelist($tyid=0,$page=1){
		
		$whetj['pty_set']=1;
		$whetj['pty_fatherid']=$tyid;
		
		return $this->where($whetj)
						->order(array('pty_paixu'=>'asc','pty_id'=>'desc'))
						->limit($page)
						->select();
	}
	/*
	*产品全部栏目
	*/
	public function cont_tylist($tyid=0){
		$whetj['pty_set']=1;
		$whetj['pty_fatherid']=$tyid;
		
		return $this->where($whetj)
						->order(array('pty_paixu'=>'asc','pty_id'=>'desc'))
						->select();
	}
	/*
	*正许全部栏目
	*/
	public function cont_tylists($tyid=0){
		$whetj['pty_set']=1;
		$whetj['pty_fatherid']=$tyid;
		
		return $this->where($whetj)
						->order(array('pty_paixu'=>'asc','pty_id'=>'asc'))
						->select();
	}
	
}
?>