<?php
namespace app\english\controller;
use think\Controller;
use think\Db;
use app\english\model\Link;				//友情链接
use app\english\model\Search;				//热门关键词
use app\english\model\Contents;			//文章内容
use app\english\model\Pagetype;			//文章栏目
use app\english\model\Advcont;			//广告内容
/*
*首页
*/
class Index extends Controller
{
	public function _initialize(){
		$this->assign('headset',1);
	}
    public function index()
    {
    	//banner列表
    	$bnam=new Advcont();
    	$this->assign('banlst',$bnam->index_advlist(2,10));
    	//热搜关键词
    	$seam=new Search();
    	$this->assign('sear_list',$seam->index_search(6));
    	//友情链接
    	$lnkm=new Link();
    	$this->assign('link_list',$lnkm->footlink(10));

        $webset=websetcont();
        
        $this->assign('webnameval',$webset['enwebname'].'-Home');
       
        $this->assign('webkeyval',$webset['enkeyword']);
       
        $this->assign('webdesval',$webset['endescription']);


    	//产品列表
    	$conm=new Contents();
    	$con_list=$conm->index_prolist(19,12,1);
    	$fyq=0;
    	$fyqs=0;
    	$in_pro=array();
    	foreach ($con_list as $key => $value) {
    		if($fyq < 4){
    			$in_pro[$fyqs][]=$value;
    			$fyq++;
    		}else{
    			$fyq=0;
    			$fyqs++;
                $in_pro[$fyqs][]=$value;
    		}
    	}
    	$this->assign('in_prols',$in_pro);
    	//产品类别
    	$protym=new Pagetype();
    	$this->assign('protym',$protym->index_typelist(19,6));
    	//厂房长毛头条
    	$this->assign('cf_top',$conm->index_prolist(23,1,2));
    	//厂房长毛推荐
    	$this->assign('cf_tui',$conm->index_prolist(23,2,1));
    	//企业风采
    	$con_fclist=$conm->index_prolist(25,12,1);
    	$fcnxh=0;
    	$fcwxh=0;
    	$in_fccon=array();
    	foreach ($con_fclist as $fckey => $valfc) {
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
    	//飞达简介
    	$this->assign('fdjianjie',$protym->find(18));
    	//飞达承诺
    	$this->assign('fdcn',$protym->find(24));
    	//飞达服务项目
    	$this->assign('fwxm',$protym->find(22));
    	$this->assign('fwxm_ls',$conm->index_prolist(22,2,1));
    	//公司动态
    	$this->assign('newdt_top',$conm->index_prolist(27,1,2));
    	$this->assign('newdt_tj',$conm->index_prolist(27,3,1));
    	//行业资讯
    	$this->assign('newhy_top',$conm->index_prolist(28,1,2));
    	$this->assign('newhy_tj',$conm->index_prolist(28,3,1));
    	//常见问题
    	$this->assign('cjwt_tj',$conm->index_prolist(29,3,1));
        return $this->fetch();
    }
}
