$(function(){
	/*
	 * 栏目点击添加内容
	 */
	$('.C_tables').on('click',click_cont);
	function click_cont(){
		var o = $(this).attr("href"),
        m = $(this).data("index"),
        u = $(this).data("url"),
        l = '内容列表',
        k = true;
        if (o == undefined || $.trim(o).length == 0) {
            return false
        }
		$(".J_menuTab",window.parent.document).each(function() {
			if ($(this).data("id") == u) {
				if (!$(this).hasClass("active")) {
                    $(this).addClass("active").siblings(".J_menuTab").removeClass("active");
                    g(this);
                    $(".J_mainContent .J_iframe",window.parent.document).each(function() {
                        if ($(this).data("id") == u) {
                        	$('#iframe'+m,window.parent.document).attr('src',o);
                            $(this).show().siblings(".J_iframe").hide();
                            return false
                        }
                    })
                }
                k = false;
                return false
			}
			
		});
		if (k) {
            var p = '<a href="javascript:;" class="active J_menuTab" data-id="' + u + '">' + l + ' <i></i></a>';
            $(".J_menuTab",window.parent.document).removeClass("active");
            var n = '<iframe class="J_iframe" name="iframe' + m + '" id="iframe' + m + '" width="100%" height="100%" src="' + o + '" frameborder="0" data-id="' + u + '" seamless></iframe>';
            $(".J_mainContent",window.parent.document).find("iframe.J_iframe").hide().parents(".J_mainContent").append(n);
            $(".J_menuTabs .page-tabs-content",window.parent.document).append(p);
            g($(".J_menuTab.active",window.parent.document))
        }
		return false;
	}
	
	
	function f(l) {
        var k = 0;
        $(l,window.parent.document).each(function() {
            k += $(this).outerWidth(true)
        });
        return k
    }
    function g(n) {
        var o = f($(n).prevAll()),
        q = f($(n).nextAll());
        var l = f($(".content-tabs",window.parent.document).children().not(".J_menuTabs"))+2;
        var k = $(".content-tabs",window.parent.document).outerWidth(true) - l;
        var p = 0;
        if ($(".page-tabs-content",window.parent.document).outerWidth() < k) {
            p = 0
        } else {
            if (q <= (k - $(n).outerWidth(true) - $(n).next().outerWidth(true))) {
                if ((k - $(n).next().outerWidth(true)) > q) {
                    p = o;
                    var m = n;
                    while ((p - $(m).outerWidth()) > ($(".page-tabs-content",window.parent.document).outerWidth() - k)) {
                        p -= $(m).prev().outerWidth();
                        m = $(m).prev()
                    }
                }
            } else {
                if (o > (k - $(n).outerWidth(true) - $(n).prev().outerWidth(true))) {
                    p = o - $(n).prev().outerWidth(true)
                }
            }
        }
        if($(".page-tabs-content",window.parent.document).css("marginLeft")!=0 - p + "px")
		{
			$(".page-tabs-content",window.parent.document).animate({
				marginLeft: 0 - p + "px"
			},"fast")
		}
    }
	
	 /*$('#grdbox').click(function(e){
	 	alert($('#grdbox').parent('label').parent('div').children('input').length);
	 });*/

	
	
	
});

//外部链接显示与隐藏
function jumpweburl(){
	if($('#attrvald').is(':checked')) {
    	$('#weburldiv').show();
	}else{
		$('#weburldiv').hide();
	}

}

//单机删除图片
//删除图片集图片
function delpicim(id){
	if(confirm('您确定要删除该图片吗？')){
		$('#imaryys'+id).remove();
	}
}
//改变图片集标题
function gbtitle(id,urlval){
	$('#imgarycont'+id).val(urlval+'|'+$('#imgtitleary'+id).html());
}
//选择栏目加载不同的输入页面
function choicetype(name){
	//alert(1);
	var tyid=$('#'+name).val();
	var tyobject=$('#'+name).find("option:selected").attr("clfangfa");
	//alert(tyid+'=>'+tyobject);
	window.location.href=host+'/myadmin/pagecont/'+tyobject+'.html?action=add&tyid='+tyid;
}
//验证码刷新
function anniu(){
	
	var captcha_img = $('#captcha-container').find('img')
	var verifyimg = captcha_img.attr("src"); 
	captcha_img.attr('title', '点击刷新'); 
	
	if( verifyimg.indexOf('?')>0){  
        captcha_img.attr("src", verifyimg+'&random='+Math.random());  
    }else{  
		captcha_img.attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());  
    } 

}

















































