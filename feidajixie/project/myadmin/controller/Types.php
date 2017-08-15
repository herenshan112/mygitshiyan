<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;

use app\publicclass\pages;

/*
*栏目类型处理
*/
/**
* 
*/
class Types extends Controller
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
	*类型列表
	*/
	public function index(){
		
		$list = Db::name('types')
					->order(array('id'=>'desc'))
					->paginate(10);
		$page=new pages($list->currentPage(),$list->lastPage());
		$this->assign('list', $list);
		$this->assign('page', $page->pagelist());
		return $this->fetch();
	}
	/*
	*添加类型
	*/
	public function add(){
		return $this->fetch();
	}
	/*
	*添加类型处理
	*/
	public function addcl(){
		$title=post_get('title');
		$methodse=post_get('methodse');
		$filevalue=post_get('filevalue');
		$miaoshu=post_get('miaoshu');
		$setval=post_get('setval')?1:0;
		$onetwo=post_get('onetwo')?1:0;
		if($title == ''){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script>alert("请输入类型名称！");history.go(-1);</script>';
			return;
		}
		if($filevalue == ''){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script>alert("请上传类型模型！");history.go(-1);</script>';
			return;
		}
		if($methodse == ''){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script>alert("请输入处理方法！");history.go(-1);</script>';
			return;
		}
		$data=array(
			'title'				=>				$title,
			'mobelurl'			=>				$filevalue,
			'contval'			=>				$miaoshu,
			'setval'			=>				$setval,
			'onetwo'			=>				$onetwo,
			'uid'				=>				session('admin.UID'),
			'time'				=>				time(),
			'methodse'			=>				$methodse
		);
		$addjg=Db::name('types')->insert($data);
		if($addjg){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url('add').'";}else{location.href="'.url('index').'";}</script>';
			return;
		}else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
			return;
		}
	}
	//删除操作
	public function delttypes(){
		$id=post_get('id');
		if($id){
			if(Db::name('types')->where(array('id'=>$id))->delete()){
				echo json_encode(array('code'=>1,'msg'=>'删除完成！'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>'没有要删除的数据！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
		}
	}
	/*
	*修改文档类型页面
	*/
	public function eite(){
		$id=post_get('id');
		if($id){
			$list=Db::name('types')->where(array('id'=>$id))->find();
			$this->assign('list',$list);
			return $this->fetch();
		}else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script>alert("参数错误！请重新提交！！！");history.go(-1);</script>';
			return;
		}
	}
	/*
	*修改文档类型处理
	*/
	public function eitecl(){
		$id=post_get('id');
		if($id){
			$title=post_get('title');
			$oldtitle=post_get('oldtitle');
			$filevalue=post_get('filevalue');
			$miaoshu=post_get('miaoshu');
			$setval=post_get('setval')?1:0;
			$onetwo=post_get('onetwo')?1:0;
			$methodse=post_get('methodse');
			if($title == ''){
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo '<script>alert("请输入类型名称！");history.go(-1);</script>';
				return;
			}
			if($filevalue == ''){
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo '<script>alert("请上传类型模型！");history.go(-1);</script>';
				return;
			}
			if($title != $oldtitle){
				if(Db::name('types')->where(array('title'=>$title))->find()){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该类型已经存在！请更换！");history.go(-1);</script>';
					return;
				}
			}
			if($methodse == ''){
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo '<script>alert("请输入处理方法！");history.go(-1);</script>';
				return;
			}
			$data=array(
				'title'				=>				$title,
				'mobelurl'			=>				$filevalue,
				'contval'			=>				$miaoshu,
				'setval'			=>				$setval,
				'onetwo'			=>				$onetwo,
				'methodse'			=>				$methodse
			);
			$eiotejg=Db::name('types')->where(array('id'=>$id))->update($data);
			if($eiotejg){
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo '<script>alert("修改完成！");location.href="'.url('index').'";</script>';
				return;
			}else{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo '<script>alert("修改失败！您提交的数据没有发生变化！");history.go(-1);</script>';
				return;
			}
		}else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script>alert("参数错误！请重新提交！！！");history.go(-1);</script>';
			return;
		}
	}
}
?>