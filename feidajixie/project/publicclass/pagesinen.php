<?php
namespace app\publicclass;

class pagesinen{
    private $page;//当前页
    private $pagenum;//总页数
    private $rollPage = 5;//总页数
    private $arycont=array();   //附加参数
    public function __construct($page,$pagenum,$arycont=array()){
        $this->page=$page;
        $this->pagenum=$pagenum;
        $this->arycont=$arycont;

    }
    //首页
    private function first(){
        $html='';
        if($this->page==1){
            //@$html.='<a class="active" style="cursor:not-allowed;">首页</a>';
        }else{
            @$html.='<a href="?page=1'.$this->caijieary($this->arycont).'" >First</a>';
        }
        return $html;
    }
    //上一页
    private function prev(){
        $html='';
        if($this->page==1){
            //@$html.='<a class="icon-angle-left page_but font_size_16 hover" style="cursor:not-allowed;"></a>';
        }else{
            @$html.='<a  href="?page='.($this->page-1).$this->caijieary($this->arycont).'" >Previous</a>';
        }
        return $html;
    }
    //下一页
    private function next(){
        $html='';
        if($this->page == $this->pagenum){
            //@$html.='<a class="icon-angle-right page_but font_size_16 hover" style="cursor:not-allowed;"></a>';
        }else{
            @$html.='<a  href="?page='.($this->page+1).$this->caijieary($this->arycont).'">Next</a>';
        }
        return $html;
    }
    //尾页
    private function last(){
        $html='';
        if($this->page==$this->pagenum){
            //@$html.='<a class="icon-double-angle-right page_but font_size_16 hover" style="cursor:not-allowed;"></a>';
        }else{
            @$html.='<a  href="?page='.($this->pagenum).$this->caijieary($this->arycont).'">Last</a>';
        }
        return $html;
    }
    //当前页
    private function currentpage(){
        $linkpag='';
        /*if($this->pagenum >5){

        }else{
            for($i=1;$i<=$this->pagenum;$i++){
                if($i == $this->page){
                    $linkpag.='<a class="page_but hover">'.$i.'</a>';
                }else{
                    $linkpag.='<a class="page_but" href="?page='.$i.'">'.$i.'</a>';
                }
                
            }
        }*/

        $now_cool_page      = $this->rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        //echo $now_cool_page .'=>'.$now_cool_page.'<br>';
        for($i=1;$i<=5;$i++){
            if(($this->page - $now_cool_page) <= 0 ){
                $page = $i;
                // echo $page.'=>1<br>';
            }elseif(($this->page + $now_cool_page - 1) >= $this->pagenum){
                $page = $this->pagenum - $this->rollPage + $i;
                // echo $page.'=>2<br>';
            }else{
                $page = $this->page - $now_cool_page_ceil + $i;
                // echo $page.'=>3<br>';
            }

            //echo $page.'<br>';


            if($page > 0 && $page != $this->page){
                if($page <= $this->pagenum){
                    $linkpag .= '<a  href="?page=' . $page . $this->caijieary($this->arycont).'">' . $page . '</a>';
                }else{
                    break;
                }
            }else{
                if($page > 0 ){
                    $linkpag .= '<a class="active"  style="cursor:not-allowed;">' . $page . '</a>';
                }
            }
        }
        return $linkpag;



    }
    public function pagelist(){
        $pageviw=$this->first().$this->prev().$this->currentpage().$this->next().$this->last();
        return $pageviw;
        //return array('first'=>$this->first(),'prev'=>$this->prev(),'aaa'=>$this->currentpage(),'next'=>$this->next(),'last'=>$this->last());
    }

    /*
    *拆解附加数组
    */
    static function caijieary($arycont){
        $linkval='';
        if(is_array($arycont)){
            foreach ($arycont as $keylnk => $vallink) {
                $linkval.='&'.$keylnk.'='.$vallink;
            }
        }
        return $linkval;
    }
}
?>