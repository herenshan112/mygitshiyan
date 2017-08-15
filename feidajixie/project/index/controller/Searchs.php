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
class Searchs extends Controller
{
	public function _initialize(){
		$this->assign('headset',1);
		$webset=websetcont();
        
        $this->assign('webnameval','搜索-'.$webset['webname']);
       
        $this->assign('webkeyval',$webset['keyword']);
       
        $this->assign('webdesval',$webset['description']);
	}
	/*
	*搜索首页
	*/
	public function index(){
		//banner列表
    	$bnam=new Advcont();
    	$this->assign('banlst',$bnam->index_advlist(1,10));
    	//友情链接
    	$lnkm=new Link();
    	$this->assign('link_list',$lnkm->footlink(10));
    	$sousuoval=post_get('sousuoval');

    	$seltj=5;
    	$sun_type=get_suntype($seltj);
    	foreach ($sun_type as $valtj) {
				$seltj.=','.$valtj;
		}
		$whetj['cn_delt']=0;
		$whetj['cn_setval']=1;
		$whetj['cn_type']=array('IN',$seltj);

		$whetjor['cn_title']=array('INSTR',$sousuoval);

		$whetjor['cn_descr']=array('INSTR',$sousuoval);

		$keycz=Db::name('search')->where('ss_title',$sousuoval)->find();
		if($keycz){
			$jiayi=Db::name('search')->where('ss_id',$keycz['ss_id'])->setInc('ss_sum');
		}else{
			$xrkeyword=array(
				'ss_title'				=>				$sousuoval,
				'ss_sum'				=>				1,
				'ss_set'				=>				1,
				'ss_time'				=>				time()
			);
			$adtj=Db::name('search')->insert($xrkeyword);
		}


		$conm=new Contents();

		$list=$conm->alias('c')
					->field('c.*,t.pty_id,t.pty_title')
					->join('__PAGETYPE__ t','c.cn_type=t.pty_id','LEFT')
					->where('cn_title|cn_descr','INSTR',$sousuoval)
					->where($whetj)
					->order(array('cn_id'=>'desc'))
					->paginate(6);
		$page=new pagesin($list->currentPage(),$list->lastPage(),array('sousuoval'=>$sousuoval));
		$this->assign('list',$list);
    	$this->assign('pages',$page->pagelist());
    	$this->assign('sousuoval',$sousuoval);
		$this->assign('empt','没有查询到数据');
    	return $this->fetch();
	}
}