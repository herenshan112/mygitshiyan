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
//选项卡
function click_newintab(curval,sumval,name){
	if(curval==1){
		$('#newsurl').attr('href',host+'/index/news/index.html?tyid=11');
	}else{
		$('#newsurl').attr('href',host+'/index/news/index.html?tyid=12');
	}
	for(i=1;i<=sumval;i++){
		var menu=document.getElementById(name+i);
		var conmenu=document.getElementById('cont_'+name+'_'+i);
		if(i==curval){
			$('#'+name+i).addClass('active');
		}else{
			$('#'+name+i).removeClass('active');
		}
		conmenu.style.display=i==curval?"block":"none";
	}
}
//取消留言
function quxiao(){
	$('input[type="text"]').val('');
	$('textarea').val('');
	return false;
}
/*
 * 提交留言
 */
function submessage(id){
	var callname=$('#callname').val();
	var tel=$('#tel').val();
	var emaile=$('#emaile').val();
	var addres=$('#addres').val();
	var beizhu=$('#beizhu').val();
	if(callname == ''){
		alert('请输入姓名！');
		return false;
	}
	if(tel == ''){
		alert('请输入手机号！');
		return false;
	}
	if(emaile == ''){
		alert('请输入邮箱！');
		return false;
	}
	if(addres == ''){
		alert('请输入联系地址！');
		return false;
	}
	if(beizhu == ''){
		alert('请输入备注！');
		return false;
	}
	if(mysjh_jc(tel) == 0 && mygddh_jc(tel) == 0){
		alert('请输入正确的联系电话！');
		return false;
	}
	if(check_email(emaile) == 0){
		alert('请输入正确的邮箱地址！');
		return false;
	}
	$.ajax({
		type:"post",
		url:host+"/index/product/mesages.html",
		async:true,
		data:{id:id,callname:callname,tel:tel,emaile:emaile,addres:addres,beizhu:beizhu},
		dataType:'json',
		success:function(data){
			//console.log(data);
			alert(data.msg);
			if(data.code == 1){
				quxiao();
			}
		},
		error:function(){
			alert('网络链接错误！请刷新！');
		}
	});
}

//---------手机号合法性检查

function mysjh_jc(id_name){
	//var sjhid=/^(\d{3,4}\-?)?\d{7,8}$/;
	var sjhid=/^1[3|4|5|8][0-9]\d{4,8}$/;
	var sjhclje=sjhid.test(id_name);
	if(!sjhclje){
		return 0;
	}
	else{//合法用户名用ajax的checkid()检测是否被注册过
	
		return 1;
	}
}
//---------固定电话号合法性检查
function mygddh_jc(id_name){
	var gdid=/^((0(10|2[1-3]|[3-9]\d{2}))?[1-9]\d{6,7})$/;
	var gdclje=gdid.test(id_name);
	if(!gdclje){
		return 0;
	}else{//合法用户名用ajax的checkid()检测是否被注册过
		return 1;
	}
}

//----------邮箱检测
function check_email(email){
	var reEmail=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
		var b_email=reEmail.test(email);
		if(b_email){
			return 1;
		}
		else{
			return 0;
		}
}

//判断搜索关键词是否为空
function jumpserval(){
	if($('#sousuoval').val() != ''){
		return true;
	}
	alert('请您输入要查找的关键字！');
	return false;
}
