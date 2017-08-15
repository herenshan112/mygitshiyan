<?php
namespace app\myadmin\model;
use think\Model;
/**
* 广告数据模型
*/
class Advtype extends Model{
	/*
	*广告位置列表
	*/
	public function listtype(){
		return $this->order(array('advt_id'=>'desc'))
					->paginate(10);
	}
	/*
	*判断广告位置是否存在
	*/
	public function jumpadvtitle($title=''){
		if(isset($title)){
			$jmpcn=$this->field('advt_id,advt_title')->where(array('advt_title'=>$title))->find();
			if($jmpcn){
				return false;
			}
			return true;
		}
		return true;
	}
}
?>