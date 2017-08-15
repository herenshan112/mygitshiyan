<?php
namespace app\english\model;
use think\Model;
/*
*友情链接数据模型
*/
/**
* 
*/
class Advcont extends Model
{
	
	/*
	*首页栏目列表
	*$tyid     栏目
	*$page     显示几条
	*/
	public function index_advlist($tyid=0,$page=1){
		
		$whetj['a.advc_setval']=1;
		$whetj['t.advt_setval']=1;
		$whetj['a.advc_type']=$tyid;
		$whetj['a.advc_images']=array('NEQ','');

		return $this->alias('a')
						->field('a.*,t.advt_id,t.advt_setval')
						->join('__ADVTYPE__ t','a.advc_type = t.advt_id','LEFT')
						->where($whetj)
						->order(array('a.advc_id'=>'desc'))
						->limit($page)
						->select();
	}
	
}
?>