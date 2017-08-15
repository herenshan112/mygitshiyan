var zindex=1;
var winWidth = 0; 
var winHeight = 0; 
var hosthead='http://';
var tmpTag = 'https:' == document.location.protocol ? false : true;

if(tmpTag){
	hosthead='http://';
}else{
	hosthead='https://';	
}

if(window.location.host != null){
			var host = hosthead+window.location.host;
		}else{
			var host=hosthead+document.domain; 
}
function findDimensions() //函数：获取尺寸 
{ 
//获取窗口宽度 
if (window.innerWidth) 
winWidth = window.innerWidth; 
else if ((document.body) && (document.body.clientWidth)) 
winWidth = document.body.clientWidth; 
//获取窗口高度 
if (window.innerHeight) 
winHeight = window.innerHeight; 
else if ((document.body) && (document.body.clientHeight)) 
winHeight = document.body.clientHeight; 
//通过深入Document内部对body进行检测，获取窗口大小 
if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth) 
{ 
winHeight = document.documentElement.clientHeight; 
winWidth = document.documentElement.clientWidth; 
} 
//结果输出至两个文本框 
//alert(winHeight); 
//alert(winWidth);
//document.form1.availWidth.value= winWidth; 
} 
function addzindex(name){
	zindex++;
	$("#"+name).css("z-index",zindex);
}
//窗口浮动
function showf(namemc)

{    

   var divBig=document.getElementById("big"); 

   var divSmall=document.getElementById(namemc);

   var v_left=(document.body.clientWidth-divSmall.clientWidth)/2 + document.body.scrollLeft;

        var v_top=(document.body.clientHeight-divBig.clientHeight)/2 + document.body.scrollTop;

        divBig.style.top=document.body.scrollTop+'px';

   divSmall.style.left=(v_left)+'px';

        divSmall.style.top=(document.body.scrollTop+((document.body.clientHeight/2)+100))+'px';

   

   document.body.style.overflow="hidden";

}
var drag_ = false
var D = new Function('obj', 'return document.getElementById(obj);')
var oevent = new Function('e', 'if (!e) e = window.event;return e')
function Move_obj(obj) {
    var x, y;
    D(obj).onmousedown = function(e) {
        drag_ = true;
        with (this) {
            style.position = "absolute"; var temp1 = offsetLeft; var temp2 = offsetTop;
            x = oevent(e).clientX; y = oevent(e).clientY;
            document.onmousemove = function(e) {
                if (!drag_) return false;
                with (this) {
                    style.left = temp1 + oevent(e).clientX - x + "px";
                    style.top = temp2 + oevent(e).clientY - y + "px";
                }
            }
        }
        document.onmouseup = new Function("drag_=false");
    }
}

//隐藏层
function hidediv(name,fjname){
	$('#'+name).hide();
	$('#'+fjname).hide();
	$('#'+name+' input[type=text]').val('');
	$('#'+name+' textarea').val('');
	$('#'+name+' input[type=checkbox]').attr("checked",false);
	$('#'+name+' input[type=password]').val('');
	$('#'+name+' input[type=hidden]').val('');
	$('#echo_val').html('');
}

//显示
function admingrade(id,name,fjname){
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
		});
	});
}


//栏目列表
$(function(){
	$('.openclost').click(function(){
		if($(this).hasClass('icon-folder-close-alt')){
			$(this).removeClass('icon-folder-close-alt');
			$(this).addClass('icon-folder-open-alt');
			$(this).parent().siblings('ul').css('display','block');
		}else{
			$(this).removeClass('icon-folder-open-alt');
			$(this).addClass('icon-folder-close-alt');
			$(this).parent().siblings('ul').css('display','none');
		}
		
	});
});




/*
 * 登陆页面定位
 */
function loginadd(){
	findDimensions();
	document.getElementById('logindiv').style.marginTop=(winHeight-610)/2+'px';
}

/*
 * 刷新页面
 */
function webreload(){
	window.location.reload();
}
// 全选按钮选中标志  
var checkback = "true";  
    // 全选功能  
function BackselectAll(name){  
     //  alert(1);
	    var field = document.getElementsByName(name);  
        // 如果全选按钮状态是未选中  
        if (checkback == "false"){  
            for (i = 0; i < field.length; i++){  
                field[i].checked = true;  
				//alert(field[i].value);
            }  
            // 更改全选按钮选中标志  
            checkback = "true";  
        }else{  
            for (i = 0; i < field.length; i++){  
                field[i].checked = false;  
				//alert(i+"kl"); 
            }  
            // 更改全选按钮选中标志  
            checkback = "false";  
        }  
} 

//数据库操作显示
function databackca(id,name,fjname){
	$('#datanames').html('优化数据库');
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
			var backval="";
			var field=document.getElementsByName(id);
			for (i = 0; i < field.length; i++){  
				//alert(field[i].value);
				if(field[i].checked == true){
					if(field[i].value!=null && field[i].value!=""){
								//alert(paxi+"=>"+field[i].value);
						if(backval != ""){
							backval=backval+","+field[i].value;
						}else{
							backval=field[i].value;
						}
					} //判断是否是空值
				}	
			}  //获取所有选中的按钮
			if(backval != ""){
				var contentd=document.getElementById("echo_val");	
				contentd.innerHTML="<ul id='back_list'></ul>";
				var strs= new Array(); //定义一数组 
				strs=backval.split(","); //字符分割
				for(i=0;i<strs.length;i++){
					optbclass(strs[i])
				}
			}else{
				$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>你没有选择要优化的表！<br>请选择表！</i></span>');
			}
		});
	});
}

