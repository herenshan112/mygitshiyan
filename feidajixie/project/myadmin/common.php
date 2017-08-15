<?php
use think\Db;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//后台判断是否登陆
function jumpsession(){
	if(session('?admin')){
		$seslog=session('admin');
		$webset=websetcont();
		$gqsj=$webset['session_time']*60;
		if(time()-$seslog['time'] > $gqsj){
			return false;
		}else{
			session('admin',array(
				'time'			=>			time(),
				'uid'			=>			$seslog['uid'],
				'grdval'		=>			$seslog['grdval'],
				'grdqz'			=>			$seslog['grdqz'],
			));
			return session('admin');
		}
	}else{
		return false;
	}
}
//判断选用哪个模版
function jumpmodees($tyid=0){
	if($tyid != 0){
		$tjval['p.pty_set'] = 1;
		$tjval['t.onetwo'] = 1;
		$tjval['t.setval'] = 1;
		if($tyid != 0){
			$tjval['p.pty_id'] = $tyid;
		}
		$tycont= Db::name('pagetype')->alias('p')
				->field('p.pty_fatherid,p.pty_set,p.pty_id,p.pty_title,p.pty_filetype,t.id,t.onetwo,t.setval,t.mobelurl,t.methodse')
				->join('__TYPES__ t','p.pty_filetype = t.id','LEFT')
				->where($tjval)
				->find();
		return $tycont['methodse'];
	}else{
		return 'index';
	}
}
/*
*获取操作人员信息
*/
function get_admin($uid){
	if(isset($uid)){
		if($uid !== 0){
			$ucont=Db::name('admin')->field('ad_id,ad_username,ada_id,ada_name')->join('__ADMINDATA__','ad_id = ada_id','LEFT')->where(array('ad_id'=>$uid))->find();
			if($ucont){
				return $ucont['ad_username'].'<br>'.$ucont['ada_name'];
			}else{
				return '超级管理员';
			}
		}else{
			return '超级管理员';
		}
	}else{
		return '获取失败';
	}
}
/*
*广告位置有效范围
*/
function advyxfw($fwid){
	if(isset($fwid)){
		if($fwid !=0){
			$tyls=Db::name('pagetype')->field('pty_id,pty_title')->where(array('pty_id'=>$fwid))->find();
			if($tyls){
				return $tyls['pty_title'];
			}
			return '所有页面';
		}
		return '所有页面';
	}
	return '所有页面';
}
