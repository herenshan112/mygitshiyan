<?php
namespace app\myadmin\model;
use think\Model;
/*
*管理员权限模型
*/
/**
* 
*/
class Grades extends Model
{
	
	/*
	*权限列表
	*/
	public function grdlist(){
		return $this->order(array('grd_id'=>'desc'))->paginate(10);
	}
	/*
	*判断权限是否已经存在
	*/
	public function jumpgrdtel($title=''){
		if(isset($title)){
			$jmptel=$this->field('grd_title')->where(array('grd_title'=>$title))->find();
			if($jmptel){
				return true;
			}
			return false;
		}
		return false;
	}
	/*
	*权限列表选用
	*/
	function selectlst(){
		return $this->field('grd_id,grd_setval,grd_title')->where(array('grd_setval'=>1))->order(array('grd_val'=>'asc','grd_id'=>'desc'))->select();
	}
}
?>