//优化表结构
function optbclass(backtanm){
	
		var content=document.getElementById("back_list");		

		$.ajax({
			url:host+'/myadmin/Databack/opttabel',
			type:'POST',
			data:{tabelname:backtanm},
			dataType:'json',
			beforeSend: function (XMLHttpRequest) {
				content.innerHTML=content.innerHTML+"<li id='"+backtanm+"li'><span>数据表<a>"+backtanm+"</a>优化中</span><b class='tubiao4'>&nbsp;</b></li>";	
			},
			success:function(data){
				//alert(data);
				var ment=document.getElementById(backtanm+"li");
				if(data.code == 1){
					ment.innerHTML="<span><a>"+backtanm+"</a>表"+data.msg+"</span><b class='tubiao1'>&nbsp;</b>";
				}else{
					ment.innerHTML="<span><a>"+backtanm+"</a>表"+data.msg+"</span><b class='tubiao2'>&nbsp;</b>";
				}
				
			},
			error:function(){
				content.innerHTML="<li id='"+backtanm+"li'><span>数据表<a>"+backtanm+"</a>优化中</span><b class='tubiao4'>&nbsp;</b></li>";	
				
			}
		});
}

//数据库操作显示
function dabacxiufu(id,name,fjname){
	$('#datanames').html('修复数据库');
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
			var backval="";
			var field=document.getElementsByName(id);
			for (i = 0; i < field.length; i++){  
				//alert(field[i].value);
				if(field[i].checked == true){
					if(field[i].value!=null && field[i].value!=""){
								//alert(paxi+"=>"+field[i].value);
						if(backval != ""){
							backval=backval+","+field[i].value;
						}else{
							backval=field[i].value;
						}
					} //判断是否是空值
				}	
			}  //获取所有选中的按钮
			if(backval != ""){
				var contentd=document.getElementById("echo_val");	
				contentd.innerHTML="<ul id='back_list'></ul>";
				var strs= new Array(); //定义一数组 
				strs=backval.split(","); //字符分割
				for(i=0;i<strs.length;i++){
					repclass(strs[i])
				}
			}else{
				$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>你没有选择要修复的表！<br>请选择表！</i></span>');
			}
		});
	});
}

//修复表结构
function repclass(backtanm){
	var content=document.getElementById("back_list");
	
	$.ajax({
			url:host+'/myadmin/Databack/repairtabel',
			type:'POST',
			data:{tabelname:backtanm},
			dataType:'json',
			beforeSend: function (XMLHttpRequest) {
				content.innerHTML=content.innerHTML+"<li id='"+backtanm+"li'><span>数据表<a>"+backtanm+"</a>修复中</span><b class='tubiao4'>&nbsp;</b></li>";	
			},
			success:function(data){
				//alert(data);
				var ment=document.getElementById(backtanm+"li");
				if(data.code == 1){
					ment.innerHTML="<span><a>"+backtanm+"</a>表"+data.msg+"</span><b class='tubiao1'>&nbsp;</b>";
				}else{
					ment.innerHTML="<span><a>"+backtanm+"</a>表"+data.msg+"</span><b class='tubiao2'>&nbsp;</b>";
				}
			},
			error:function(){
				content.innerHTML="<li id='"+backtanm+"li'><span>数据表<a>"+backtanm+"</a>修复中</span><b class='tubiao4'>&nbsp;</b></li>";	
				
			}
		});
}

