<?php
namespace app\myadmin\model;
use think\Model;
/**
* 栏目数据模型
*/
class Pagetype extends Model
{
	
	/*
	*栏目列表
	*/
	public function getTypelist(){
		return $this->alias('p')
				->field('p.pty_fatherid,p.pty_set,p.pty_id,p.pty_title,p.pty_filetype,t.id,t.onetwo,t.setval,t.methodse')
				->join('__TYPES__ t','p.pty_filetype = t.id','LEFT')
				->where(array('p.pty_set'=>1,'t.onetwo'=>1,'t.setval'=>1))
				->select();
	}
	/*
	*获取一条
	*/
	public function getOnefind($id=0){
		$tjval['p.pty_set'] = 1;
		$tjval['t.onetwo'] = 1;
		$tjval['t.setval'] = 1;
		if($id != 0){
			$tjval['p.pty_id'] = $id;
		}
		return $this->alias('p')
				->field('p.pty_fatherid,p.pty_set,p.pty_id,p.pty_title,p.pty_filetype,t.id,t.onetwo,t.setval,t.mobelurl,t.methodse')
				->join('__TYPES__ t','p.pty_filetype = t.id','LEFT')
				->where($tjval)
				->find();
	}
}

?>