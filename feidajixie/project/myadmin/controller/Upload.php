<?php
namespace app\myadmin\controller;
use think\Controller;
use think\Db;
use app\publicclass\share;
use app\publicclass\uploadfile;
/*
*上传
*/
/**
* 
*/
class Upload extends Controller
{
	
	public function uploades(){
		$typeid=post_get('typeid');//;
        $conlst=Db::name('config')->where('1=1')->order(array('id'=>'desc'))->find();
        switch ($typeid) {
            case 1:
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './project/myadmin/view/template/',
                    'classtype'     =>       'html|htm',
                    'filesize'      =>       $conlst['filesize']*1024,
                    'nametrue'      =>       2,
                );
                break;
            case 2:
                $filecon=array(
                    'filenam'       =>       'upload_filees',
                    'dir_base'      =>       './project/index/View/template/',
                    'classtype'     =>       'html|htm',
                    'filesize'      =>       $conlst['filesize'],
                    'nametrue'      =>       2,
                );
                break;
            case 3:
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/images/',
                    'classtype'     =>       $conlst['imagestype'],
                    'filesize'      =>       $conlst['imagesize'],
                    'nametrue'      =>       1,
                );
                break;
            case 4:
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/void/',
                    'classtype'     =>       $conlst['vidotype'],
                    'filesize'      =>       $conlst['vidosize']*1024,
                    'nametrue'      =>       1,
                );
                break;
            case 5:
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/download/',
                    'classtype'     =>       $conlst['filetype'],
                    'filesize'      =>       $conlst['filesize']*1024,
                    'nametrue'      =>       1,
                );
                break;
            case 6:
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/vip/',
                    'classtype'     =>       $conlst['imagestype'],
                    'filesize'      =>       $conlst['imagesize'],
                    'nametrue'      =>       1,
                );
                break;
            case 7:
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/images/',
                    'classtype'     =>       $conlst['imagestype'],
                    'filesize'      =>       $conlst['imagesize'],
                    'nametrue'      =>       1,
                );
                break;
            default:
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/',
                    'classtype'     =>       $conlst['imagestype'],
                    'filesize'      =>       1,
                    'nametrue'      =>       1,
                );
                break;
        }
        //var_dump($filecon);
        if($typeid == 7){
            echo uploadfile::Uploadfileary($filecon);
        }else{
            echo uploadfile::Upload($filecon);
        }
        
	}
}
?>