//优化单表
function optbegin(tabelname){
	$('#datanames').html('优化数据表'+tabelname);
	$('#fukj').show(1,function(){
		$('#load_pf').css("margin-top",($('#fukj').height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#fukj').width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#deltuser').css("top",($('#fukj').height()-$('#deltuser').height())/2+"px");
		$('#deltuser').css("left",($('#fukj').width()-$('#deltuser').width())/2+"px");
		$('#deltuser').show(1,function(){
			$('#load_pf').hide();
			//alert(tabelname);
			var contentd=document.getElementById("echo_val");	
			contentd.innerHTML="<ul id='back_list'></ul>";
			optbclass(tabelname);
			
		});
	});
}
//修复单表
function repabegin(tabelname){
	$('#datanames').html('修复数据表'+tabelname);
	$('#fukj').show(1,function(){
		$('#load_pf').css("margin-top",($('#fukj').height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#fukj').width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#deltuser').css("top",($('#fukj').height()-$('#deltuser').height())/2+"px");
		$('#deltuser').css("left",($('#fukj').width()-$('#deltuser').width())/2+"px");
		$('#deltuser').show(1,function(){
			$('#load_pf').hide();
			//alert(tabelname);
			var contentd=document.getElementById("echo_val");	
			contentd.innerHTML="<ul id='back_list'></ul>";
			repclass(tabelname);
			
		});
	});
}
//单表结构
function strubegin(backtanm){
	$('#datanames').html(backtanm+'表结构');
	$('#fukj').show(1,function(){
		$('#load_pf').css("margin-top","50px");
		$('#load_pf').css("margin-left",($('#fukj').width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#deltuser').css("top","50px");
		$('#deltuser').css("left",($('#fukj').width()-$('#deltuser').width())/2+"px");
		$('#deltuser').show(1,function(){
			$('#load_pf').hide();
			//alert(tabelname);
			var contentd=document.getElementById("echo_val");	
			contentd.innerHTML="<ul id='back_list'></ul>";
			var content=document.getElementById("back_list");
			$.ajax({
				url:host+'/myadmin/Databack/tabelbast',
				type:'POST',
				data:{tabelname:backtanm},
				dataType:'json',
				beforeSend: function (XMLHttpRequest) {
					content.innerHTML=content.innerHTML+"<li id='"+backtanm+"li'><span>数据表<a>"+backtanm+"</a>优化中</span><b class='tubiao4'>&nbsp;</b></li>";	
				},
				success:function(data){
					//alert(data);
					content.innerHTML=data.msg;
					
				},
				error:function(){
					content.innerHTML="<li id='"+backtanm+"li'><span>数据表<a>"+backtanm+"</a>优化中</span><b class='tubiao4'>&nbsp;</b></li>";	
					
				}
			});
			
		});
	});
}

//开始备份
function beginback(id,name,fjname){
	$('#datanames').html('备份数据库');
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
			var backval="";
			var field=document.getElementsByName(id);
			for (i = 0; i < field.length; i++){  
				//alert(field[i].value);
				if(field[i].checked == true){
					if(field[i].value!=null && field[i].value!=""){
								//alert(paxi+"=>"+field[i].value);
						if(backval != ""){
							backval=backval+","+field[i].value;
						}else{
							backval=field[i].value;
						}
					} //判断是否是空值
				}	
			}  //获取所有选中的按钮
			if(backval != ""){
				backtaball(backval);
			}else{
				$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>你没有选择要备份的表！<br>请选择表！</i></span>');
			}
		});
	});
}
//备份数据库结构
function backtaball(tabelall){
	var contentd=document.getElementById("echo_val");	
	contentd.innerHTML="<ul id='back_list'></ul>";
	var content=document.getElementById("back_list");
	
	$.ajax({
		type:"POST",
		url:host+"/myadmin/Databack/backtabelall",
		data:{id:1},
		dataType:'json',
		beforeSend: function (XMLHttpRequest) {
			content.innerHTML=content.innerHTML+"<li id='alltabelli'><span>数据库表结构备份<a></a>准备中</span><b class='tubiao4'>&nbsp;</b></li>";	
		},
		success:function(data){
			var ment=document.getElementById("alltabelli");
			if(data.code == 1){
				ment.innerHTML="<span><a>数据库表结构备份</a>备份完成</span><b class='tubiao1'>&nbsp;</b>";
				var strs= new Array(); //定义一数组 
				strs=tabelall.split(","); //字符分割
				for(i=0;i<strs.length;i++){
					onetabelback(strs[i],data.filename);
				}
			}else{
				ment.innerHTML="<span><a>数据库表结构备份</a>备份失败</span><b class='tubiao2'>&nbsp;</b>";
			}
		},
		error:function(data_error){
			$('#back_list').html('<em class="icon-remove-circle icon-5x"></em><span>网络链接失败！<br>请检查你的网络！</i></span>');
		}
	});
}
/*
 * 单表备份
 */
function onetabelback(tabname,fileurl){
	var content=document.getElementById("back_list");
	$.ajax({
		type:"post",
		url:host+"/myadmin/Databack/onetabelback",
		data:{tabelname:tabname,fileurl:fileurl},
		dataType:'json',
		beforeSend:function(xmldata){
			content.innerHTML=content.innerHTML+"<li id='"+tabname+"li'><span>数据表<a>"+tabname+"</a>备份中</span><b class='tubiao4'>&nbsp;</b></li>";
		},
		success:function(data){
			var ment=document.getElementById(tabname+"li");
			if(data.code==1){
				ment.innerHTML="<span>数据表<a>"+tabname+"</a>"+data.msg+"</span><b class='tubiao1'>&nbsp;</b>";
			}else{
				ment.innerHTML="<span>数据表<a>"+tabname+"</a>"+data.msg+"</span><b class='tubiao2'>&nbsp;</b>";
			}
				
		},
		error:function(data_error){
			content.innerHTML=content.innerHTML+"<li id='"+tabname+"li'><span>数据表<a>"+tabname+"</a>获取失败！</span><b class='tubiao2'>&nbsp;</b></li>";
		}
	});
}
/*
 *还原数据库
 */
function huanback(id,name,fjname){
	$('#datanames').html('还原数据库');
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
			var backval="";
			var field=document.getElementsByName(id);
			for (i = 0; i < field.length; i++){  
				//alert(field[i].value);
				if(field[i].checked == true){
					if(field[i].value!=null && field[i].value!=""){
								//alert(paxi+"=>"+field[i].value);
						if(backval != ""){
							backval=backval+","+field[i].value;
						}else{
							backval=field[i].value;
						}
					} //判断是否是空值
				}	
			}  //获取所有选中的按钮
			if(backval != ""){
				beginhuanyuan(backval,$('#fileurl').val());
			}else{
				$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>你没有选择要还原的表文件！<br>请选择表文件！</i></span>');
			}
		});
	});
}

//开始备份
function beginbackes(id,name,fjname){
	$('#datanames').html('备份数据库');
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
			var backval="";
			var field=document.getElementsByName(id);
			for (i = 0; i < field.length; i++){  
				//alert(field[i].value);
				if(field[i].checked == true){
					if(field[i].value!=null && field[i].value!=""){
								//alert(paxi+"=>"+field[i].value);
						if(backval != ""){
							backval=backval+","+field[i].value;
						}else{
							backval=field[i].value;
						}
					} //判断是否是空值
				}	
			}  //获取所有选中的按钮
			if(backval != ""){
				xunhuanback(backval);
			}else{
				$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>你没有选择要备份的表！<br>请选择表！</i></span>');
			}
		});
	});
}
/*
 * 生成备份目录
 */
