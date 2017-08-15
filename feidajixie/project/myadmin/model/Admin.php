<?php
namespace app\myadmin\model;
use think\Model;
/**
* 管理员数据模型
*/
class Admin extends Model{
	/*
	*管理员列表
	*/
	public function adminlist(){
		return $this->alias('a')
					->field('a.*,g.grd_id,g.grd_title,d.ada_name,d.ada_tel')
					->join('__GRADES__ g','a.ad_grod = g.grd_id','LEFT')
					->join('__ADMINDATA__ d','a.ad_id = d.ada_id','LEFT')
					->order(array('a.ad_id'=>'desc'))
					->paginate(10);
	}
	/*
	*判断管理员是否存在
	*/
	public function jumpadmin($usenam=''){
		if(isset($usenam)){
			$jmpus=$this->field('ad_username')->where(array('ad_username'=>$usenam))->find();
			if($jmpus){
				return true;
			}
			return false;
		}
		return false;
	}
	/*
	*判断用户名和密码是否正确
	*/
	public function jumpuspwd($usename='',$pwd=''){
		if(isset($usename) && isset($pwd)){
			$whetj=array(
				'a.ad_username'			=>			$usename,
				'a.ad_pwdval'			=>			jiamimd5($pwd)
			);
			$jmpusary=$this->alias('a')
					->field('a.*,g.grd_id,g.grd_title,g.grd_setval,g.grd_arycon,g.grd_val')
					->join('__GRADES__ g','a.ad_grod = g.grd_id','LEFT')
					->where($whetj)
					->find();
			if($jmpusary){
				return $jmpusary;
			}
			return false;
		}
		return false;
	}
	/*
	*判断原始密码是否正确
	*/
	public function ysmmjump($uid='',$pwd=''){
		if(isset($uid) && isset($pwd)){
			$whetj=array(
				'ad_id'			=>			$uid,
				'ad_pwdval'		=>			jiamimd5($pwd)
			);
			$jmppwd=$this->field('ad_id,ad_pwdval')->where($whetj)->find();
			if($jmppwd){
				return true;
			}
			return false;
		}
		return false;
	}

	/*
	*管理员列表(图标)
	*/
	public function lstadm(){
		return $this->alias('a')
					->field('a.*,d.ada_name,d.ada_tel')
					->join('__ADMINDATA__ d','a.ad_id = d.ada_id','LEFT')
					->order(array('a.ad_id'=>'desc'))
					->select();
	}
}
?>