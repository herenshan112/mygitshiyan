<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Link;				//友情链接
use app\index\model\Contents;			//文章内容
use app\index\model\Pagetype;			//文章栏目
use app\index\model\Advcont;			//广告内容
use app\publicclass\pagesin;
/*
*联系我
*/
/**
* 
*/
class Contactus extends Controller
{
	public function _initialize(){
		$this->assign('headset',5);
	}
	
	public function index(){
		//banner列表
    	$bnam=new Advcont();
    	$this->assign('banlst',$bnam->index_advlist(1,10));
    	//友情链接
    	$lnkm=new Link();
    	$this->assign('link_list',$lnkm->footlink(10));

    	$protym=new Pagetype();
    	$this->assign('protym',$protym->cont_tylists(3));

    	$tyid=post_get('tyid')?post_get('tyid'):4;
    	$this->assign('tyid',$tyid);
    	$webset=websetcont();
    	$tyset=$protym->find($tyid);

    	$this->assign('webnameval',$tyset['pty_title'].'-'.$webset['webname']);
    	if($tyset['pty_keyword'] != ''){
    		$this->assign('webkeyval',$tyset['pty_keyword']);
    	}else{
    		$this->assign('webkeyval',$webset['keyword']);
    	}
    	if($tyset['pty_destons'] != ''){
    		$this->assign('webdesval',$tyset['pty_destons']);
    	}else{
    		$this->assign('webdesval',$webset['description']);
    	}
    	$this->assign('list',$webset);
    	return $this->fetch();
	}
}
?>