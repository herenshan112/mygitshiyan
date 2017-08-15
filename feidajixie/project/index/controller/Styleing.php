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
*新闻列表
*/
/**
* 
*/
class Styleing extends Controller
{
	
	public function _initialize(){
		$this->assign('headset',1);
	}
	/*
	*新闻首页
	*/
	public function index(){
		//banner列表
    	$bnam=new Advcont();
    	$this->assign('banlst',$bnam->index_advlist(1,10));
    	//友情链接
    	$lnkm=new Link();
    	$this->assign('link_list',$lnkm->footlink(10));

    	//新闻类别
    	$protym=new Pagetype();
    	$this->assign('protym',$protym->cont_tylists(10));



    	$tyid=post_get('tyid')?post_get('tyid'):10;
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

    	$conm=new Contents();

    	//头条
    	
        $list=$conm->cont_list($tyid,$tyset['pty_pgsum'],$tyid);

    	
    	$page=new pagesin($list->currentPage(),$list->lastPage(),array('tyid'=>$tyid));
    	$this->assign('list',$list);
    	$this->assign('pages',$page->pagelist());
    	return $this->fetch();
	}
	/*
	*新闻详细内容
	*/
	public function details(){
		//banner列表
    	$bnam=new Advcont();
    	$this->assign('banlst',$bnam->index_advlist(1,10));
    	//友情链接
    	$lnkm=new Link();
    	$this->assign('link_list',$lnkm->footlink(10));

    	//产品类别
    	$protym=new Pagetype();
    	$this->assign('protym',$protym->cont_tylist(10));
    	$id=post_get('id');
    	if($id == ''){
    		echo '参数错误！';
    		return;
    	}
    	$conm=new Contents();
    	$list=$conm->find($id);
    	
    	$this->assign('list',$list);
    	$tyidv=10;
    	if(isset($list['cn_type'])){
    		$tyidv=$list['cn_type'];
    	}
    	
    	$this->assign('tyid',$tyidv);


    	$tyset=$protym->find($tyidv);
    	$webset=websetcont();
    	$this->assign('webnameval',$list['cn_title'].'-'.$tyset['pty_title'].'-'.$webset['webname']);
    	if($list['cn_keyword'] != ''){
    		$this->assign('webkeyval',$list['cn_keyword']);
    	}else{
    		if($tyset['pty_keyword'] != ''){
	    		$this->assign('webkeyval',$tyset['pty_keyword']);
	    	}else{
	    		$this->assign('webkeyval',$webset['keyword']);
	    	}
    	}
    	if($list['cn_descr'] != ''){
    		$this->assign('webdesval',$list['cn_descr']);
    	}else{
	    	if($tyset['pty_destons'] != ''){
	    		$this->assign('webdesval',$tyset['pty_destons']);
	    	}else{
	    		$this->assign('webdesval',$webset['description']);
	    	}
    	}



    	//相关新闻
    	$xgprd=$conm->xgcp($id,$tyidv,10);

    	$fcnxh=0;
    	$fcwxh=0;
    	$in_fccon=array();
    	foreach ($xgprd as $fckey => $valfc) {
    		if($fcnxh < 3){
    			$in_fccon[$fcwxh][]=$valfc;
    			$fcnxh++;
    		}else{
    			$fcnxh=0;
    			$fcwxh++;
                $in_fccon[$fcwxh][]=$valfc;
    		}
    	}
    	$this->assign('in_fccon',$in_fccon);

    	//上一篇
        $prourl=$conm->precont($id,$tyidv);
        if($prourl){
            $this->assign('prourl','<a href="'.url('myserver/details',array('id'=>$prourl['cn_id'])).'">'.$prourl['cn_title'].'</a>');
        }else{
            $this->assign('prourl','<a href="#">无</a>');
        }
        //下一篇
        $nexurl=$conm->precont($id,$tyidv,'next');
        if($nexurl){
            $this->assign('nexurl','<a href="'.url('myserver/details',array('id'=>$nexurl['cn_id'])).'">'.$nexurl['cn_title'].'</a>');
        }else{
            $this->assign('nexurl','<a href="#">无</a>');
        }

    	return $this->fetch();
	}
}