function xunhuanback(tabelall){
	var contentd=document.getElementById("echo_val");	
	contentd.innerHTML="<ul id='back_list'></ul>";
	var content=document.getElementById("back_list");
	
	$.ajax({
		type:"POST",
		url:host+"/myadmin/Databack/backfileurl",
		data:{id:1},
		dataType:'json',
		beforeSend: function (XMLHttpRequest) {
			content.innerHTML=content.innerHTML+"<li id='alltabelli'><span>数据库备份目录<a></a>创建中</span><b class='tubiao4'>&nbsp;</b></li>";	
		},
		success:function(data){
			var ment=document.getElementById("alltabelli");
			if(data.code == 1){
				ment.innerHTML="<span><a>数据库备份目录</a>"+data.msg+"</span><b class='tubiao1'>&nbsp;</b>";
				var strs= new Array(); //定义一数组 
				strs=tabelall.split(","); //字符分割
				for(i=0;i<strs.length;i++){
					onetabwrefb(strs[i],data.filename);
				}
			}else{
				ment.innerHTML="<span><a>数据库备份</a>"+data.msg+"</span><b class='tubiao2'>&nbsp;</b>";
			}
		},
		error:function(data_error){
			$('#back_list').html('<em class="icon-remove-circle icon-5x"></em><span>网络链接失败！<br>请检查你的网络！</i></span>');
		}
	});
}
/*
 * 单表写入副本
 */
function onetabwrefb(tabname,fileurl){
	var content=document.getElementById("back_list");
	$.ajax({
		type:"post",
		url:host+"/myadmin/Databack/onelbackfb",
		data:{tabelname:tabname,fileurl:fileurl},
		dataType:'json',
		beforeSend:function(xmldata){
			content.innerHTML=content.innerHTML+"<li id='"+tabname+"li'><span>数据表<a>"+tabname+"</a>备份中</span><b class='tubiao4'>&nbsp;</b></li>";
		},
		success:function(data){
			var ment=document.getElementById(tabname+"li");
			if(data.code==1){
				ment.innerHTML="<span>数据表<a>"+tabname+"</a>"+data.msg+"</span><b class='tubiao1'>&nbsp;</b>";
			}else{
				ment.innerHTML="<span>数据表<a>"+tabname+"</a>"+data.msg+"</span><b class='tubiao2'>&nbsp;</b>";
			}
				
		},
		error:function(data_error){
			//alert(data_error);
			var ment=document.getElementById(tabname+"li");
			ment.innerHTML="<span>数据表<a>"+tabname+"</a>获取失败！</span><b class='tubiao2'>&nbsp;</b>";
		}
	});
}
/*
 * 开始还原
 */
function beginhuanyuan(tabname,mulu){
	var contentd=document.getElementById("echo_val");	
	contentd.innerHTML="<ul id='back_list'></ul>";
	var strs= new Array(); //定义一数组 
	strs=tabname.split(","); //字符分割
	for(i=0;i<strs.length;i++){
		onehuayuantb(strs[i],mulu);
	}
}
/*
 * 单表还原
 */
function onehuayuantb(tbnam,tburl){
	//alert(tbnam+'<=>'+tburl);
	var content=document.getElementById("back_list");
	$.ajax({
		url:host+'/myadmin/Databack/onetbhuanya',
		type:'POST',
		data:{tbnam:tbnam,tburl:tburl},
		dataType:'json',
		beforeSend:function(xmldata){
			content.innerHTML=content.innerHTML+"<li id='"+tbnam+"li'><span>数据表<a>"+tbnam+"</a>还原中</span><b class='tubiao4'>&nbsp;</b></li>";
		},
		success:function(data){
			var ment=document.getElementById(tbnam+"li");
			if(data.code == 1){
				ment.innerHTML="<span>数据表<a>"+tbnam+"</a>还原完成！</span><b class='tubiao1'>&nbsp;</b>";
			}else{
				ment.innerHTML="<span>数据表<a>"+tbnam+"</a>还原失败！</span><b class='tubiao2'>&nbsp;</b>";
			}
		},
		error:function(errordata){
			var ment=document.getElementById(tbnam+"li");
			ment.innerHTML="<span>数据表<a>"+tbnam+"</a>文件获取失败！</span><b class='tubiao2'>&nbsp;</b>";
		}
	});
}

function lookimg(name){
	
	$('#'+name).show();
}
function yincangimg(name){
	$('#'+name).hide();
}
//删除隐藏层
function deltehidediv(name,fjname){
	$('#'+name).hide();
	$('#'+fjname).hide();
	$('#'+name+' input[type=text]').val('');
	$('#'+name+' textarea').val('');
	$('#'+name+' input[type=checkbox]').attr("checked",false);
	$('#'+name+' input[type=password]').val('');
	$('#'+name+' input[type=hidden]').val('');
	$('#echo_val').html('');
	//webreload();
}
function xxtsshuaxin(name,fjname){
	$('#'+name).hide();
	$('#'+fjname).hide();
	$('#'+name+' input[type=text]').val('');
	$('#'+name+' textarea').val('');
	$('#'+name+' input[type=checkbox]').attr("checked",false);
	$('#'+name+' input[type=password]').val('');
	$('#'+name+' input[type=hidden]').val('');
	$('#echo_val').html('');
	webreload();
}
//删除通用矿建
/*
 * 删除通用矿建ajax
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * appurl				数据回调地址
 */
