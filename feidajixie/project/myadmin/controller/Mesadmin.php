<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\publicclass\pages;
/**
* 客户留言
*/
class Mesadmin extends Controller{
	public function _initialize()
	{
		if(!jumpsession()){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("登陆超时！请重新登陆系统！！！");top.location.href="'.url('login/index').'"</script>';
        }
        $this->assign('sesval',session('admin'));
	}
	public function index(){
		$list=Db::name('message')
					->alias('m')
					->field('m.*,c.cn_id,c.cn_title')
					->join('__CONTENTS__ c','m.mes_porid = c.cn_id','LEFT')
					->order(array('mes_id'=>'desc'))
					->paginate(10);
		$page=new pages($list->currentPage(),$list->lastPage());
		$this->assign('list',$list);
		$this->assign('page',$page->pagelist());
		return $this->fetch();
	}
	/*
	*删除留言
	*/
	public function delmesage(){
		$id=post_get('id');
		if($id){
			$jumpval=0;
			Db::startTrans();
			try{
				Db::name('message')->where(array('mes_id'=>$id))->delete();
				$jumpval=1;
				Db::commit();
			} catch (\Exception $e) {
			    // 回滚事务
			    Db::rollback();
			}
			if($jumpval === 1){
				echo json_encode(array('code'=>1,'msg'=>'删除完成！'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>'删除失败！请重新提交！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
		}
	}
	/*
	*读取信息
	*/
	public function readmescont(){
		$id=post_get('id');
		if($id){
			$list=Db::name('message')->alias('m')
					->field('m.*,c.cn_id,c.cn_title')
					->join('__CONTENTS__ c','m.mes_porid = c.cn_id','LEFT')
					->where(array('mes_id'=>$id))
					->find();
			if($list){
				echo json_encode(array('code'=>1,'msg'=>'读取成功！','info'=>$list));
			}else{
				echo json_encode(array('code'=>0,'msg'=>'读取失败！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
		}
	}
	/*
	*信息回复
	*/
	public function huifucl(){
		$id=post_get('id');
		$mescont=post_get('hfnrcontss');
		$adsetval=post_get('adsetval')?1:0;
		if($id){
			if($mescont == ''){
				echo json_encode(array('code'=>0,'msg'=>'请输入回复内容！'));
				return;
			}
			$hfary=array(
				'mes_hfcont'			=>				$mescont,
				'mes_setval'			=>				$adsetval
			);
			if(Db::name('message')->where(array('mes_id'=>$id))->update($hfary)){
				echo json_encode(array('code'=>1,'msg'=>'回复完成！'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>'回复失败！请重新提交！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
		}
	}
}