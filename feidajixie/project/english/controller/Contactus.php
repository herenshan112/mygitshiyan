<?php
namespace app\english\controller;
use think\Controller;
use think\Db;
use app\english\model\Link;				//友情链接
use app\english\model\Contents;			//文章内容
use app\english\model\Pagetype;			//文章栏目
use app\english\model\Advcont;			//广告内容
use app\publicclass\pagesinen;
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
    	$this->assign('banlst',$bnam->index_advlist(2,10));
    	//友情链接
    	$lnkm=new Link();
    	$this->assign('link_list',$lnkm->footlink(10));

    	$protym=new Pagetype();
    	$this->assign('protym',$protym->cont_tylists(20));

    	$tyid=post_get('tyid')?post_get('tyid'):21;
    	$this->assign('tyid',$tyid);
    	$webset=websetcont();
    	$tyset=$protym->find($tyid);

    	$this->assign('webnameval',$tyset['pty_title'].'-'.$webset['enwebname']);
    	if($tyset['pty_keyword'] != ''){
    		$this->assign('webkeyval',$tyset['pty_keyword']);
    	}else{
    		$this->assign('webkeyval',$webset['enkeyword']);
    	}
    	if($tyset['pty_destons'] != ''){
    		$this->assign('webdesval',$tyset['pty_destons']);
    	}else{
    		$this->assign('webdesval',$webset['endescription']);
    	}
    	$this->assign('list',$webset);
    	return $this->fetch();
	}
}
?>