function deltall(id,name,fjname,appurl){
	$('#'+fjname).show(1,function(){
		$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>你是否真的要执行删除操作？<br>资料一经删除，将不可恢复！<br><i>请确认无误！请慎重操作!</i></span>');
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$('#delteid').val(id);
		$('#delteurl').val(appurl);
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
		});
	});
}
//执行删除操作
function delteall(){
	var id=$('#delteid').val();
	var zxurl=$('#delteurl').val();
	if(id != '' && zxurl != ''){
		$.ajax({
			type:'post',
			url:host+'/'+zxurl,
			data:{id:id},
			dataType:'json',
			beforeSend:function(){
				$('#echo_val').html('<em class="icon-spinner icon-spin icon-large icon-5x"></em><span>程序处理中！请等待！</span>');
			},
			success:function(data){
				if(data.code == 1){
					deltehidediv('deltcont','fukj');
					webreload()
				}else{
					$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>'+data.msg+'</span>');
				}
			},
			error:function(){
				$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>网络链接错误！请刷新操作</span>');
			}
		});
	}else{
		alert('参数错误！不能执行删除操作！');
		deltehidediv('deltcont','fukj');
	}
}
//执行还原操作
function huanyuanall(){
	
	var id=$('#tishiid').val();
	var zxurl=$('#tishiurl').val();
	
	if(id != '' && zxurl != ''){
		
		$.ajax({
			type:'post',
			url:host+'/'+zxurl,
			data:{id:id},
			dataType:'json',
			beforeSend:function(){
				$('#echo_val').html('<em class="icon-spinner icon-spin icon-large icon-5x"></em><span>程序处理中！请等待！</span>');
			},
			success:function(data){
				
				if(data.code == 1){
					deltehidediv('deltcont','fukj');
					webreload()
				}else{
					$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>'+data.msg+'</span>');
				}
			},
			error:function(){
				$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>网络链接错误！请刷新操作</span>');
			}
		});
	}else{
		alert('参数错误！不能执行还原操作！');
		deltehidediv('deltcont','fukj');
	}
}
/*
 * 提示显示ajax
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * appurl				数据回调地址
 * contval				提示语句
 */
function tishiall(id,name,fjname,appurl,contval){
	$('#'+fjname).show(1,function(){
		$('#echo_val').html('<em class="icon-remove-circle icon-5x"></em><span>你是否真的要执行删除操作？<br>资料一经删除，将不可恢复！<br><i>请确认无误！请慎重操作!</i></span>');
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$('#tishiid').val(id);
		$('#tishiurl').val(appurl);
		$('#xxtscont').html(contval);
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
		});
	});
}
/*
 * 提示显示ajax没操作
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * contval				提示语句
 */
function tishiallmcz(id,name,fjname,contval){
	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$('#xxtsmczcont').html(contval);
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
		});
	});
}
/*
 * 修改ajax
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * lokurl				数据回调地址
 * clurl				数据处理地址
 */
function eiteajax(id,name,fjname,lokurl,clurl){
	
	$('#eiteid').val(id);
	$('#eiteurl').val(clurl);
	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$.ajax({
			type:"post",
			url:host+"/"+lokurl,
			async:true,
			data:{id:id},
			dataType:'json',
			success:function(data){
				if(data.code == 1){
					var info=data.info;
					$('#etajname').val(info.grd_title);
					$('#etajoldname').val(info.grd_title);
					$('#ejatcont').val(info.grd_cont);
					if(info.grd_setval != 0){
						$('#ejatztclz').html('<span>状态：</span><input type="radio" name="ejatsetval" id="ejatsetval" value="1" checked><em>开启</em><input type="radio" name="ejatsetval" id="ejatsetval" value="0" ><em>关闭</em>');
					}else{
						$('#ejatztclz').html('<span>状态：</span><input type="radio" name="ejatsetval" id="ejatsetval" value="1"><em>开启</em><input type="radio" name="ejatsetval" id="ejatsetval" value="0" checked><em>关闭</em>');
					}
					$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
					$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
					$('#'+name).show(1,function(){
						$('#load_pf').hide();
					});
				}else{
					tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
				}
			},
			error:function(){
				tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
			}
		});
		
	});
}
/*
 * 修改管理级别信息
 */
function eiteajaxgadcon(){
	var etajname=$('#etajname').val();
	var etajoldname=$('#etajoldname').val();
	var ejatcont=$('#ejatcont').val();
	var ejatsetval=$('input[name="ejatsetval"]:checked').val();
	
	var id=$('#eiteid').val();
	var clurl=$('#eiteurl').val();
	if(etajname == ''){
		alert('请输入权限名称');
		return false;
	}
	if(id == '' || clurl== ''){
		alert('参数错误！');
		return false;
	}
	$.ajax({
		type:"post",
		url:host+"/"+clurl,
		async:true,
		data:{id:id,gradname:etajname,gradcont:ejatcont,gradset:ejatsetval,gradnameold:etajoldname},
		dataType:'json',
		success:function(data){
			deltehidediv('eitegrd','fukj')
			tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
			
		},
		error:function(){
			tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
		}
	});
}
/*
 * 修改ajax
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * lokurl				数据回调地址
 * clurl				数据处理地址
 */
function peizhiajax(id,name,fjname,lokurl,clurl){
	$('#peizhiid').val(id);
	$('#peizhiurl').val(clurl);
	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$.ajax({
			type:"post",
			url:host+"/"+lokurl,
			async:true,
			data:{id:id},
			dataType:'json',
			success:function(data){
				if(data.code == 1){
					var info=data.info;
					//console.log(info);
					$('#grdlists').html(data.grdbut);
					
					$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
					$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
					$('#'+name).show(1,function(){
						$('#load_pf').hide();
					});
				}else{
					tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
				}
			},
			error:function(){
				tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
			}
		});
		
	});
}
/*
 * 修改管理权限配置
 */
