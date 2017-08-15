<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\publicclass\pages;
use app\myadmin\model\Contents;
use app\myadmin\model\Pagetype;
/*
*栏目-文档控制器
*/
class Pagecont extends Controller
{
	private $seltval=1;
	public function _initialize()
	{
		if(!jumpsession()){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("登陆超时！请重新登陆系统！！！");top.location.href="'.url('login/index').'"</script>';
        }
        $this->assign('sesval',session('admin'));
	}
	//文章列表
	public function index(){
		$action=post_get('action')?post_get('action'):'list';
		$tyid=post_get('tyid')?post_get('tyid'):0;
		$this->assign('tyid',$tyid);
		switch ($action) {
			case 'add':
				$tyid=post_get('tyid')?post_get('tyid'):0;  //父类id
				$this->assign('tyid',$tyid);
				if($tyid != 0){
					return $this->fetch(gettypeurl($tyid));
				}else{
					//栏目列表
					$tycon=new Pagetype();
					$tylist=$tycon->getTypelist();
					$typecon=toLevend($tylist,'&nbsp',0);
					$this->assign('tylist',$typecon);
					//获取默认数值
					$list=gettabelmrval('qd_contents');
					if($tyid != 0){
						$list['cn_type']=$tyid;
					}
					if($list['cn_smallimg'] != ''){
						$smallimg=$list['cn_smallimg'];
					}else{
						$smallimg='/public/admin/images/33.png';
					}
					$tyfile=$tycon->getOnefind();
					$mbname=filetyzz($tyfile['mobelurl']);
					//echo $tyfile['methodse'];
					$this->assign('smallimg',$smallimg);
					$this->assign('list',$list);
					//选项卡链接
					$this->assign('indexurl',url($tyfile['methodse'],array('action'=>'list','tyid'=>$tyfile['pty_id'])));
					$this->assign('addurl',url($tyfile['methodse'],array('action'=>'add','tyid'=>$tyfile['pty_id'])));
					$this->assign('posturl',url($tyfile['methodse'],array('action'=>'addcl','tyid'=>$tyfile['pty_id'])));
					return $this->fetch($mbname);
				}
				break;
			case 'hszlst':

				$contm=new Contents();
				$list = $contm->hszcontlist();
				$page=new pages($list->currentPage(),$list->lastPage(),array('action'=>'hszlst'));
				$this->assign('list', $list);
				$this->assign('page', $page->pagelist());
				
				$empt='<tr><td colspan="7">暂没有数据！请更新！</td></tr>';
				$this->assign('empt',$empt);
				return $this->fetch('hszlst');
				break;
			default:
				$contm=new Contents();
				$list = $contm->contlist($tyid);
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list', $list);
				$this->assign('page', $page->pagelist());
				//选项卡链接
				$tycon=new Pagetype();
				$tyff=$tycon->getOnefind($tyid);
				$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyff['pty_id'])));
				$this->assign('addurl',url($tyff['methodse'],array('action'=>'add','tyid'=>$tyff['pty_id'])));
				$empt='<tr><td colspan="7">暂没有数据！请更新！</td></tr>';
				$this->assign('empt',$empt);
				return $this->fetch();
				break;
		}
	}
	/*
	*栏目列表
	*/
	public function pagetype(){
		$action=post_get('action')?post_get('action'):'list';
		switch ($action) {
			case 'add':
				$sonid=post_get('sonid')?post_get('sonid'):0;  //父类id
				$this->assign('sonid',$sonid);
				if($sonid != 0){
					$tyidlm=Db::name('pagetype')->field('pty_id,pty_filetype')->where(array('pty_id'=>$sonid))->find();
					$this->assign('lmtyid',$tyidlm['pty_filetype']);
				}else{
					$this->assign('lmtyid',0);
				}
				//栏目类型
				$lxlist=Db::name('types')->where(array('setval'=>1))->select();
				$this->assign('lxlist',$lxlist);
				//栏目分级
				$tylist=Db::name('pagetype')
							    ->alias('p')
					            ->field('p.pty_fatherid,p.pty_set,p.pty_id,p.pty_title,p.pty_filetype,t.id,t.onetwo,t.setval')
					            ->join('__TYPES__ t','p.pty_filetype = t.id','LEFT')
					            ->where(array('p.pty_set'=>1,'t.setval'=>1))
				                ->select();
				$typecon=toLevend($tylist,'&nbsp',0);
				$this->assign('tylist',$typecon);
				$this->assign('action','addcl');
				$this->assign('seltval',$this->seltval);
				return $this->fetch('addpgtype');
				break;
			case 'addcl':
				$tydata['pty_title']=post_get('title')?post_get('title'):'';					//栏目标题
				$tydata['pty_fatherid']=post_get('sstype')?post_get('sstype'):0;					//所属分类
				$tydata['pty_filetype']=post_get('filetype')?post_get('filetype'):0;					//栏目类型
				$tydata['pty_keyword']=post_get('keyword')?post_get('keyword'):'';					//关键词
				$tydata['pty_destons']=post_get('description')?post_get('description'):'';					//栏目描述
				$tydata['pty_images']=post_get('smlpicval')?post_get('smlpicval'):'';					//封面图
				$tydata['pty_paixu']=post_get('paixu')?post_get('paixu'):50;					//排序
				$tydata['pty_pgsum']=post_get('pagesum')?post_get('pagesum'):20;					//每页显示个数
				$tydata['pty_cont']=post_get('typecont')?post_get('typecont'):'';					//详细介绍
				$tydata['pty_set']=post_get('typeset')?1:0;					//状态
				$tydata['pty_time']=time();
				if($tydata['pty_title'] == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入栏目名称！");history.go(-1);</script>';
					return;
				}
				if($tydata['pty_filetype'] == 0){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请选择栏目类型");history.go(-1);</script>';
					return;
				}
				$tym=Db::name('pagetype');
				if($tym->where(array('pty_title'=>$tydata['pty_title']))->find()){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该栏目已经存在！请更换！");history.go(-1);</script>';
					return;
				}
				if($tym->insert($tydata)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url('pagetype',array('action'=>'add')).'";}else{location.href="'.url('pagetype').'";}</script>';
					return;
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
					return;
				}
				break;
			case 'eite':
				$id=post_get('id')?post_get('id'):0;
				if($id==0){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！请重新提交修改操作！");history.go(-1);</script>';
					return;
				}
				$this->assign('id',$id);
				//栏目类型
				$lxlist=Db::name('types')->where(array('setval'=>1))->select();
				$this->assign('lxlist',$lxlist);
				//栏目分级
				$tylist=Db::name('pagetype')
				                ->alias('p')
					            ->field('p.pty_fatherid,p.pty_set,p.pty_id,p.pty_title,p.pty_filetype,t.id,t.onetwo,t.setval')
					            ->join('__TYPES__ t','p.pty_filetype = t.id','LEFT')
					            ->where(array('p.pty_set'=>1,'t.setval'=>1))
					            ->select();
				$typecon=toLevend($tylist,'&nbsp',0);
				$this->assign('tylist',$typecon);
				$this->assign('action','eitecl');
				$this->assign('seltval',$this->seltval);
				$list=Db::name('pagetype')->where(array('pty_id'=>$id))->find();
				$this->assign('list',$list);

				return $this->fetch('eitepgtype');
				break;
			case 'eitecl':
				$id=post_get('id')?post_get('id'):0;
				if($id==0){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！请重新提交修改操作！");history.go(-1);</script>';
					return;
				}
				$tydata['pty_title']=post_get('title')?post_get('title'):'';					//栏目标题
				$tydata['pty_fatherid']=post_get('sstype')?post_get('sstype'):0;					//所属分类
				$tydata['pty_filetype']=post_get('filetype')?post_get('filetype'):0;					//栏目类型
				$tydata['pty_keyword']=post_get('keyword')?post_get('keyword'):'';					//关键词
				$tydata['pty_destons']=post_get('description')?post_get('description'):'';					//栏目描述
				$tydata['pty_images']=post_get('smlpicval')?post_get('smlpicval'):'';					//封面图
				$tydata['pty_paixu']=post_get('paixu')?post_get('paixu'):50;					//排序
				$tydata['pty_pgsum']=post_get('pagesum')?post_get('pagesum'):20;					//每页显示个数
				$tydata['pty_cont']=post_get('typecont')?post_get('typecont'):'';					//详细介绍
				$tydata['pty_set']=post_get('typeset')?1:0;					//状态

				$oldtitle=post_get('oldtitle');

				if($tydata['pty_title'] == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入栏目名称！");history.go(-1);</script>';
					return;
				}
				if($tydata['pty_title'] != $oldtitle){
					if(Db::name('pagetype')->where(array('pty_title'=>$tydata['pty_title']))->find()){
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("该栏目已经存在！请更换！");history.go(-1);</script>';
						return;
					}
				}
				if($tydata['pty_filetype'] == 0){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请选择栏目类型");history.go(-1);</script>';
					return;
				}
				$tym=Db::name('pagetype');
				if($tydata['pty_fatherid'] == $id){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("栏目不能成为自己的子类");history.go(-1);</script>';
					return;
				}
				if($tym->where(array('pty_id'=>$id))->update($tydata)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("修改成功");location.href="'.url('pagetype').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("内容与以前的数据没有区别，修改失败");location.href="'.url('pagetype').'"</script>';
                }
				break;
			default:
				$listty=Db::name('pagetype')
					->alias('p')
					->field('p.*,w.id,w.onetwo,w.methodse')
                    ->join('__TYPES__ w','p.pty_filetype = w.id','LEFT')
					->order(array('pty_id'=>'asc'))
					->select();
				$this->assign('typelist',wuxianxj($listty));
				$empt='<tr><td colspan="8">暂没有数据！请更新！</td></tr>';
				$this->assign('empt',$empt);

				return $this->fetch();
				break;
		}
	}
	//栏目类型删除操作
	public function delttypes(){
		$id=post_get('id');

		if($id){
			$listty=Db::name('pagetype')
					->order(array('pty_id'=>'asc'))
					->select();
			$deltidary=array_unique(explode(',', (delttype($listty,$id))));
			foreach ($deltidary as $valid) {
				if(Db::name('pagetype')->where(array('pty_id'=>$valid))->delete()){
					//$deltcont=Db::name('contents')->where(array('type_id'=>$id))->delete()
					$deltcont=Contents::destroy(['cn_type'=>$valid]);
				}
				
			}
			echo json_encode(array('code'=>1,'msg'=>'删除完成！'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
		}
	}

	//文章内容删除操作
	public function deltpagecong(){
		$id=post_get('id');

		if($id){
			$contm=new Contents();
			if($contm->recyclebin($id)){
				echo json_encode(array('code'=>1,'msg'=>'删除完成！资料进入回收站'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>'删除失败！请重新提交！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
		}
	}
	//彻底删除文章内容
	public function deltcongyes(){
		$id=post_get('id');
		if($id){
			
			if(Db::name('contents')->where(array('cn_id'=>$id))->delete()){
				echo json_encode(array('code'=>1,'msg'=>'删除完成！'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>'删除失败！请重新提交！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
		}
	}
	//还原数据
	public function huanyuan(){
		$id=post_get('id');

		if($id){
			$contm=new Contents();
			if($contm->huanyuan($id)){
				echo json_encode(array('code'=>1,'msg'=>'还原完成！'));
			}else{
				echo json_encode(array('code'=>0,'msg'=>'还原失败！请重新提交！'));
			}
		}else{
			echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
		}
	}
	/*
	*文章列表文档处理
	*/
	public function newslist(){
		$action=post_get('action')?post_get('action'):'list';
		$tyid=post_get('tyid')?post_get('tyid'):0;
		$this->assign('tyid',$tyid);
		switch ($action) {
			case 'add':
				$tycon=new Pagetype();
				$tylist=$tycon->getTypelist();
				$typecon=toLevend($tylist,'|-&nbsp',0);
				$this->assign('tylist',$typecon);

				$tyff=$tycon->getOnefind($tyid);
				//选项卡链接
				$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
				$this->assign('addurl',url($tyff['methodse'],array('action'=>'add','tyid'=>$tyid)));
				$this->assign('addtel','添加文档');
				//提交链接
				$this->assign('posturl',url($tyff['methodse'],array('action'=>'addcl','tyid'=>$tyid)));

				//获取默认数值
				$list=gettabelmrval('qd_contents');
				if($tyid != 0){
						$list['cn_type']=$tyid;
				}
				if($list['cn_smallimg'] != ''){
					$smallimg=$list['cn_smallimg'];
				}else{
					$smallimg='/public/admin/images/33.png';
				}
				$this->assign('smallimg',$smallimg);
				$this->assign('list',$list);
				$this->assign('seltval',$this->seltval);
				$mbname=filetyzz($tyff['mobelurl']);
				//return $this->fetch('pagecont');
				return $this->fetch($mbname);
				break;
			case 'addcl':
				$typeval=post_get('typeval');								//所属栏目
				$title=post_get('title');									//文章标题

				$keyword=post_get('keyword');								//关键字
				$description=post_get('description');						//描述
				$smlpicval=post_get('smlpicval');							//封面图
				$storval=post_get('storval')?post_get('storval'):50;		//排序
				$weburl=post_get('weburl');									//外部链接
				$typecont=post_get('typecont');								//详细内容
				$setval=post_get('setval')?1:0;								//启用关闭

				$sxdata=post_get('attrval');								//
                $sxval=0;													//文章属性
                if(is_array($sxdata)){
                	foreach ($sxdata as $valsx) {
	                    if($sxval != ''){
	                        $sxval=$sxval.','.$valsx;
	                    }else{
	                        $sxval=$valsx;
	                    }
	                }
                }
                $cont=new Contents;
                if($typeval == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请选择所属栏目！");history.go(-1);</script>';
					return;
                }
                if($title == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入文章标题！");history.go(-1);</script>';
					return;
                }
                if($typecont == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入文章内容！");history.go(-1);</script>';
					return;
                }
                if($cont->jumptitle($title)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该文章标题已经存在！请不要重复输入！");history.go(-1);</script>';
					return;
                }
				
				$cont->cn_title=$title;					//文章标题
				$cont->cn_type=$typeval;					//文章所属栏目
				$cont->cn_attr=$sxval;					//文章属性
				$cont->cn_keyword=$keyword;				//关键字
				$cont->cn_descr=$description;           //描述
				$cont->cn_smallimg=$smlpicval;			//封面图
				$cont->cn_sort=$storval;				//排序
				$cont->cn_urllinks=$weburl;				//外部链接
				$cont->cn_content=$typecont;			//详细内容
				$cont->cn_setval=$setval;				//状态
				$cont->cn_time=time();					//写入时间
				$sesadmin=jumpsession();
				if($sesadmin){
					$cont->cn_uid=$sesadmin['uid'];
				}
				if($cont->save()){
					$tycon=new Pagetype();
					$tyff=$tycon->getOnefind($tyid);
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";}else{location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";}</script>';
					return;
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
					return;
				}
				
				break;
			case 'eite':
				$id=post_get('id');
				if($id){
					$tycon=new Pagetype();
					$tylist=$tycon->getTypelist();
					$typecon=toLevend($tylist,'&nbsp',0);
					$this->assign('tylist',$typecon);

					$tyff=$tycon->getOnefind($tyid);
					//选项卡链接
					$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
					$this->assign('addurl',url($tyff['methodse'],array('action'=>'eite','tyid'=>$tyid,'id'=>$id)));
					$this->assign('addtel','修改文档');
					//提交链接
					$this->assign('posturl',url($tyff['methodse'],array('action'=>'eitecl','tyid'=>$tyid,'id'=>$id)));
					$contm=new Contents();
					$list=$contm->find($id);

					if($list['cn_smallimg'] != ''){
						$smallimg='/upload/images/'.$list['cn_smallimg'];
					}else{
						$smallimg='/public/admin/images/33.png';
					}
					$this->assign('smallimg',$smallimg);

					$this->assign('list',$list);
					$this->seltval=0;
					$this->assign('seltval',$this->seltval);
					$mbname=filetyzz($tyff['mobelurl']);
					return $this->fetch($mbname);
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return;
				}
				break;
			case 'eitecl':
				$id=post_get('id');
				if($id){
					$typeval=post_get('typeval');								//所属栏目
					$title=post_get('title');									//文章标题
					$oldtitle=post_get('oldtitle');									//文章标题

					$keyword=post_get('keyword');								//关键字
					$description=post_get('description');						//描述
					$smlpicval=post_get('smlpicval');							//封面图
					$storval=post_get('storval')?post_get('storval'):50;		//排序
					$weburl=post_get('weburl');									//外部链接
					$typecont=post_get('typecont');								//详细内容
					$setval=post_get('setval')?1:0;								//启用关闭

					$sxdata=post_get('attrval');								//
	                $sxval=0;													//文章属性
	                if(is_array($sxdata)){
	                	foreach ($sxdata as $valsx) {
		                    if($sxval != ''){
		                        $sxval=$sxval.','.$valsx;
		                    }else{
		                        $sxval=$valsx;
		                    }
		                }
	                }
	                $cont=new Contents;
	                if($typeval == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请选择所属栏目！");history.go(-1);</script>';
						return;
	                }
	                if($title == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请输入文章标题！");history.go(-1);</script>';
						return;
	                }
	                if($typecont == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请输入文章内容！");history.go(-1);</script>';
						return;
	                }
	                if($oldtitle != $title && $cont->jumptitle($title)){
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("该文章标题已经存在！请不要重复输入！");history.go(-1);</script>';
						return;
	                }
					$contcb=$cont::get($id);
					$contcb->cn_title=$title;					//文章标题
					$contcb->cn_type=$typeval;					//文章所属栏目
					$contcb->cn_attr=$sxval;					//文章属性
					$contcb->cn_keyword=$keyword;				//关键字
					$contcb->cn_descr=$description;           //描述
					$contcb->cn_smallimg=$smlpicval;			//封面图
					$contcb->cn_sort=$storval;				//排序
					$contcb->cn_urllinks=$weburl;				//外部链接
					$contcb->cn_content=$typecont;			//详细内容
					$contcb->cn_setval=$setval;				//状态
					$tycon=new Pagetype();
					$tyff=$tycon->getOnefind($tyid);
					if($contcb->save()){
						
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("修改完成！");location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";</script>';
						return;
					}else{
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("修改完成！信息没有变动");location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";</script>';
						return;
					}
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return;
				}
				break;
			default:
				$contm=new Contents();
				$list = $contm->contlist($tyid);
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list', $list);
				$this->assign('page', $page->pagelist());
				$tycon=new Pagetype();
				$tyff=$tycon->getOnefind($tyid);
				//选项卡链接
				$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
				$this->assign('addurl',url($tyff['methodse'],array('action'=>'add','tyid'=>$tyid)));
				$empt='<tr><td colspan="7">暂没有数据！请更新！</td></tr>';
				$this->assign('empt',$empt);
				return $this->fetch('index');
				break;
		}
	}
	/*
	*图片集文档处理
	*/
	public function imglist(){
		$action=post_get('action')?post_get('action'):'list';
		$tyid=post_get('tyid')?post_get('tyid'):0;
		$this->assign('tyid',$tyid);
		switch ($action) {
			case 'add':
				$tycon=new Pagetype();
				$tylist=$tycon->getTypelist();
				$typecon=toLevend($tylist,'&nbsp',0);
				$this->assign('tylist',$typecon);

				$tyff=$tycon->getOnefind($tyid);
				//选项卡链接
				$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
				$this->assign('addurl',url($tyff['methodse'],array('action'=>'add','tyid'=>$tyid)));
				$this->assign('addtel','添加文档');
				//提交链接
				$this->assign('posturl',url($tyff['methodse'],array('action'=>'addcl','tyid'=>$tyid)));

				//获取默认数值
				$list=gettabelmrval('qd_contents');
				if($tyid != 0){
						$list['cn_type']=$tyid;
				}
				if($list['cn_smallimg'] != ''){
					$smallimg=$list['cn_smallimg'];
				}else{
					$smallimg='/public/admin/images/33.png';
				}
				$this->assign('smallimg',$smallimg);
				$this->assign('list',$list);
				$this->assign('seltval',$this->seltval);
				$mbname=filetyzz($tyff['mobelurl']);
				//return $this->fetch('pagecont');
				return $this->fetch($mbname);
				break;
			case 'addcl':
				$typeval=post_get('typeval');								//所属栏目
				$title=post_get('title');									//文章标题

				$keyword=post_get('keyword');								//关键字
				$description=post_get('description');						//描述
				$smlpicval=post_get('smlpicval');							//封面图
				$storval=post_get('storval')?post_get('storval'):50;		//排序
				$imgaryurl=post_get('imgaryurl');							//图片集
				$weburl=post_get('weburl');									//外部链接
				$typecont=post_get('typecont');								//详细内容
				$setval=post_get('setval')?1:0;								//启用关闭

				$ggcspecont=post_get('ggcspecont');								//规格参数


				$zyjscsval=post_get('zyjscsval');							//主要参数

				$sxdata=post_get('attrval');								//
                $sxval=0;													//文章属性
                if(is_array($sxdata)){
                	foreach ($sxdata as $valsx) {
	                    if($sxval != ''){
	                        $sxval=$sxval.','.$valsx;
	                    }else{
	                        $sxval=$valsx;
	                    }
	                }
                }

                $imgary=post_get('imgarycont');								//
                $imgaryval='';													//文章属性
                if(is_array($imgary)){
                	foreach ($imgary as $valsx) {
	                    if($imgaryval != ''){
	                        $imgaryval=$imgaryval.','.$valsx;
	                    }else{
	                        $imgaryval=$valsx;
	                    }
	                }
                }

                $cont=new Contents;
                if($typeval == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请选择所属栏目！");history.go(-1);</script>';
					return;
                }
                if($title == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入文章标题！");history.go(-1);</script>';
					return;
                }
                if($typecont == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入文章内容！");history.go(-1);</script>';
					return;
                }
                if($cont->jumptitle($title)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该文章标题已经存在！请不要重复输入！");history.go(-1);</script>';
					return;
                }
				
				$cont->cn_title=$title;					//文章标题
				$cont->cn_type=$typeval;					//文章所属栏目
				$cont->cn_attr=$sxval;					//文章属性
				$cont->cn_keyword=$keyword;				//关键字
				$cont->cn_descr=$description;           //描述
				$cont->cn_smallimg=$smlpicval;			//封面图
				$cont->cn_sort=$storval;				//排序
				$cont->cn_imgary=$imgaryval;			//图片集
				$cont->cn_urllinks=$weburl;				//外部链接
				$cont->cn_content=$typecont;			//详细内容

				$cont->cn_guigecanshu=$ggcspecont;		//规格参数

				$cont->cn_zycsval=$zyjscsval;		//主要参数

				$cont->cn_setval=$setval;				//状态
				$cont->cn_time=time();					//写入时间
				$sesadmin=jumpsession();
				if($sesadmin){
					$cont->cn_uid=$sesadmin['uid'];
				}
				if($cont->save()){
					$tycon=new Pagetype();
					$tyff=$tycon->getOnefind($tyid);
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";}else{location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";}</script>';
					return;
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
					return;
				}
				
				break;
			case 'eite':
				$id=post_get('id');
				if($id){
					$tycon=new Pagetype();
					$tylist=$tycon->getTypelist();
					$typecon=toLevend($tylist,'&nbsp',0);
					$this->assign('tylist',$typecon);

					$tyff=$tycon->getOnefind($tyid);
					//选项卡链接
					$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
					$this->assign('addurl',url($tyff['methodse'],array('action'=>'eite','tyid'=>$tyid,'id'=>$id)));
					$this->assign('addtel','修改文档');
					//提交链接
					$this->assign('posturl',url($tyff['methodse'],array('action'=>'eitecl','tyid'=>$tyid,'id'=>$id)));
					$contm=new Contents();
					$list=$contm->find($id);

					if($list['cn_smallimg'] != ''){
						$smallimg='/upload/images/'.$list['cn_smallimg'];
					}else{
						$smallimg='/public/admin/images/33.png';
					}
					$this->assign('smallimg',$smallimg);

					$this->assign('list',$list);
					$this->seltval=0;
					$this->assign('seltval',$this->seltval);
					$mbname=filetyzz($tyff['mobelurl']);
					//return $this->fetch('pagecont');
					return $this->fetch($mbname);
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return;
				}
				break;
			case 'eitecl':
				$id=post_get('id');
				if($id){
					$typeval=post_get('typeval');								//所属栏目
					$title=post_get('title');									//文章标题
					$oldtitle=post_get('oldtitle');									//原文章标题

					$keyword=post_get('keyword');								//关键字
					$description=post_get('description');						//描述
					$smlpicval=post_get('smlpicval');							//封面图
					$storval=post_get('storval')?post_get('storval'):50;		//排序
					$imgaryurl=post_get('imgaryurl');							//图片集
					$weburl=post_get('weburl');									//外部链接
					$typecont=post_get('typecont');								//详细内容
					$setval=post_get('setval')?1:0;								//启用关闭

					$ggcspecont=post_get('ggcspecont');								//规格参数

					$zyjscsval=post_get('zyjscsval');							//主要参数

					$sxdata=post_get('attrval');								//
	                $sxval=0;													//文章属性
	                if(is_array($sxdata)){
	                	foreach ($sxdata as $valsx) {
		                    if($sxval != ''){
		                        $sxval=$sxval.','.$valsx;
		                    }else{
		                        $sxval=$valsx;
		                    }
		                }
	                }

	                $imgary=post_get('imgarycont');								//
	                $imgaryval='';													//文章属性
	                if(is_array($imgary)){
	                	foreach ($imgary as $valsx) {
		                    if($imgaryval != ''){
		                        $imgaryval=$imgaryval.','.$valsx;
		                    }else{
		                        $imgaryval=$valsx;
		                    }
		                }
	                }

	                $cont=new Contents;
	                if($typeval == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请选择所属栏目！");history.go(-1);</script>';
						return;
	                }
	                if($title == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请输入文章标题！");history.go(-1);</script>';
						return;
	                }
	                if($typecont == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请输入文章内容！");history.go(-1);</script>';
						return;
	                }
	                if($oldtitle != $title && $cont->jumptitle($title)){
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("该文章标题已经存在！请不要重复输入！");history.go(-1);</script>';
						return;
	                }

	                $contcl=$cont::get($id);
	                $contcl->cn_title=$title;					//文章标题
					$contcl->cn_type=$typeval;					//文章所属栏目
					$contcl->cn_attr=$sxval;					//文章属性
					$contcl->cn_keyword=$keyword;				//关键字
					$contcl->cn_descr=$description;           //描述
					$contcl->cn_smallimg=$smlpicval;			//封面图
					$contcl->cn_sort=$storval;				//排序
					$contcl->cn_imgary=$imgaryval;			//图片集
					$contcl->cn_urllinks=$weburl;				//外部链接
					$contcl->cn_content=$typecont;			//详细内容

					$contcl->cn_guigecanshu=$ggcspecont;		//规格参数

					$contcl->cn_zycsval=$zyjscsval;		//主要参数

					$contcl->cn_setval=$setval;				//状态
					$tycon=new Pagetype();
					$tyff=$tycon->getOnefind($tyid);

					

					if($contcl->save()){
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("修改完成！");location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";</script>';
						return;
					}else{
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("修改完成！信息没有变动");location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";</script>';
						return;
					}
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return;
				}
				break;
			default:
				$contm=new Contents();
				$list = $contm->contlist($tyid);
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list', $list);
				$this->assign('page', $page->pagelist());
				$tycon=new Pagetype();
				$tyff=$tycon->getOnefind($tyid);
				//选项卡链接
				$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
				$this->assign('addurl',url($tyff['methodse'],array('action'=>'add','tyid'=>$tyid)));
				$empt='<tr><td colspan="7">暂没有数据！请更新！</td></tr>';
				$this->assign('empt',$empt);
				return $this->fetch('index');
				break;
		}
	}

	/*
	*视频处理过程
	*/
	public function voidelist(){
		$action=post_get('action')?post_get('action'):'list';
		$tyid=post_get('tyid')?post_get('tyid'):0;
		$this->assign('tyid',$tyid);
		switch ($action) {
			case 'add':
				$tycon=new Pagetype();
				$tylist=$tycon->getTypelist();
				$typecon=toLevend($tylist,'&nbsp',0);
				$this->assign('tylist',$typecon);

				$tyff=$tycon->getOnefind($tyid);
				//选项卡链接
				$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
				$this->assign('addurl',url($tyff['methodse'],array('action'=>'add','tyid'=>$tyid)));
				$this->assign('addtel','添加文档');
				//提交链接
				$this->assign('posturl',url($tyff['methodse'],array('action'=>'addcl','tyid'=>$tyid)));

				//获取默认数值
				$list=gettabelmrval('qd_contents');
				if($tyid != 0){
						$list['cn_type']=$tyid;
				}
				if($list['cn_smallimg'] != ''){
					$smallimg=$list['cn_smallimg'];
				}else{
					$smallimg='/public/admin/images/33.png';
				}
				$this->assign('smallimg',$smallimg);
				$this->assign('list',$list);
				$this->assign('seltval',$this->seltval);
				$mbname=filetyzz($tyff['mobelurl']);
				//return $this->fetch('pagecont');
				return $this->fetch($mbname);
				break;
			case 'addcl':
				$typeval=post_get('typeval');								//所属栏目
				$title=post_get('title');									//文章标题

				$keyword=post_get('keyword');								//关键字
				$description=post_get('description');						//描述
				$smlpicval=post_get('smlpicval');							//封面图
				$storval=post_get('storval')?post_get('storval'):50;		//排序
				$weburl=post_get('weburl');									//外部链接
				$typecont=post_get('typecont');								//详细内容
				$setval=post_get('setval')?1:0;								//启用关闭
				$voideurl=post_get('voideurl');
				$sxdata=post_get('attrval');								//
                $sxval=0;													//文章属性
                if(is_array($sxdata)){
                	foreach ($sxdata as $valsx) {
	                    if($sxval != ''){
	                        $sxval=$sxval.','.$valsx;
	                    }else{
	                        $sxval=$valsx;
	                    }
	                }
                }
                $cont=new Contents;
                if($typeval == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请选择所属栏目！");history.go(-1);</script>';
					return;
                }
                if($title == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入文章标题！");history.go(-1);</script>';
					return;
                }
                if($typecont == ''){
                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入文章内容！");history.go(-1);</script>';
					return;
                }
                if($cont->jumptitle($title)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该文章标题已经存在！请不要重复输入！");history.go(-1);</script>';
					return;
                }
				
				$cont->cn_title=$title;					//文章标题
				$cont->cn_type=$typeval;					//文章所属栏目
				$cont->cn_attr=$sxval;					//文章属性
				$cont->cn_keyword=$keyword;				//关键字
				$cont->cn_descr=$description;           //描述
				$cont->cn_smallimg=$smlpicval;			//封面图
				$cont->cn_sort=$storval;				//排序
				$cont->cn_urllinks=$weburl;				//外部链接
				$cont->cn_content=$typecont;			//详细内容
				$cont->cn_setval=$setval;				//状态
				$cont->cn_voidurl=$voideurl;			//视频地址
				$cont->cn_time=time();					//写入时间
				$sesadmin=jumpsession();
				if($sesadmin){
					$cont->cn_uid=$sesadmin['uid'];
				}
				if($cont->save()){
					$tycon=new Pagetype();
					$tyff=$tycon->getOnefind($tyid);
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";}else{location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";}</script>';
					return;
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
					return;
				}
				
				break;
			case 'eite':
				$id=post_get('id');
				if($id){
					$tycon=new Pagetype();
					$tylist=$tycon->getTypelist();
					$typecon=toLevend($tylist,'&nbsp',0);
					$this->assign('tylist',$typecon);

					$tyff=$tycon->getOnefind($tyid);
					//选项卡链接
					$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
					$this->assign('addurl',url($tyff['methodse'],array('action'=>'eite','tyid'=>$tyid,'id'=>$id)));
					$this->assign('addtel','修改文档');
					//提交链接
					$this->assign('posturl',url($tyff['methodse'],array('action'=>'eitecl','tyid'=>$tyid,'id'=>$id)));
					$contm=new Contents();
					$list=$contm->find($id);

					if($list['cn_smallimg'] != ''){
						$smallimg='/upload/images/'.$list['cn_smallimg'];
					}else{
						$smallimg='/public/admin/images/33.png';
					}
					$this->assign('smallimg',$smallimg);

					$this->assign('list',$list);
					$this->seltval=0;
					$this->assign('seltval',$this->seltval);
					$mbname=filetyzz($tyff['mobelurl']);
					return $this->fetch($mbname);
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return;
				}
				break;
			case 'eitecl':
				$id=post_get('id');
				if($id){
					$typeval=post_get('typeval');								//所属栏目
					$title=post_get('title');									//文章标题
					$oldtitle=post_get('oldtitle');									//文章标题

					$keyword=post_get('keyword');								//关键字
					$description=post_get('description');						//描述
					$smlpicval=post_get('smlpicval');							//封面图
					$storval=post_get('storval')?post_get('storval'):50;		//排序
					$weburl=post_get('weburl');									//外部链接
					$typecont=post_get('typecont');								//详细内容
					$setval=post_get('setval')?1:0;								//启用关闭
					$voideurl=post_get('voideurl');
					$sxdata=post_get('attrval');								//
	                $sxval=0;													//文章属性
	                if(is_array($sxdata)){
	                	foreach ($sxdata as $valsx) {
		                    if($sxval != ''){
		                        $sxval=$sxval.','.$valsx;
		                    }else{
		                        $sxval=$valsx;
		                    }
		                }
	                }
	                $cont=new Contents;
	                if($typeval == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请选择所属栏目！");history.go(-1);</script>';
						return;
	                }
	                if($title == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请输入文章标题！");history.go(-1);</script>';
						return;
	                }
	                if($typecont == ''){
	                	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("请输入文章内容！");history.go(-1);</script>';
						return;
	                }
	                if($oldtitle != $title && $cont->jumptitle($title)){
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("该文章标题已经存在！请不要重复输入！");history.go(-1);</script>';
						return;
	                }
					$contcb=$cont::get($id);
					$contcb->cn_title=$title;					//文章标题
					$contcb->cn_type=$typeval;					//文章所属栏目
					$contcb->cn_attr=$sxval;					//文章属性
					$contcb->cn_keyword=$keyword;				//关键字
					$contcb->cn_descr=$description;           //描述
					$contcb->cn_smallimg=$smlpicval;			//封面图
					$contcb->cn_sort=$storval;				//排序
					$contcb->cn_urllinks=$weburl;				//外部链接
					$contcb->cn_content=$typecont;			//详细内容
					$contcb->cn_setval=$setval;				//状态
					$contcb->cn_voidurl=$voideurl;			//视频地址
					$tycon=new Pagetype();
					$tyff=$tycon->getOnefind($tyid);
					if($contcb->save()){
						
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("修改完成！");location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";</script>';
						return;
					}else{
						echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
						echo '<script>alert("修改完成！信息没有变动");location.href="'.url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)).'";</script>';
						return;
					}
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("参数错误！");history.go(-1);</script>';
					return;
				}
				break;
			default:
				$contm=new Contents();
				$list = $contm->contlist($tyid);
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list', $list);
				$this->assign('page', $page->pagelist());
				$tycon=new Pagetype();
				$tyff=$tycon->getOnefind($tyid);
				//选项卡链接
				$this->assign('indexurl',url($tyff['methodse'],array('action'=>'list','tyid'=>$tyid)));
				$this->assign('addurl',url($tyff['methodse'],array('action'=>'add','tyid'=>$tyid)));
				$empt='<tr><td colspan="7">暂没有数据！请更新！</td></tr>';
				$this->assign('empt',$empt);
				return $this->fetch('index');
				break;
		}
	}

}




?>