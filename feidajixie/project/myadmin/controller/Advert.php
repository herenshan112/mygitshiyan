<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\publicclass\pages;
use app\myadmin\model\Advtype;
use app\myadmin\model\Advcont;

/*
*广告处理
*/
/**
* 
*/
class Advert extends Controller
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
	*广告列表
	*/
	public function index(){
		$action=post_get('action')?post_get('action'):'list';
		switch ($action) {
			case 'add':
				$this->assign('tylist',Db::name('advtype')->field('advt_id,advt_title,advt_setval')->where(array('advt_setval'=>1))->select());
				return $this->fetch('addcont');
				break;
			case 'addcl':
				$title=post_get('title');					//标题
				$typeval=post_get('typeval');					//所属类型
				$smlpicval=post_get('smlpicval');					//封面图
				$weburl=post_get('weburl');					//外部链接
				$conttent=post_get('conttent');					//详细描述
				$setval=post_get('setval')?1:0;					//状态
				$advdb=new Advcont();
				if($title == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入标题");history.go(-1);</script>';
					return false;
				}
				if($advdb->jumpadvtitle($title)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该标题已经存在！请更换！");history.go(-1);</script>';
					return false;
				}
				if($typeval == 0){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请选择所属类型!");history.go(-1);</script>';
					return false;
				}
				if($smlpicval == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请上传封面图片！!");history.go(-1);</script>';
					return false;
				}
				$advdb->advc_title=$title;
				$advdb->advc_type=$typeval;
				$advdb->advc_images=$smlpicval;
				$advdb->advc_url=$weburl;
				$advdb->advc_cont=$conttent;
				$advdb->advc_setval=$setval;
				$advdb->advc_time=time();
				$sesadmin=jumpsession();
				if($sesadmin){
					$advdb->advc_uid=$sesadmin['uid'];
				}
				if($advdb->save()){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url('advert/index',array('action'=>'add')).'";}else{location.href="'.url('advert/index',array('action'=>'list')).'";}</script>';
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
				$this->assign('tylist',Db::name('advtype')->field('advt_id,advt_title,advt_setval')->where(array('advt_setval'=>1))->select());
				$advdb=new Advcont();
				$this->assign('list',$advdb->find($id));
				return $this->fetch('eitecont');
				break;
			case 'eitecl':
				$id=post_get('id');
				if($id == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return false;
				}
				$title=post_get('title');					//标题
				$oldtitle=post_get('oldtitle');					//旧标题
				$typeval=post_get('typeval');					//所属类型
				$smlpicval=post_get('smlpicval');					//封面图
				$weburl=post_get('weburl');					//外部链接
				$conttent=post_get('conttent');					//详细描述
				$setval=post_get('setval')?1:0;					//状态
				$advdb=new Advcont();
				if($title == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入标题");history.go(-1);</script>';
					return false;
				}
				if($title != $oldtitle && $advdb->jumpadvtitle($title)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该标题已经存在！请更换！");history.go(-1);</script>';
					return false;
				}
				if($typeval == 0){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请选择所属类型!");history.go(-1);</script>';
					return false;
				}
				if($smlpicval == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请上传封面图片！!");history.go(-1);</script>';
					return false;
				}
				$adveit=$advdb::get($id);
				$adveit->advc_title=$title;
				$adveit->advc_type=$typeval;
				$adveit->advc_images=$smlpicval;
				$adveit->advc_url=$weburl;
				$adveit->advc_cont=$conttent;
				$adveit->advc_setval=$setval;
				if($adveit->save()){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("修改完成！");location.href="'.url('advert/index',array('action'=>'list')).'";</script>';
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("修改完成！信息没有变动");location.href="'.url('advert/index',array('action'=>'list')).'";</script>';
				}
				break;
			default:
				$advdb=new Advcont();
				$list=$advdb->listcont();
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list', $list);
				$this->assign('page', $page->pagelist());
				$empt='<tr><td colspan="8">暂没有数据！请更新！</td></tr>';
				$this->assign('empt',$empt);
				return $this->fetch();
				break;
		}
	}
	/*
	*广告位置
	*/
	public function type(){
		$action=post_get('action')?post_get('action'):'list';
		switch ($action) {
			case 'add':
				$this->assign('colum',Db::name('pagetype')->field('pty_id,pty_title,pty_paixu,pty_set')->where(array('pty_set'=>1))->order(array('pty_paixu'=>'asc','pty_id'=>'desc'))->select());
				return $this->fetch('addtype');
				break;
			case 'addcl':
				$webname=post_get('webname');					//位置名称
				$usergrade=post_get('usergrade');					//有效范围
				$description=post_get('description');					//描述
				$userset=post_get('userset')?1:0;					//状态
				if($webname == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入位置名称");history.go(-1);</script>';
					return false;
				}
				$typecont=new Advtype();
				$typecont->advt_title=$webname;
				$typecont->advt_fanwei=$usergrade;
				$typecont->advt_cont=$description;
				$typecont->advt_setval=$userset;
				$typecont->advt_time=time();
				$sesadmin=jumpsession();
				if($sesadmin){
					$typecont->advt_uid=$sesadmin['uid'];
				}
				if($typecont->jumpadvtitle($webname)){
					if($typecont->save()){
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url('advert/type',array('action'=>'add')).'";}else{location.href="'.url('advert/type',array('action'=>'list')).'";}</script>';
					}else{
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
					}
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该位置已经存在！请不要重复添加！");history.go(-1);</script>';
					
				}
				
				break;
			case 'eite':
				$id=post_get('id');
				if($id == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return false;
				}
				$typecont=new Advtype();
				$list=$typecont->find($id);
				$this->assign('list',$list);
				$this->assign('colum',Db::name('pagetype')->field('pty_id,pty_title,pty_paixu,pty_set')->where(array('pty_set'=>1))->order(array('pty_paixu'=>'asc','pty_id'=>'desc'))->select());
				return $this->fetch('eitetype');
				break;
			case 'eitecl':
				$id=post_get('id');
				if($id == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return false;
				}
				$webname=post_get('webname');					//位置名称
				$oldwebname=post_get('oldwebname');					//位置名称
				$usergrade=post_get('usergrade');					//有效范围
				$description=post_get('description');					//描述
				$userset=post_get('userset')?1:0;					//状态
				if($webname == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入位置名称");history.go(-1);</script>';
					return false;
				}
				$typecont=new Advtype();
				if($webname != $oldwebname){
					if(!$typecont->jumpadvtitle($webname)){
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("该位置已经存在！请不要重复！");history.go(-1);</script>';
						return false;
					}
				}
				$tyconeit=$typecont::get($id);
				$tyconeit->advt_title=$webname;
				$tyconeit->advt_fanwei=$usergrade;
				$tyconeit->advt_cont=$description;
				$tyconeit->advt_setval=$userset;
				if($tyconeit->save()){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("修改完成！");location.href="'.url('advert/type',array('action'=>'list')).'";</script>';
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("修改完成！信息没有变动");location.href="'.url('advert/type',array('action'=>'list')).'";</script>';
				}
				break;
			default:
				$typecont=new Advtype();
				$list=$typecont->listtype();
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list', $list);
				$this->assign('page', $page->pagelist());
				$empt='<tr><td colspan="8">暂没有数据！请更新！</td></tr>';
				$this->assign('empt',$empt);
				return $this->fetch();
				break;
		}
	}
	/*
	*删除banner位置
	*/
	public function deltadvtype(){
		$id=post_get('id');
		if($id){
			$jumpval=0;
			Db::startTrans();
			try{
				Db::name('advtype')->where(array('advt_id'=>$id))->delete();
				Db::name('advcont')->where(array('advc_type'=>$id))->delete();
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
	*删除广告内容
	*/
	public function deltadvcont(){
		$id=post_get('id');
		if($id){
			$jumpval=0;
			Db::startTrans();
			try{
				Db::name('advcont')->where(array('advc_id'=>$id))->delete();
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