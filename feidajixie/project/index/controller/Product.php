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
*产品控制器
*/
/**
* 
*/
class Product extends Controller
{
	public function _initialize(){
		$this->assign('headset',3);
	}
	/*
	*产品首页
	*/
	public function index(){
		
		//banner列表
    	$bnam=new Advcont();
    	$this->assign('banlst',$bnam->index_advlist(1,10));
    	//友情链接
    	$lnkm=new Link();
    	$this->assign('link_list',$lnkm->footlink(10));

    	//产品类别
    	$protym=new Pagetype();
    	$this->assign('protym',$protym->cont_tylist(2));



    	$tyid=post_get('tyid')?post_get('tyid'):2;
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
    	$list=$conm->cont_list($tyid,$tyset['pty_pgsum']);
    	$page=new pagesin($list->currentPage(),$list->lastPage(),array('tyid'=>$tyid));
    	$this->assign('list',$list);
    	$this->assign('pages',$page->pagelist());
		return $this->fetch();
	}
	/*
	*产品内容
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
    	$this->assign('protym',$protym->cont_tylist(2));
    	$id=post_get('id');
    	if($id == ''){
    		echo '参数错误！';
    		return;
    	}
    	$conm=new Contents();
    	$list=$conm->find($id);
    	$imgary=array();
    	if($list['cn_smallimg'] != ''){
    		$imgary[]=array(
    			'imgsrc'			=>					'/upload/images/'.$list['cn_smallimg'],
    			'title'				=>					$list['cn_title']
    		);
    	}else{
    		$imgary[]=array(
    			'imgsrc'			=>					'/public/images/33.png',
    			'title'				=>					$list['cn_title']
    		);
    	}
    	if($list['cn_imgary'] != ''){
    		foreach (explode(',', $list['cn_imgary']) as $valpic) {
    			$arim=explode('|', $valpic);
    			$imgary[]=array(
	    			'imgsrc'			=>					'/upload/images/'.$arim[0],
	    			'title'				=>					$arim[1]
	    		);
    		}
    	}
    	$list['imageary']=$imgary;
    	$this->assign('list',$list);
    	$tyidv=2;
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



    	//相关产品
    	$xgprd=$conm->xgcp($id,$tyidv,12);

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
            $this->assign('prourl','<a href="'.url('product/details',array('id'=>$prourl['cn_id'])).'">'.$prourl['cn_title'].'</a>');
        }else{
            $this->assign('prourl','<a href="#">无</a>');
        }
        //下一篇
        $nexurl=$conm->precont($id,$tyidv,'next');
        if($nexurl){
            $this->assign('nexurl','<a href="'.url('product/details',array('id'=>$nexurl['cn_id'])).'">'.$nexurl['cn_title'].'</a>');
        }else{
            $this->assign('nexurl','<a href="#">无</a>');
        }
        

    	return $this->fetch();
	}
	/*
	*留言处理
	*/
	public function mesages(){
		$id=post_get('id');
		$callname=post_get('callname');
		$tel=post_get('tel');
		$emaile=post_get('emaile');
		$addres=post_get('addres');
		$beizhu=post_get('beizhu');
		if($callname == ''){
			echo json_encode(array('code'=>0,'msg'=>'请输入联系人姓名！'));
			return;
		}
		if($tel == ''){
			echo json_encode(array('code'=>0,'msg'=>'请输入联系电话！'));
			return;
		}
		if($emaile == ''){
			echo json_encode(array('code'=>0,'msg'=>'请输入邮箱！'));
			return;
		}
		if($addres == ''){
			echo json_encode(array('code'=>0,'msg'=>'请输入联系地址！'));
			return;
		}
		if($beizhu == ''){
			echo json_encode(array('code'=>0,'msg'=>'请输入备注！'));
			return;
		}
		$mesary=array(
			'mes_name'				=>				htmlspecialchars($callname),
			'mes_tel'				=>				htmlspecialchars($tel),
			'mes_email'				=>				htmlspecialchars($emaile),
			'mes_address'			=>				htmlspecialchars($addres),
			'mes_cont'				=>				htmlspecialchars($beizhu),
			'mes_time'				=>				time(),
			'mes_porid'				=>				$id
		);
		if(Db::name('message')->insert($mesary)){
			echo json_encode(array('code'=>1,'msg'=>'您的信息已经提交！稍后会有我们的工作人员与您联系！'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'您的信息提交失败！请重新提交！'));
		}
	}
}