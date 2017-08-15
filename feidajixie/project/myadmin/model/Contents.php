<?php
namespace app\myadmin\model;
use think\Model;

/**
* 文档数据模型
*/
class Contents extends Model
{
	/*
	*文档列表
	*/
	public function contlist($typid=0){
		$whe['cn_delt']=0;
		/*if($typid != 0){
			$whe['cn_type']=$typid;
		}*/

		if($typid != 0){
			$sun_type=get_suntype($typid);
			$seltj=$typid;
			foreach ($sun_type as $valtj) {
				$seltj.=','.$valtj;
			}
			$whe['cn_type']=array('IN',$seltj);
		}
		
		return $this->alias('c')
					->field('c.cn_id,c.cn_title,c.cn_type,c.cn_time,c.cn_uid,c.cn_setval,c.cn_attr,t.pty_id,t.pty_title')
					->join('__PAGETYPE__ t','c.cn_type = t.pty_id','LEFT')
					->where($whe)
					->order(array('cn_id'=>'desc'))
					->paginate(10);
	}
	/*
	*获取字段信息
	*/
	public function getTablesary(){
		return $this->getTableFields();
	}
	/*
	*判断标题是否存在
	*/
	public function jumptitle($title){
		if(isset($title)){
			$jmpary=$this->field('cn_id,cn_title')
					->where(array('cn_title'=>$title))
					->find();
			if($jmpary){
				return true;
			}
			return false;
		}
		return false;
	}
	/*
	*文章放回回收站
	*/
	public function recyclebin($id){
		if(isset($id)){

			$deltary['cn_delt']=1;
			$deltary['cn_deltime']=time();
			$sesadmin=jumpsession();
			if($sesadmin){
				$deltary['cn_deituid']=$sesadmin['uid'];
			}
			$jmpary=$this->field('cn_id,cn_delt,cn_deltime,cn_deituid')->where(array('cn_id'=>$id))->update($deltary);
			if($jmpary){
				return true;
			}
			return false;
		}
		return false;
	}
	/*
	*还原删除的文章
	*/
	public function huanyuan($id){
		if(isset($id)){

			$deltary['cn_delt']=0;
			$deltary['cn_deltime']=0;
			$sesadmin=jumpsession();
			if($sesadmin){
				$deltary['cn_deituid']=$sesadmin['uid'];
			}
			$jmpary=$this->field('cn_id,cn_delt,cn_deltime,cn_deituid')->where(array('cn_id'=>$id))->update($deltary);
			if($jmpary){
				return true;
			}
			return false;
		}
		return false;
	}
	/*
	*彻底删除文章
	*/
	public function deltetrue($id){
		if(isset($id)){
			$jmpary=$this->filed('cn_id')->where(array('cn_id'=>$id))->delt();
			if($jmpary){
				return true;
			}
			return false;
		}
		return false;
	}
	/*
	*回收站文档列表
	*/
	public function hszcontlist(){
		$whe['cn_delt']=1;
		
		return $this->alias('c')
					->field('c.cn_id,c.cn_title,c.cn_type,c.cn_time,c.cn_uid,c.cn_setval,c.cn_attr,t.pty_id,t.pty_title,c.cn_deituid,c.cn_deltime')
					->join('__PAGETYPE__ t','c.cn_type = t.pty_id','LEFT')
					->where($whe)
					->order(array('cn_id'=>'desc'))
					->paginate(10);
	}
	/*
	*统计时间区间内的发表个数
	*/
	public function datesum($begtime=0,$endtime=0){
		if(isset($begtime) && isset($begtime)){
			return $this->where(array('cn_time'=>array('between',$begtime.','.$endtime)))->count();
		}
		return 0;
	}
	/*
	*统计管理员的发表个数
	*/
	public function adminfbsum($uid=0){
		if(isset($uid)){
			return $this->where(array('cn_uid'=>$uid))->count();
		}
		return 0;
	}
}
?>