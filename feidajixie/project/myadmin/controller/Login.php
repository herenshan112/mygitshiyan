<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\publicclass\verify;
use app\myadmin\model\Admin;
/*
*登陆
*/
/**
* 
*/
class Login extends Controller
{
	
	/*
	*登陆页面
	*/
	public function index(){
		return $this->fetch();
	}
	/*
	*验证码
	*/
	public function yzmcode(){
		$yzcode=new Verify();
		$yzcode->fontSize = 18;  
        $yzcode->length   = 4;  
        $yzcode->useNoise = false;  
        $yzcode->codeSet = '0123456789';  
        $yzcode->useImgBg = true; 
        $yzcode->expire = 60;  
        $yzcode->entry(1);  
	}

	/**
	 * 检测验证码
	 * @param  integer $id 验证码ID
	 * @param  integer $reset 是否重置验证码
	 * @return boolean     检测结果
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	*/
	static function check_verify($code, $id = 1,$reset = true){
	   $config['reset'] = $reset;//验证成功后不重置
	   $verify = new Verify($config);
	   return $verify->check($code, $id);
	}
	/*
	*判断验证码是否正确
	*/
	public function jumpcode(){
		$chose=post_get('chose');			//验证码
		if($chose != ''){
			if(!self::check_verify($chose,1,false)){
				echo json_encode(array('code'=>0,'msg'=>'验证码不正确！'));
				return ;
			}
			echo json_encode(array('code'=>1,'msg'=>'验证码正确'));
			return ;
		}
		echo json_encode(array('code'=>0,'msg'=>'请输入验证码！'));
		return ;
	}
	/*
	*登陆处理
	*/
	public function logincl(){
		$usename=post_get('usename');				//用户名
		$pwdadmin=post_get('pwdadmin');				//密码
		$chose=post_get('chose');				//验证码
		if($usename == ''){
			echo json_encode(array('code'=>0,'msg'=>'请输入用户名！'));
			return ;
		}
		if($pwdadmin == ''){
			echo json_encode(array('code'=>0,'msg'=>'请输入密码！'));
			return ;
		}
		if($chose == ''){
			echo json_encode(array('code'=>0,'msg'=>'请输入验证码！'));
			return ;
		}
		if(!self::check_verify($chose,1,true)){
			echo json_encode(array('code'=>0,'msg'=>'验证码不正确！'));
			return ;
		}
		$adm=new Admin();
		$list=$adm->jumpuspwd($usename,$pwdadmin);
		if($list){
			if($list['grd_setval'] != 1){
				echo json_encode(array('code'=>0,'msg'=>'该管理组已经被禁用！'));
				return;
			}
			if($list['ad_setval'] != 1){
				echo json_encode(array('code'=>0,'msg'=>'您的帐号已经被禁用！'));
				return;
			}
			session('admin',array(
				'time'			=>			time(),
				'uid'			=>			$list['ad_id'],
				'grdval'		=>			$list['grd_arycon'],
				'grdqz'			=>			$list['grd_val'],
			));
			$savad=$adm::get($list['ad_id']);
			$savad->ad_lasttime=time();
			$savad->ad_lastip=getIP();
			$savad->save();
			echo json_encode(array('code'=>1,'msg'=>'登陆成功！'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'您输入的用户名或密码错误！'));
		}
	}
}
?>