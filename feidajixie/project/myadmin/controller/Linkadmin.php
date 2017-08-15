<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\publicclass\pages;
use app\myadmin\model\Link;


/**
* 友情链接
*/
class Linkadmin extends Controller
{
	public function _initialize()
	{
		if(!jumpsession()){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("登陆超时！请重新登陆系统！！！");top.location.href="'.url('login/index').'"</script>';
        }
        $this->assign('sesval',session('admin'));
	}
	/*
	*友情链接列表
	*/
	public function index(){
		$action=post_get('action')?post_get('action'):'list';
		switch ($action) {
			case 'add':
				return $this->fetch('addlink');
				break;
			case 'addcl':
				$webname=post_get('webname');				//网站名称
				$weburl=post_get('weburl');				//网址
				$smlpicval=post_get('smlpicval');				//LOGO
				$conttent=post_get('conttent');				//网站描述
				$setval=post_get('setval')?1:0;				//状态
				if($webname == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入网站名称");history.go(-1);</script>';
					return false;
				}
				if($weburl == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入网址");history.go(-1);</script>';
					return false;
				}
				if(!strstr($weburl,'http')){
					$weburl='http://'.$weburl;
				}
				$linkm=new Link();
				if($linkm->jumplinkurl($weburl)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该网址已经存在！请不要重复添加！");history.go(-1);</script>';
					return false;
				}
				$linkm->title=$webname;
				$linkm->linkurl=$weburl;
				$linkm->images=$smlpicval;
				$linkm->linkcont=$conttent;
				$linkm->linkset=$setval;
				$linkm->time=time();
				$sesadmin=jumpsession();
				if($sesadmin){
					$linkm->lnk_uid=$sesadmin['uid'];
				}
				if($linkm->save()){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url('linkadmin/index',array('action'=>'add')).'";}else{location.href="'.url('linkadmin/index',array('action'=>'list')).'";}</script>';
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
				}
				break;
			case 'eite':
				$id=post_get('id');
				if($id == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return false;
				}
				$linkm=new Link();
				$this->assign('list',$linkm->find($id));
				return $this->fetch('eitelink');
				break;
			case 'eitecl':
				$id=post_get('id');
				if($id == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return false;
				}

				$webname=post_get('webname');				//网站名称
				$weburl=post_get('weburl');				//网址
				$oldweburl=post_get('oldweburl');				//网址
				$smlpicval=post_get('smlpicval');				//LOGO
				$conttent=post_get('conttent');				//网站描述
				$setval=post_get('setval')?1:0;				//状态
				if($webname == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入网站名称");history.go(-1);</script>';
					return false;
				}
				if($weburl == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入网址");history.go(-1);</script>';
					return false;
				}
				if(!strstr($weburl,'http')){
					$weburl='http://'.$weburl;
				}
				$linkm=new Link();
				if($oldweburl != $weburl && $linkm->jumplinkurl($weburl)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该网址已经存在！请更换！");history.go(-1);</script>';
					return false;
				}
				$linket=$linkm::get($id);
				$linket->title=$webname;
				$linket->linkurl=$weburl;
				$linket->images=$smlpicval;
				$linket->linkcont=$conttent;
				$linket->linkset=$setval;
				if($linket->save()){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("修改完成！");location.href="'.url('linkadmin/index',array('action'=>'list')).'";</script>';
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("修改完成！信息没有变动");location.href="'.url('linkadmin/index',array('action'=>'list')).'";</script>';
				}
				break;
			default:
				$linkm=new Link();
				$list=$linkm->linklist();
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list',$list);
				$this->assign('page',$page->pagelist());
				$this->assign('empt','<tr><td colspan="8">暂没有数据！请更新！</td></tr>');
				return $this->fetch();
				break;
		}
	}
	/*
	*删除友情链接
	*/
	public function deltlink(){
		$id=post_get('id');
		if($id){
			$jumpval=0;
			Db::startTrans();
			try{
				Db::name('link')->where(array('id'=>$id))->delete();
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
}
?>