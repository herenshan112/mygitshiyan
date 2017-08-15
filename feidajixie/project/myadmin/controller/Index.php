<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\myadmin\model\Contents;
use app\myadmin\model\Admin;

class Index extends Controller{

    public function _initialize(){
        if(!jumpsession()){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>top.location.href="'.url('login/index').'"</script>';
        }
    }
    
    public function index()
    {
        $sesval=session('admin');
        $lefadm=Db::name('admin')->alias('a')
                                    ->field('a.*,d.*,g.*')
                                    ->join('__ADMINDATA__ d','a.ad_id = d.ada_id','LEFT')
                                    ->join('__GRADES__ g','a.ad_grod = g.grd_id','LEFT')
                                    ->where(array('a.ad_id'=>$sesval['uid']))
                                    ->find();
        $this->assign('lefadm',$lefadm);
        $this->assign('sesval',$sesval);
        return $this->fetch();
    }
    /*
    *首页
    */
    public function main(){
        /*$mydan=date('w');
        echo $mydan;
        var_dump(get_riqi(date('Y-m-d')));*/
    	return $this->fetch();
    }
    
    /*
    *站点设置
    */
    public function webset(){
        $webname=post_get('webname');
        $company=post_get('company');
        $companyadd=post_get('companyadd');
        $icpnum=post_get('icpnum');
        $websetval=post_get('websetval')?1:0;

        $weburl=post_get('weburl');


        $enwebname=post_get('enwebname');
        $encompany=post_get('encompany');
        $encompanyadd=post_get('encompanyadd');
        $enicpnum=post_get('enicpnum');

        $valjump=Db::name('config')->order(array('id'=>'desc'))->find();
        if(isset($_POST['tijiao'])){
            $saveval=array(
                'webname'           =>              $webname,
                'companyname'       =>              $company,
                'companyadd'        =>              $companyadd,
                'icpnumber'         =>              $icpnum,
                'webset'            =>              $websetval,
                'weburl'            =>              $weburl,
                'enwebname'         =>              $enwebname,
                'encompanyname'     =>              $encompany,
                'encompanyadd'      =>              $encompanyadd,
                'enicpnumber'       =>              $enicpnum,
            );
            if($valjump){
                if(Db::name('config')->where(array('id'=>$valjump['id']))->update($saveval)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("修改成功");location.href="'.url('index/webset').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("内容与以前的数据没有区别，修改失败");location.href="'.url('index/webset').'"</script>';
                }
            }else{
                if(Db::name('config')->insert($saveval)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("添加成功");location.href="'.url('index/webset').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("添加失败");location.href="'.url('index/webset').'"</script>';
                }
            }
            $this->assign('webname',$webname);
            $this->assign('company',$company);
            $this->assign('companyadd',$companyadd);
            $this->assign('icpnum',$icpnum);
            $this->assign('websetval',$websetval);
            $this->assign('weburl',$weburl);

            $this->assign('enwebname',$enwebname);
            $this->assign('encompany',$encompany);
            $this->assign('encompanyadd',$encompanyadd);
            $this->assign('enicpnum',$enicpnum);
        }else{
            if($valjump){
                $this->assign('webname',$valjump['webname']);
                $this->assign('company',$valjump['companyname']);
                $this->assign('companyadd',$valjump['companyadd']);
                $this->assign('icpnum',$valjump['icpnumber']);
                $this->assign('websetval',$valjump['webset']);
                $this->assign('weburl',$valjump['weburl']);

                $this->assign('enwebname',$valjump['enwebname']);
                $this->assign('encompany',$valjump['encompanyname']);
                $this->assign('encompanyadd',$valjump['encompanyadd']);
                $this->assign('enicpnum',$valjump['enicpnumber']);
            }else{
                $this->assign('webname',$webname);
                $this->assign('company',$company);
                $this->assign('companyadd',$companyadd);
                $this->assign('icpnum',$icpnum);
                $this->assign('websetval',$websetval);
                $this->assign('weburl',$weburl);

                $this->assign('enwebname',$enwebname);
                $this->assign('encompany',$encompany);
                $this->assign('encompanyadd',$encompanyadd);
                $this->assign('enicpnum',$enicpnum);
            }
        }
        return $this->fetch();
    }
    /*
    *seo设置
    */
    public function seoset(){
        $keywords=post_get('keywords');
        $description=post_get('description');

        $enkeywords=post_get('enkeywords');
        $endescription=post_get('endescription');

        $valjump=Db::name('config')->order(array('id'=>'desc'))->find();
        if(isset($_POST['seosettj'])){
            $saveval=array(
                'keyword'           =>              $keywords,
                'description'       =>              $description,
                'enkeyword'         =>              $enkeywords,
                'endescription'     =>              $endescription
            );
            if($valjump){
                if(Db::name('config')->where(array('id'=>$valjump['id']))->update($saveval)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("修改成功");location.href="'.url('index/seoset').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("内容与以前的数据没有区别，修改失败");location.href="'.url('index/seoset').'"</script>';
                }
            }else{
                if(Db::name('config')->insert($saveval)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("添加成功");location.href="'.url('index/seoset').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("添加失败");location.href="'.url('index/seoset').'"</script>';
                }
            }
            $this->assign('keywords',$keywords);
            $this->assign('description',$description);

            $this->assign('enkeywords',$enkeywords);
            $this->assign('endescription',$endescription);
        }else{
            if($valjump){
                $this->assign('keywords',$valjump['keyword']);
                $this->assign('description',$valjump['description']);
                $this->assign('enkeywords',$valjump['enkeyword']);
                $this->assign('endescription',$valjump['endescription']);
            }else{
                $this->assign('keywords',$keywords);
                $this->assign('description',$description);
                $this->assign('enkeywords',$enkeywords);
                $this->assign('endescription',$endescription);
            }
        }
        return $this->fetch();
    }
    /*
    *负责人设置
    */
    public function fuzerenset(){
        $chargename=post_get('chargename');
        $mobile=post_get('mobile');
        $telephone=post_get('telephone');
        $faxnum=post_get('faxnum');
        $email=post_get('email');
        $qqnum=post_get('qqnum');

        $youbiannum=post_get('youbiannum');

        $valjump=Db::name('config')->order(array('id'=>'desc'))->find();
        if(isset($_POST['fzrtijiao'])){
            $saveval=array(
                'Personname'            =>              $chargename,
                'mobel'                 =>              $mobile,
                'telval'                =>              $telephone,
                'email'                 =>              $email,
                'faxnum'                =>              $faxnum,
                'qqnumber'              =>              $qqnum,
                'webyoubian'            =>              $youbiannum
            );
            if($valjump){
                if(Db::name('config')->where(array('id'=>$valjump['id']))->update($saveval)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("修改成功");location.href="'.url('index/fuzerenset').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("内容与以前的数据没有区别，修改失败");location.href="'.url('index/fuzerenset').'"</script>';
                }
            }else{
                if(Db::name('config')->insert($saveval)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("添加成功");location.href="'.url('index/fuzerenset').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("添加失败");location.href="'.url('index/fuzerenset').'"</script>';
                }
            }
            $this->assign('chargename',$chargename);
            $this->assign('mobile',$mobile);
            $this->assign('telephone',$telephone);
            $this->assign('email',$email);
            $this->assign('faxnum',$faxnum);
            $this->assign('qqnum',$qqnum);
            $this->assign('youbiannum',$youbiannum);
        }else{
            if($valjump){
                $this->assign('chargename',$valjump['Personname']);
                $this->assign('mobile',$valjump['mobel']);
                $this->assign('telephone',$valjump['telval']);
                $this->assign('email',$valjump['email']);
                $this->assign('faxnum',$valjump['faxnum']);
                $this->assign('qqnum',$valjump['qqnumber']);
                $this->assign('youbiannum',$valjump['webyoubian']);
            }else{
                $this->assign('chargename',$chargename);
                $this->assign('mobile',$mobile);
                $this->assign('telephone',$telephone);
                $this->assign('email',$email);
                $this->assign('faxnum',$faxnum);
                $this->assign('qqnum',$qqnum);
                $this->assign('youbiannum',$youbiannum);
            }
        }
        return $this->fetch();
    }
    /*
    *核心设置
    */
    public function hexin(){
        $mobackval=post_get('mobackval');
        $databack=post_get('databack');
        $imagesize=post_get('imagesize');
        $imagestype=post_get('imagestype');
        $filesize=post_get('filesize');
        $filetype=post_get('filetype');
        $videosize=post_get('videosize');
        $videotype=post_get('videotype');
        $sandemo=post_get('sandemo');
        $userset=post_get('userset')?1:0;
        $sestime=post_get('sestime')?post_get('sestime'):30;

        $valjump=Db::name('config')->order(array('id'=>'desc'))->find();
        if(isset($_POST['hexintj'])){
            $saveval=array(
                'mobackval'                 =>              $mobackval,
                'databack'                  =>              $databack,
                'imagesize'                 =>              $imagesize,
                'imagestype'                =>              $imagestype,
                'filesize'                  =>              $filesize,
                'filetype'                  =>              $filetype,
                'vidosize'                  =>              $videosize,
                'vidotype'                  =>              $videotype,
                'sandaima'                  =>              $sandemo,
                'userset'                   =>              $userset,
                'session_time'              =>              $sestime
            );
            if($valjump){
                if(Db::name('config')->where(array('id'=>$valjump['id']))->update($saveval)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("修改成功");location.href="'.url('index/hexin').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("内容与以前的数据没有区别，修改失败");location.href="'.url('index/hexin').'"</script>';
                }
            }else{
                if(Db::name('config')->insert($saveval)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("添加成功");location.href="'.url('index/hexin').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("添加失败");location.href="'.url('index/hexin').'"</script>';
                }
            }
            $this->assign('mobackval',$mobackval);
            $this->assign('databack',$databack);
            $this->assign('imagesize',$imagesize);
            $this->assign('imagestype',$imagestype);
            $this->assign('filesize',$filesize);
            $this->assign('filetype',$filetype);
            $this->assign('videosize',$videosize);
            $this->assign('videotype',$videotype);
            $this->assign('sandemo',$sandemo);
            $this->assign('userset',$userset);
            $this->assign('sestime',$sestime);
        }else{
            if($valjump){
                
                $this->assign('mobackval',$valjump['mobackval']);
                $this->assign('databack',$valjump['databack']);
                $this->assign('imagesize',$valjump['imagesize']);
                $this->assign('imagestype',$valjump['imagestype']);
                $this->assign('filesize',$valjump['filesize']);
                $this->assign('filetype',$valjump['filetype']);
                $this->assign('videosize',$valjump['vidosize']);
                $this->assign('videotype',$valjump['vidotype']);
                $this->assign('sandemo',$valjump['sandaima']);
                $this->assign('userset',$valjump['userset']);
                $this->assign('sestime',$valjump['session_time']);
            }else{
                $this->assign('mobackval',$mobackval);
                $this->assign('databack',$databack);
                $this->assign('imagesize',$imagesize);
                $this->assign('imagestype',$imagestype);
                $this->assign('filesize',$filesize);
                $this->assign('filetype',$filetype);
                $this->assign('videosize',$videosize);
                $this->assign('videotype',$videotype);
                $this->assign('sandemo',$sandemo);
                $this->assign('userset',$userset);
                $this->assign('sestime',$sestime);
            }
        }
        return $this->fetch();
    }
    /*
    *实验
    */
    public function shiyan(){
    	$valz=post_get('tijiao');
    	echo $valz;
    }
    /*
    *退出系统
    */
    public function exitlogin(){
        session('admin',null);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        echo '<script>top.location.href="'.url('/index/index').'";</script>';
    }
    /*
    *统计数据图表
    */
    public function onemothzxt(){
        $action=post_get('action')?post_get('action'):'zxt';
        switch ($action) {
            case 'value':
                # code...
                break;
            
            default:
                $cnm=new Contents();
                $myddv=date('w');
                if($myddv == 0){
                    $myddv=7;
                }
                $benzhou=get_riqi(date('Y-m-d'));
                $datar=array();
                foreach ($benzhou as $key => $valtm) {
                    if($key <= $myddv){
                        $bgtim=strtotime($valtm.' 00:00:00');
                        $edtim=strtotime($valtm.' 23:59:59');
                        $datary[]=$cnm->datesum($bgtim,$edtim);
                    }else{
                        $datary[]='-';
                    }
                    
                }
                $adm=new Admin();
                $adls=$adm->lstadm();
                $datarbt=array();
                foreach ($adls as $key => $valadv) {
                   $datarbt[]=array(
                        'value'     =>      $cnm->adminfbsum($valadv['ad_id']),
                        'name'      =>      $valadv['ad_username']."\n".$valadv['ada_name']
                    );
                }
                //$datary=array(150,12,23,660,'-','-','-');
                echo json_encode(array('code'=>1,'info'=>$datary,'bing'=>$datarbt));
                break;
        }
    }
}
