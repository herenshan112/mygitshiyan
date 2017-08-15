<?php
namespace app\myadmin\model;
use think\Model;
/**
* 广告数据模型
*/
class Advcont extends Model{
	/*
	*广告列表
	*/
	public function listcont(){
		return $this->alias('c')
					->field('c.*,t.advt_id,t.advt_title,t.advt_fanwei,t.advt_setval')
					->join('__ADVTYPE__ t','c.advc_type = t.advt_id','LEFT')
					->order(array('advc_id'=>'desc'))
					->paginate(10);
	}
	/*
	*判断广告是否存在
	*/
	public function jumpadvtitle($title=''){
		if(isset($title)){
			$jmptle=$this->field('advc_title')->where(array('advc_title'=>$title))->find();
			if($jmptle){
				return true;
			}
			return false;
		}
		return false;
	}
}
?>