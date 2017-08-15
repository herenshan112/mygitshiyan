<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\publicclass\pages;
use app\myadmin\model\Grades;
use app\myadmin\model\Admindata;
use app\myadmin\model\Admin;

/**
* 管理员
*/
class Admingrod extends Controller
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
	*管理员列表
	*/
	public function index(){
		$action=post_get('action')?post_get('action'):'list';
		switch ($action) {
			case 'add':					//添加管理员页面
				$grdm=new Grades();
				$this->assign('grdlist',$grdm->selectlst());
				return $this->fetch('addadmin');
				break;
			case 'addcl':					//添加管理员处理
				$username=post_get('username');					//用户名

				$newpwd=post_get('newpwd');					//密码
				$qrpwd=post_get('qrpwd');					//确认密码
				$usegrd=post_get('usegrd');					//管理权限
				$callname=post_get('callname');					//联系人
				$calltel=post_get('calltel');					//联系电话
				$qqnum=post_get('qqnum');					//QQ
				$email=post_get('email');					//email
				$address=post_get('address');					//联系地址
				$content=post_get('content');					//管理员简介
				$userset=post_get('userset')?1:0;					//状态
				$smlpicval=post_get('smlpicval');				//头像

				if($username == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入用户名");history.go(-1);</script>';
					return false;
				}
				$adm=new Admin();
				if($username != '' && $adm->jumpadmin($username)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该用户名已经存在！请更换！");history.go(-1);</script>';
					return false;
				}
				if($newpwd == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入密码");history.go(-1);</script>';
					return false;
				}
				if($newpwd != $qrpwd){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("您两次输入的密码不一致");history.go(-1);</script>';
					return false;
				}

				if($usegrd == 0){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请选择管理权限");history.go(-1);</script>';
					return false;
				}
				$jumpwval=0;
				//事务处理
				Db::startTrans();
				try{
					$adm->ad_username=$username;
					$adm->ad_pwdval=jiamimd5($qrpwd);
					$adm->ad_time=time();
					$adm->ad_grod=$usegrd;
					$adm->ad_setval=$userset;
					$adm->save();				//存储帐号信息
					$adam=new Admindata();
					$adam->ada_id=$adm->ad_id;
					$adam->ada_name=$callname;
					$adam->ada_tel=$calltel;
					$adam->ada_qq=$qqnum;
					$adam->ada_email=$email;
					$adam->ada_address=$address;
					$adam->ada_cont=$content;
					$adam->ada_imgpic=$smlpicval;
					$adam->save();				//存储管理员信息
					$jumpwval=1;
					Db::commit();
				} catch (\Exception $e) {
				    // 回滚事务
				    Db::rollback();
				}
				if($jumpwval === 1){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url('admingrod/index',array('action'=>'add')).'";}else{location.href="'.url('admingrod/index',array('action'=>'list')).'";}</script>';
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
				}
				break;
			case 'ajaxeitepwdcl':				//修改密码处理
				$id=post_get('id');
				if($id == ''){
					echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
					return false;
				}
				$eitnewpwd=post_get('eitnewpwd');
				$eitoldpwd=post_get('eitoldpwd');
				if($eitnewpwd == ''){
					echo json_encode(array('code'=>0,'msg'=>'请输入新密码！'));
					return false;
				}
				if($eitnewpwd != $eitoldpwd){
					echo json_encode(array('code'=>0,'msg'=>'您两次输入的密码不一致！'));
					return false;
				}
				$adm=new Admin();
				$admcn=$adm::get($id);
				$admcn->ad_pwdval=jiamimd5($eitoldpwd);
				if($admcn->save()){
					echo json_encode(array('code'=>1,'msg'=>'密码修改完成！'));
				}else{
					echo json_encode(array('code'=>1,'msg'=>'密码修改完成！内容没有变动！'));
				}
				break;
			case 'ajaxeite':			//修改信息回调
				$id=post_get('id');
				if($id == ''){
					echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
					return false;
				}
				$adam=new Admindata();
				$list=$adam->listadcom($id);
				if($list['ad_lasttime'] != 0){
					$list['ad_lasttime']=date('Y-m-d H:i:s',$list['ad_lasttime']);
				}else{
					$list['ad_lasttime']='';
				}
				$grdm=new Grades();
				if($list){
					
					echo json_encode(array('code'=>1,'msg'=>'读取成功！','info'=>$list,'grdlist'=>$grdm->selectlst()));
				}else{
					echo json_encode(array('code'=>1,'msg'=>'资料不存在','info'=>$list,'grdlist'=>$grdm->selectlst()));
				}
				break;
			case 'ajaxeitecl':
				$id=post_get('id');
				if($id == ''){
					echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
					return false;
				}
				$gradval=post_get('gradval');				//管理员级别
				$adsetval=post_get('adsetval')?1:0;				//状态
				$callname=post_get('callname');				//联系人姓名
				$tel=post_get('tel');				//联系电话
				$qqnum=post_get('qqnum');				//QQ号码
				$email=post_get('email');				//电子邮件
				$address=post_get('address');				//联系地址
				$touxiang=post_get('touxiang');				//头像
				$conttent=post_get('conttent');				//简介
				if($gradval == 0){
					echo json_encode(array('code'=>0,'msg'=>'请选择管理权限！'));
					return false;
				}
				$jumpwval=0;
				//事务处理
				Db::startTrans();
				try{
					$adm=new Admin();
					$admcl=$adm::get($id);
					$admcl->ad_grod=$gradval;
					$admcl->ad_setval=$adsetval;
					$admcl->save();				//存储帐号信息

					$adam=new Admindata();
					$adamcl=$adam::get($id);
					if($adamcl){
						$adamcl->ada_name=$callname;
						$adamcl->ada_tel=$tel;
						$adamcl->ada_qq=$qqnum;
						$adamcl->ada_email=$email;
						$adamcl->ada_address=$address;
						$adamcl->ada_cont=$conttent;
						$adamcl->ada_imgpic=$touxiang;
						$adamcl->save();				//存储管理员信息
					}else{
						$adam->ada_id=$id;
						$adam->ada_name=$callname;
						$adam->ada_tel=$tel;
						$adam->ada_qq=$qqnum;
						$adam->ada_email=$email;
						$adam->ada_address=$address;
						$adam->ada_cont=$conttent;
						$adam->ada_imgpic=$touxiang;
						$adam->save();				//存储管理员信息
					}
					
					$jumpwval=1;
					Db::commit();
				} catch (\Exception $e) {
				    // 回滚事务
				    Db::rollback();
				}
				if($jumpwval === 1){
					echo json_encode(array('code'=>0,'msg'=>'修改完成！'));
				}else{
					echo json_encode(array('code'=>0,'msg'=>'修改完成！资料没有变动！'));
				}
				break;
			default:
				$adm=new Admin();
				$list=$adm->adminlist();
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list',$list);
				$this->assign('page',$page->pagelist());
				$this->assign('empt','<tr><td colspan="9">暂没有数据！请更新！</td></tr>');
				return $this->fetch();
				break;
		}
	}
	/*
	*权限列表
	*/
	public function grodindex(){
		$action=post_get('action')?post_get('action'):'list';
		switch ($action) {
			case 'add':
				return $this->fetch('addgrod');
				break;
			case 'addcl':
				$gradname=post_get('gradname');						//权限名称
				$gradcont=post_get('gradcont');						//权限描述
				$gradset=post_get('gradset')?1:0;						//状态
				$grdbox=post_get('grdbox');						//权限配置
				$sxval=0;
				$qzval=0;
				if(is_array($grdbox)){
					foreach ($grdbox as $valsx) {
	                    if($sxval != ''){
	                        $sxval=$sxval.'|'.$valsx;
	                    }else{
	                        $sxval=$valsx;
	                    }
	                    if(in_array($valsx, explode('|','1|2|7|14|18|19|23|27|32|33|39|45|46|47|51|52'))){
	                    	$qzval++;
	                    }
	                }
				}
				$grdm=new Grades();
				if($gradname == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入权限名称");history.go(-1);</script>';
					return false;
				}
				if($grdm->jumpgrdtel($gradname)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("该权限已经存在！请更换！");history.go(-1);</script>';
					return false;
				}
				if($sxval==0){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请分配权限！");history.go(-1);</script>';
					return false;
				}
				$grdm->grd_title=$gradname;
				$grdm->grd_cont=$gradcont;
				$grdm->grd_setval=$gradset;
				$grdm->grd_time=time();
				$grdm->grd_arycon=$sxval;
				$grdm->grd_val=$qzval;
				$sesadmin=jumpsession();
				if($sesadmin){
					$grdm->grd_uid=$sesadmin['uid'];
				}
				if($grdm->save()){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.url('admingrod/grodindex',array('action'=>'add')).'";}else{location.href="'.url('admingrod/grodindex',array('action'=>'list')).'";}</script>';
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("添加失败！请重新提交！");history.go(-1);</script>';
				}
				break;
			case 'ajaxeite':
				$id=post_get('id');
				if($id == ''){
					echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
					return false;
				}
				$grdm=new Grades();
				$list=$grdm->find($id);
				if($list){
					echo json_encode(array('code'=>1,'msg'=>'获取成功！','info'=>$list));
				}else{
					echo json_encode(array('code'=>0,'msg'=>'此信息不存在！'));
				}
				
				break;
			case 'ajaxeitecl':
				$id=post_get('id');
				if($id == ''){
					echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
					return false;
				}
				$gradname=post_get('gradname');						//权限名称
				$gradnameold=post_get('gradnameold');						//权限名称
				$gradcont=post_get('gradcont');						//权限描述
				$gradset=post_get('gradset')?1:0;						//状态
				if($gradname == ''){
					echo json_encode(array('code'=>0,'msg'=>'请输入权限名称'));
					return false;
				}
				$grdm=new Grades();
				if($gradname != $gradnameold && $grdm->jumpgrdtel($gradname)){
					echo json_encode(array('code'=>0,'msg'=>'该权限已经存在！请更换！'));
					return false;
				}
				$eitcon['grd_title']=$gradname;
				$eitcon['grd_cont']=$gradcont;
				$eitcon['grd_setval']=$gradset;
				/*$grdet=$grdm::get($id);
				$grdet->grd_title=$gradname;
				$grdet->grd_cont=$gradcont;
				$grdet->grd_setval=$gradset;*/
				$eitls=Db::name('grades')->where(array('grd_id'=>$id))->update($eitcon);
				/*if($eitls == 1){
					echo json_encode(array('code'=>1,'msg'=>'修改完成！'));
					return false;
				}else{
					echo json_encode(array('code'=>2,'msg'=>'修改完成！资料没有变动！'));
					return false;
				}*/
				echo json_encode(array('code'=>1,'msg'=>'修改完成！','info'=>$eitls));
				break;
			case 'ajaxpeizi':
				$id=post_get('id');
				if($id == ''){
					echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
					return false;
				}
				$grdm=new Grades();
				$list=$grdm->find($id);
				if($list){
					$grsls='<label><span>文章管理</span><input type="checkbox" name="setquanxin[]" id="setquanxin" value="1"';
					if(in_array(1, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></label>';

					$grsls.='<ul>';

					$grsls.='<li><span><em>内容列表</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="2" ';
					if(in_array(2, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';

					$grsls.='<em>添加</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="3" ';
					if(in_array(3, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="4" ';
					if(in_array(4, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="5" ';
					if(in_array(5, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>查看</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="6" ';
					if(in_array(6, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='</i></li>';

					$grsls.='<li><span><em>栏目列表</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="7" ';
					if(in_array(7, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';

					$grsls.='<em>添加栏目</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="8" ';
					if(in_array(8, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>添加子栏目</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="9" ';
					if(in_array(9, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>添加内容</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="10" ';
					if(in_array(10, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="11" ';
					if(in_array(11, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="12" ';
					if(in_array(12, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>查看</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="13" ';
					if(in_array(13, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='</i></li>';

					$grsls.='<li><span><em>回收站</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="14" ';
					if(in_array(14, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';

					$grsls.='<em>还原</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="15" ';
					if(in_array(15, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="16" ';
					if(in_array(16, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>查看</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="17" ';
					if(in_array(17, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='</i></li>';

					$grsls.='</ul>';

					$grsls.='<label><span>广告管理</span><input type="checkbox" name="setquanxin[]" id="setquanxin" value="18"';
					if(in_array(18, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></label>';

					$grsls.='<ul>';

					$grsls.='<li><span><em>广告类型</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="19" ';
					if(in_array(19, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';

					$grsls.='<em>添加</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="20" ';
					if(in_array(20, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="21" ';
					if(in_array(21, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="22" ';
					if(in_array(22, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='</i></li>';

					$grsls.='<li><span><em>广告列表</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="23" ';
					if(in_array(23, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';

					$grsls.='<em>添加</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="24" ';
					if(in_array(24, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="25" ';
					if(in_array(25, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="26" ';
					if(in_array(26, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';
					
					$grsls.='</i></li>';

					$grsls.='</ul>';

					$grsls.='<label><span>友情链接</span><input type="checkbox" name="setquanxin[]" id="setquanxin" value="27"';
					if(in_array(27, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></label>';

					$grsls.='<ul>';
					$grsls.='<li><i>';

					$grsls.='<em>添加</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="28" ';
					if(in_array(28, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="29" ';
					if(in_array(29, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="30" ';
					if(in_array(30, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>查看</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="31" ';
					if(in_array(31, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='</i></li>';
					$grsls.='</ul>';




					$grsls.='<label><span>客户留言</span><input type="checkbox" name="setquanxin[]" id="setquanxin" value="52"';
					if(in_array(52, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></label>';

					$grsls.='<ul>';
					$grsls.='<li><i>';

					$grsls.='<em>回复</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="53" ';
					if(in_array(53, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="54" ';
					if(in_array(54, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>查看</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="55" ';
					if(in_array(55, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='</i></li>';
					$grsls.='</ul>';




					$grsls.='<label><span>系统管理员</span><input type="checkbox" name="setquanxin[]" id="setquanxin" value="32"';
					if(in_array(32, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></label>';

					$grsls.='<ul>';

					$grsls.='<li><span><em>管理员列表</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="33" ';
					if(in_array(33, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';

					$grsls.='<em>添加</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="34" ';
					if(in_array(34, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改密码</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="35" ';
					if(in_array(35, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改资料</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="36" ';
					if(in_array(36, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="37" ';
					if(in_array(37, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>查看</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="38" ';
					if(in_array(38, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='</i></li>';

					$grsls.='<li><span><em>管理权限</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="39" ';
					if(in_array(39, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';

					$grsls.='<em>添加</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="40" ';
					if(in_array(40, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>配置权限</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="41" ';
					if(in_array(41, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改资料</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="42" ';
					if(in_array(42, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="43" ';
					if(in_array(43, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>查看</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="44" ';
					if(in_array(44, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';
					
					$grsls.='</i></li>';

					$grsls.='</ul>';

					$grsls.='<label><span>系统设置</span><input type="checkbox" name="setquanxin[]" id="setquanxin" value="45"';
					if(in_array(45, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></label>';

					$grsls.='<ul>';
					$grsls.='<li><span><em>站点设置</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="46" ';
					if(in_array(46, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';
					$grsls.='</i></li>';

					$grsls.='<li><span><em>栏目类型</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="47" ';
					if(in_array(47, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';

					$grsls.='<em>添加</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="48" ';
					if(in_array(48, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>修改</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="49" ';
					if(in_array(49, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='<em>删除</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="50" ';
					if(in_array(50, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' />';

					$grsls.='</i></li>';

					$grsls.='<li><span><em>备份/还原数据库</em><input type="checkbox" name="setquanxin[]" id="setquanxin" value="51" ';
					if(in_array(51, explode('|',$list['grd_arycon']))){
						$grsls.=' checked ';
					}
					$grsls.=' /></span><i>';
					$grsls.='</i></li>';

					$grsls.='</ul>';

					echo json_encode(array('code'=>1,'msg'=>'获取成功！','info'=>$list,'grdbut'=>$grsls));
				}else{
					echo json_encode(array('code'=>0,'msg'=>'此信息不存在！'));
				}
				break;
			case 'ajaxpeizicl':
				$id=post_get('id');
				$grdval=post_get('grdval')?post_get('grdval'):0;
				if($id == ''){
					echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
					return false;
				}
				$qzval=0;
				foreach (explode('|', $grdval) as $key => $valsx) {
					if(in_array($valsx, explode('|','1|2|7|14|18|19|23|27|32|33|39|45|46|47|51|52'))){
	                    $qzval++;
	                }
				}
				
				$grdm=new Grades();
				$grdetg=$grdm::get($id);
				$grdetg->grd_arycon=$grdval;
				$grdetg->grd_val=$qzval;
				if($grdetg->save()){
					echo json_encode(array('code'=>1,'msg'=>'处理完成'));
				}else{
					echo json_encode(array('code'=>1,'msg'=>'处理完成,权限没有变动！'));
				}
				break;

			default:
				$grdm=new Grades();
				$list=$grdm->grdlist();
				$page=new pages($list->currentPage(),$list->lastPage());
				$this->assign('list',$list);
				$this->assign('page',$page->pagelist());
				$this->assign('empt','<tr><td colspan="5">暂没有数据！请更新！</td></tr>');
				return $this->fetch();
				break;
		}
	}
	/*
	*删除管理级别
	*/
	public function delgrade(){
		$id=post_get('id');
		if($id){
			$jumpval=0;
			Db::startTrans();
			try{
				$deladm=Db::name('admin')->where(array('grd_id'=>$id))->select();
				foreach ($deladm as $valdelad) {
					Db::name('admindata')->where(array('ada_id'=>$deladm['ad_id']))->delete();
				}
				Db::name('admin')->where(array('grd_id'=>$id))->delete();
				Db::name('grades')->where(array('grd_id'=>$id))->delete();
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
	*删除管理员
	*/
	public function deladmin(){
		$id=post_get('id');
		if($id){
			$jumpval=0;
			Db::startTrans();
			try{
				
				Db::name('admin')->where(array('ad_id'=>$id))->delete();
				Db::name('admindata')->where(array('ada_id'=>$id))->delete();
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
	*管理员个人信息处理
	*/
	public function mycont(){
		$action=post_get('action')?post_get('action'):'mycont';
		$id=post_get('id');
		$this->assign('id',$id);
		switch ($action) {
			case 'myeitecont':
				$adam=new Admindata();
				$adamc=$adam::get($id);
				$adamc->ada_name=post_get('callname');
				$adamc->ada_tel=post_get('calltel');
				$adamc->ada_qq=post_get('qqnum');
				$adamc->ada_email=post_get('email');
				$adamc->ada_address=post_get('address');
				$adamc->ada_imgpic=post_get('smlpicval');
				$adamc->ada_cont=post_get('content');
				$adamc->save();
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo '<script>alert("修改成功");location.href="'.url('admingrod/mycont',array('action'=>'mycont','id'=>$id)).'"</script>';
				break;
			case 'mypwd':
				return $this->fetch('mypwd');
				break;
			case 'myeitepwdc':
				$oldpwd=post_get('oldpwd');
				$newpwd=post_get('newpwd');
				$qrnewpwd=post_get('qrnewpwd');
				if($oldpwd == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入原密码！");history.go(-1);</script>';
					return;
				}
				if($newpwd == ''){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("请输入新密码！");history.go(-1);</script>';
					return;
				}
				if($newpwd != $qrnewpwd){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("您两次输入的新密码不一致！");history.go(-1);</script>';
					return;
				}
				$adm=new Admin();
				if(!$adm->ysmmjump($id,$oldpwd)){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo '<script>alert("您输入的原始密码不正确！");history.go(-1);</script>';
					return;
				}
				$admcpwd=$adm::get($id);
				$admcpwd->ad_pwdval=jiamimd5($qrnewpwd);
				$admcpwd->save();
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo '<script>alert("修改成功");location.href="'.url('admingrod/mycont',array('action'=>'mypwd','id'=>$id)).'"</script>';
				break;
			default:
				$adam=new Admindata();
				$this->assign('list',$adam->find($id));
				return $this->fetch();
				break;
		}
	}
}
?>