function eitegrdpeizi(){
	var pzid=$('#peizhiid').val();
	var pzurl=$('#peizhiurl').val();
	var str = ""; 
	$("input[name='setquanxin[]']:checkbox").each(function() { 
		if($(this).is(":checked")){ 
			if(str != ''){
				str +="|"+$(this).attr("value"); 
			}else{
				str = $(this).attr("value"); 
			}
			
		} 
	});
	$.ajax({
		type:"post",
		url:host+"/"+pzurl,
		async:true,
		data:{id:pzid,grdval:str},
		dataType:'json',
		success:function(data){
			deltehidediv('setgrd','fukj')
			tishiallmcz(pzid,'xxtsmycz','fukj','<br>'+data.msg);
		},
		error:function(){
			tishiallmcz(pzid,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
		}
	});
}

/*
 * 查看权限
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * lokurl				数据回调地址
 */
function lookgrade(id,name,fjname,lokurl){

	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$.ajax({
			type:"post",
			url:host+"/"+lokurl,
			async:true,
			data:{id:id},
			dataType:'json',
			success:function(data){
				if(data.code == 1){
					var info=data.info;
					
					$('#lokwebname').val(info.grd_title);
					$('#lokcont').val(info.grd_cont);
					if(info.grd_setval != 0){
						$('#loksetval').html('<span>状态：</span><input type="radio" name="ejatsetval" id="ejatsetval" value="1" checked><em>开启</em><input type="radio" name="ejatsetval" id="ejatsetval" value="0" ><em>关闭</em>');
					}else{
						$('#loksetval').html('<span>状态：</span><input type="radio" name="ejatsetval" id="ejatsetval" value="1"><em>开启</em><input type="radio" name="ejatsetval" id="ejatsetval" value="0" checked><em>关闭</em>');
					}
					
					
					$('#lookgrdlist').html(data.grdbut);
					
					$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
					$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
					$('#'+name).show(1,function(){
						$('#load_pf').hide();
					});
				}else{
					tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
				}
			},
			error:function(){
				tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
			}
		});
		
	});
}
/*
 * 修改密码
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * lokurl				数据回调地址
 */
function eiteajaxadm(id,name,fjname,clurl){
	
	$('#eitpwdid').val(id);
	$('#eitpwdurl').val(clurl);
	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		
		$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
		$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
		$('#'+name).show(1,function(){
			$('#load_pf').hide();
		});
	});
}
/*
 * 修改密码
 */
function eitepwdcl(){
	var id=$('#eitpwdid').val();
	var eiteurl=$('#eitpwdurl').val();
	
	var eitnewpwd=$('#eitnewpwd').val();
	var eitoldpwd=$('#eitoldpwd').val();
	if(eitnewpwd == ''){
		alert('请输入新密码！');
		return false;
	}
	if(eitnewpwd != eitoldpwd){
		alert('您两次输入的密码不一致！');
		return false;
	}
	$.ajax({
		type:"post",
		url:host+"/"+eiteurl,
		async:true,
		data:{id:id,eitnewpwd:eitnewpwd,eitoldpwd:eitoldpwd},
		dataType:'json',
		success:function(data){
			deltehidediv('eitepwd','fukj')
			tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
		},
		error:function(){
			tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
		}
	});
}

/*
 * 回调会员ajax
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * lokurl				数据回调地址
 * clurl				数据处理地址
 */
function eiteadminajx(id,name,fjname,lokurl,clurl){
	
	$('#eiteadmconid').val(id);
	$('#eiteadmconurl').val(clurl);
	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$.ajax({
			type:"post",
			url:host+"/"+lokurl,
			async:true,
			data:{id:id},
			dataType:'json',
			success:function(data){
				console.log(data);
				if(data.code == 1){
					var info=data.info;
					var grdlist=data.grdlist;
					$('#eitadcongrd').empty();
					$('#eitadcongrd').append('<option value="0">※※※请选择管理权限※※※</option>');
					for(i=0;i<grdlist.length;i++){
						if(info.ad_grod == grdlist[i]['grd_id']){
							$('#eitadcongrd').append('<option value="'+grdlist[i]['grd_id']+'" selected>'+grdlist[i]['grd_title']+'</option>');
						}else{
							$('#eitadcongrd').append('<option value="'+grdlist[i]['grd_id']+'">'+grdlist[i]['grd_title']+'</option>');
						}
					}
					
					if(info.ad_setval != 0){
						$('#eiteztcl').html('<span>状态：</span><input type="radio" name="eiteusset" id="eiteusset" value="1" checked><em>开启</em><input type="radio" name="eiteusset" id="eiteusset" value="0" ><em>关闭</em>');
					}else{
						$('#eiteztcl').html('<span>状态：</span><input type="radio" name="eiteusset" id="eiteusset" value="1"><em>开启</em><input type="radio" name="eiteusset" id="eiteusset" value="0" checked><em>关闭</em>');
					}
					
					$('#eitecallname').val(info.ada_name);
					$('#eitecalltel').val(info.ada_tel);
					$('#eiteqqnum').val(info.ada_qq);
					$('#eiteemail').val(info.ada_email);
					$('#eiteaddress').val(info.ada_address);
					$('#eiteconts').val(info.ada_cont);
					$('#smlpicval').val(info.ada_imgpic);
					
					if(info.ada_imgpic != '' && info.ada_imgpic != null){
						$('#smlpiclook').attr('src','/upload/vip/'+info.ada_imgpic);
					}
					
					$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
					$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
					$('#'+name).show(1,function(){
						$('#load_pf').hide();
					});
				}else{
					tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
				}
			},
			error:function(){
				tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
			}
		});
		
	});
}
/*
 * 执行管理员资料修改操作
 */
