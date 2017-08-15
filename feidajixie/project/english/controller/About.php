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
class About extends Controller
{
	public function _initialize(){
		$this->assign('headset',2);
	}
	
	public function index(){
		//banner列表
    	$bnam=new Advcont();
    	$this->assign('banlst',$bnam->index_advlist(2,10));
    	//友情链接
    	$lnkm=new Link();
    	$this->assign('link_list',$lnkm->footlink(10));

    	$protym=new Pagetype();
    	$this->assign('protym',$protym->cont_tylists(18));

    	$tyid=post_get('tyid')?post_get('tyid'):18;
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
        //企业简介
        $this->assign('qyjj',$tyset);
        //技术服务
        $this->assign('jsfwval',$protym->find(22));
        //承诺
        $this->assign('lxcnval',$protym->find(24));

        //文档列表
        $cnm=new Contents();
        //厂房厂貌
        $this->assign('cfcmls',$cnm->index_prolist(23,3));
        //产品
        $this->assign('prodls',$cnm->index_prolist(19,3));
    	
    	return $this->fetch();
	}
}
?>