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

/*
*获取操作人员信息
*/
function get_admin_in($uid){
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