function eiteadclzx(){
	var id=$('#eiteadmconid').val();
	var eiteurl=$('#eiteadmconurl').val();
	
	var gradval=$('#eitadcongrd').val();
	
	var callname=$('#eitecallname').val();
	var tel=$('#eitecalltel').val();
	var qqnum=$('#eiteqqnum').val();
	var email=$('#eiteemail').val();
	var address=$('#eiteaddress').val();
	var touxiang=$('#smlpicval').val();
	var conttent=$('#eiteconts').val();
	
	var adsetval=$('input[name="eiteusset"]:checked').val();
	
	if(gradval == 0){
		alert('请选择管理级别！');
		return false;
	}
	$.ajax({
		type:"post",
		url:host+"/"+eiteurl,
		async:true,
		data:{id:id,gradval:gradval,adsetval:adsetval,callname:callname,tel:tel,qqnum:qqnum,email:email,address:address,touxiang:touxiang,conttent:conttent},
		dataType:'json',
		success:function(data){
			deltehidediv('eitecont','fukj')
			tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
		},
		error:function(){
			tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
		}
	});
}
/*
 * 查看管理员信息
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * lokurl				数据回调地址
 */
function lookadmcont(id,name,fjname,lokurl){

	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$.ajax({
			type:"post",
			url:host+"/"+lokurl,
			async:true,
			data:{id:id},
			dataType:'json',
			success:function(data){
				if(data.code == 1){
					var info=data.info;
					$('#sky1').html(info.ad_username);
					$('#sky2').html(info.grd_title);
					$('#sky3').html(info.ada_name);
					$('#sky4').html(info.ada_tel);
					$('#sky5').html(info.ada_qq);
					$('#sky6').html(info.ada_email);
					$('#sky7').html(info.ada_address);
					$('#sky8').html(info.ada_cont);
					if(info.ad_setval != 0){
						$('#sky9').html('启用');
					}else{
						$('#sky9').html('<font color="#ff0000">禁用</font>');
					}
					$('#sky10').html(info.ad_lasttime);
					$('#sky11').html(info.ad_lastip);
					if(info.ada_imgpic != ''){
						$('#touxicl').attr('src','/upload/vip/'+info.ada_imgpic);
					}
					/*$('#lokwebname').val(info.grd_title);
					$('#lokcont').val(info.grd_cont);
					if(info.grd_setval != 0){
						$('#loksetval').html('<span>状态：</span><input type="radio" name="ejatsetval" id="ejatsetval" value="1" checked><em>开启</em><input type="radio" name="ejatsetval" id="ejatsetval" value="0" ><em>关闭</em>');
					}else{
						$('#loksetval').html('<span>状态：</span><input type="radio" name="ejatsetval" id="ejatsetval" value="1"><em>开启</em><input type="radio" name="ejatsetval" id="ejatsetval" value="0" checked><em>关闭</em>');
					}
					
					
					$('#lookgrdlist').html(data.grdbut);*/
					
					$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
					$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
					$('#'+name).show(1,function(){
						$('#load_pf').hide();
					});
				}else{
					tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
				}
			},
			error:function(){
				tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
			}
		});
		
	});
}
//登陆页面
function loginload(){
	findDiions();
	var tongkj1=document.getElementById("loginwkj");
	tongkj1.style.marginTop=(winHeight-610)/2+"px";
	tongkj1.style.marginLeft=(winWidth-763)/2+"px";
}

function findDiions() //函数：获取尺寸 
{ 
//获取窗口宽度 
if (window.innerWidth) 
winWidth = window.innerWidth; 
else if ((document.body) && (document.body.clientWidth)) 
winWidth = document.body.clientWidth; 
//获取窗口高度 
if (window.innerHeight) 
winHeight = window.innerHeight; 
else if ((document.body) && (document.body.clientHeight)) 
winHeight = document.body.clientHeight; 
//通过深入Document内部对body进行检测，获取窗口大小 
if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth) 
{ 
winHeight = document.documentElement.clientHeight; 
winWidth = document.documentElement.clientWidth; 
} 

var backcont=document.getElementById("backcont");
	backcont.style.height=winHeight+"px";
//结果输出至两个文本框 
//alert(winHeight); 
//alert(winWidth);
//document.form1.availWidth.value= winWidth; 
}

var logset=[0,0,0];
/*
 * 判断验证码是否正确
 */
function yxzmc(){
	var chose=$('#chose').val();
	$.ajax({
		type:"post",
		url:host+"/myadmin/login/jumpcode.html",
		async:true,
		data:{chose:chose},
		dataType:'json',
		beforeSend:function(){
			$('#loginsky1').html('Loading...');
		},
		success:function(data){
			$('#loginsky1').html(data.msg);
			if(data.code == 1){
				logset[0]=1;
				jumbutlog();
			}else{
				logset[0]=0;
				jumbutlog();
			}
		},
		error:function(){
			$('#loginsky1').html('网络链接错误！');
		}
		
	});
}
function jmpusval(){
	if($('#usename').val() != ''){
		logset[1]=1;
		jumbutlog();
	}else{
		logset[1]=0;
		jumbutlog();
	}
}
function jumpwdlog(){
	if($('#pwdadmin').val() != ''){
		logset[2]=1;
		jumbutlog();
	}else{
		logset[2]=0;
		jumbutlog();
	}
}
function jumbutlog(){
	if(logset[0]==1 && logset[1]==1 && logset[2]==1){
		$('#loginbuttion').html("<span id='logclick' class='hover' onclick='logincl()'>&nbsp;</span>");
	}else{
		$('#loginbuttion').html("<span id='logclick'>&nbsp;</span>");
	}
}
/*
 * 登陆处理
 */
