<?php
namespace app\myadmin\model;
use think\Model;
/**
* 管理员数据模型
*/
class Admindata extends Model{
	/*
	*获取会员资料
	*/
	public function listadcom($uid=''){
		if(isset($uid)){
			$conval=$this->alias('d')
							->field('d.*,a.ad_id,a.ad_username,a.ad_grod,a.ad_setval,a.ad_lasttime,a.ad_lastip,g.grd_id,g.grd_title,g.grd_setval')
							->join('__ADMIN__ a','d.ada_id = a.ad_id','LEFT')
							->join('__GRADES__ g','a.ad_grod = g.grd_id','LEFT')
							->where(array('d.ada_id'=>$uid))
							->find();
			if($conval){
				return $conval;
			}
			return false;
		}
		return false;
	}
}
?>