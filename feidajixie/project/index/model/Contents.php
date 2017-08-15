<?php
namespace app\index\model;
use think\Model;
/*
*友情链接数据模型
*/
/**
* 
*/
class Contents extends Model
{
	
	/*
	*首页产品列表
	*$tyid     栏目
	*$page     显示几条
	*/
	public function index_prolist($tyid=0,$page=1,$attr=0){
		$whetj['cn_delt']=0;
		$whetj['cn_setval']=1;
		if($tyid != 0){
			$sun_type=get_suntype($tyid);
			$seltj=$tyid;
			foreach ($sun_type as $valtj) {
				$seltj.=','.$valtj;
			}
			$whetj['cn_type']=array('IN',$seltj);
		}
		if($attr != 0){
			$whetj['cn_attr']=array('INSTR',$attr);
		}
		return $this->where($whetj)
						->order(array('cn_id'=>'desc'))
						->limit($page)
						->select();
	}

	public function index_toplist($tyid=0,$page=1,$attr=0){
		$whetj['cn_delt']=0;
		$whetj['cn_setval']=1;
		if($tyid != 0){
			$sun_type=get_suntype($tyid);
			$seltj=$tyid;
			foreach ($sun_type as $valtj) {
				$seltj.=','.$valtj;
			}
			$whetj['cn_type']=array('IN',$seltj);
		}
		if($attr != 0){
			$whetj['cn_attr']=array('INSTR',$attr);
		}
		return $this->where($whetj)
						->order(array('cn_id'=>'desc'))
						->limit($page)
						->find();
	}
	/*
	*文档列表
	*/
	public function cont_list($typid=0,$sumval=6,$id=0){
		$whe['cn_delt']=0;
		$whe['cn_setval']=1;
		if($typid != 0){
			$sun_type=get_suntype($typid);
			$seltj=$typid;
			foreach ($sun_type as $valtj) {
				$seltj.=','.$valtj;
			}
			$whe['cn_type']=array('IN',$seltj);
		}
		if($id != 0){
			$whe['cn_id']=array('NEQ',$id);
		}
		return $this->alias('c')
					->field('c.cn_id,c.cn_title,c.cn_type,c.cn_time,c.cn_uid,c.cn_setval,c.cn_attr,t.pty_id,t.pty_title,c.cn_smallimg,c.cn_descr')
					->join('__PAGETYPE__ t','c.cn_type = t.pty_id','LEFT')
					->where($whe)
					->order(array('cn_id'=>'desc'))
					->paginate($sumval);
	}
	/*
	*相关产品
	*id 		文档id
	*tyid 		当前分类
	*sumval     显示个数
	*attr 		属性
	*/
	public function xgcp($id=0,$tyid=0,$sumval=6,$attr=0){
		$whetj['cn_delt']=0;
		$whetj['cn_setval']=1;
		if($tyid != 0){
			$sun_type=get_suntype($tyid);
			$seltj=$tyid;
			foreach ($sun_type as $valtj) {
				$seltj.=','.$valtj;
			}
			$whetj['cn_type']=array('IN',$seltj);
		}
		if($attr != 0){
			$whetj['cn_attr']=array('INSTR',$attr);
		}
		if($id != 0){
			$whetj['cn_id']=array('NEQ',$id);
		}
		return $this->where($whetj)
						->order(array('cn_id'=>'desc'))
						->limit($sumval)
						->select();
	}

	/*
	*上一篇
	*/
	public function precont($id=0,$tyid=0,$actval='pro'){
		$whetj['cn_delt']=0;
		$whetj['cn_setval']=1;

		if($tyid != 0){
			$sun_type=get_suntype($tyid);
			$seltj=$tyid;
			foreach ($sun_type as $valtj) {
				$seltj.=','.$valtj;
			}
			$whetj['cn_type']=array('IN',$seltj);
		}

		if($actval == 'pro'){
			$whetj['cn_id']=array('LT',$id);
		}else{
			$whetj['cn_id']=array('GT',$id);
		}
		
		$prolist=$this->field('cn_delt,cn_id,cn_setval,cn_title')
						->where($whetj)
						->order(array('cn_id'=>'desc'))
						->limit(1)
						->find();
		return $prolist;
	}

}
?>