function logincl(){
	var usename=$('#usename').val();
	var pwdadmin=$('#pwdadmin').val();
	var chose=$('#chose').val();
	if(usename == '请输入用户名'){
		$('#loginsky1').html("请输入用户名");
		$('#usename').focus();
		return false;
	}
	if(pwdadmin == '请输入密码'){
		$('#loginsky1').html("请输入密码");
		$('#pwdadmin').focus();
		return false;
	}
	if(logset[0]==0){
		$('#loginsky1').html("验证码不正确");
		$('#chose').focus();
		return false;
	}
	$.ajax({
		type:"post",
		url:host+"/myadmin/login/logincl.html",
		async:true,
		data:{usename:usename,pwdadmin:pwdadmin,chose:chose},
		dataType:'json',
		beforeSend:function(){
			$('#loginsky1').html('Loading...');
		},
		success:function(data){
			$('#loginsky1').html(data.msg);
			if(data.code == 1){
				location.href=host+'/myadmin';
			}else{
				anniu();
				//yxzmc();
			}
		},
		error:function(){
			$('#loginsky1').html("网络链接错误！");
		}
	});
}



/*
 * 回调会员ajax
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * lokurl				数据回调地址
 * clurl				数据处理地址
 */
function hfnescont(id,name,fjname,lokurl,clurl){
	
	$('#meshfid').val(id);
	$('#meshfurl').val(clurl);
	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$.ajax({
			type:"post",
			url:host+"/"+lokurl,
			async:true,
			data:{id:id},
			dataType:'json',
			success:function(data){
				//console.log(data);
				if(data.code == 1){
					var info=data.info;
					
					$('#prd_title').html(info.cn_title);
					$('#prd_name').html(info.mes_name);
					$('#prd_tel').html(info.mes_tel);
					$('#prd_email').html(info.mes_email);
					$('#prd_addres').html(info.mes_address);
					$('#prd_cont').html(info.mes_cont);
					
					$('#hfnrcontss').val(info.mes_hfcont);
					
					if(info.mes_setval != 0){
						$('#eiteztcl').html('<span>状态：</span><input type="radio" name="eiteusset" id="eiteusset" value="1" checked><em>开启</em><input type="radio" name="eiteusset" id="eiteusset" value="0" ><em>关闭</em>');
					}else{
						$('#eiteztcl').html('<span>状态：</span><input type="radio" name="eiteusset" id="eiteusset" value="1"><em>开启</em><input type="radio" name="eiteusset" id="eiteusset" value="0" checked><em>关闭</em>');
					}
					
					$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
					$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
					$('#'+name).show(1,function(){
						$('#load_pf').hide();
					});
				}else{
					tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
				}
			},
			error:function(){
				tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
			}
		});
		
	});
}

/*
 * 回调会员ajax
 * id 					信息id
 * name					提示框id
 * fjname				提示框外框架
 * lokurl				数据回调地址
 * clurl				数据处理地址
 */
function lookmes(id,name,fjname,lokurl,clurl){
	
	$('#'+fjname).show(1,function(){
		$('#load_pf').css("margin-top",($('#'+fjname).height()-$('#load_pf').height())/2+"px");
		$('#load_pf').css("margin-left",($('#'+fjname).width()-$('#load_pf').width())/2+"px");
		$('#load_pf').show();
		$.ajax({
			type:"post",
			url:host+"/"+lokurl,
			async:true,
			data:{id:id},
			dataType:'json',
			success:function(data){
				//console.log(data);
				if(data.code == 1){
					var info=data.info;
					
					$('#prd_titles').html(info.cn_title);
					$('#prd_names').html(info.mes_name);
					$('#prd_tels').html(info.mes_tel);
					$('#prd_emails').html(info.mes_email);
					$('#prd_address').html(info.mes_address);
					$('#prd_conts').html(info.mes_cont);
					$('#prd_contshf').html(info.mes_hfcont);
					
					if(info.mes_setval != 0){
						$('#eiteztcls').html('<span>状态：</span><em>开启</em>');
					}else{
						$('#eiteztcls').html('<span>状态：</span><em>关闭</em>');
					}
					
					$('#'+name).css("top",($('#'+fjname).height()-$('#'+name).height())/2+"px");
					$('#'+name).css("left",($('#'+fjname).width()-$('#'+name).width())/2+"px");
					$('#'+name).show(1,function(){
						$('#load_pf').hide();
					});
				}else{
					tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
				}
			},
			error:function(){
				tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
			}
		});
		
	});
}

/*
 * 回复留言
 */
function huifumescl(){
	var id=$('#meshfid').val();
	var eiteurl=$('#meshfurl').val();
	
	var hfnrcontss=$('#hfnrcontss').val();
	var adsetval=$('input[name="eiteusset"]:checked').val();
	if(hfnrcontss == ''){
		alert('请输入回复内容！');
		return false;
	}
	
	$.ajax({
		type:"post",
		url:host+"/"+eiteurl,
		async:true,
		data:{id:id,hfnrcontss:hfnrcontss,adsetval:adsetval},
		dataType:'json',
		success:function(data){
			deltehidediv('lookmsg','fukj')
			tishiallmcz(id,'xxtsmycz','fukj','<br>'+data.msg);
		},
		error:function(){
			tishiallmcz(id,'xxtsmycz','fukj','<br>网络链接错误！请检查！');
		}
	});
}




















