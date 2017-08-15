/*
 * 这不是一个自由软件，使用请注明出处。
 * ajax上传js部分
 */
$(document).ready(function(){
	/*
	 * 上传文件文档
	 */
	//响应文件添加成功事件(上传文档)
	$("#filetypecnt").change(function(){
		var fileclas=document.getElementById("filetypecntval");
		var data = new FormData();
		data.append('typeid',fileclas.value);
		$.each($('#filetypecnt')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		
		//alert(host+'/qdadmin/index/Upload/typeid/'+fileclas.value);
		//发送数据
		$.ajax({
			url:host+'/myadmin/upload/uploades',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				var strs= new Array(); //定义一数组 
				strs=data.split("|"); //字符分割 
				
				switch(strs[1]){
					case "0":
						$('#filevalue').val(strs[0]);
						$('#upmsg_error_fh').html('<b class="tubiao2">&nbsp;</b>上传失败');
						break;
					case "1":
						$('#filevalue').val(strs[0]);
						$('#upmsg_error_fh').html('<b class="tubiao2">&nbsp;</b>你上传的文件超过允许上传的最大值');
						break;
					case "2":
						$('#filevalue').val(strs[0]);
						$('#upmsg_error_fh').html('<b class="tubiao2">&nbsp;</b>你上传的文件类型不正确');
						break;
					case "3":
						$('#filevalue').val(strs[0]);
						$('#upmsg_error_fh').html('<b class="tubiao1">&nbsp;</b>');
						$('#loadimgpage').attr("src",strs[0]);
						
						break;
					case "4":

						$('#filevalue').val(strs[0]);
						$('#upmsg_error_fh').html('<b class="tubiao2">&nbsp;</b>文件上传失败');
						break;
					default:
						$('#filevalue').val(strs[0]);
						$('#upmsg_error_fh').html('<b class="tubiao2">&nbsp;</b>文件上传失败');
				}

			},
			error:function(){
				alert('上传出错');
			}
		});
	});
	
	
	/*
	 * 上传文件文档
	 */
	//响应文件添加成功事件(上传文档)
	$("#smlpicbut").change(function(){
		var fileclas=document.getElementById("smlpictype");
		var data = new FormData();
		data.append('typeid',fileclas.value);
		$.each($('#smlpicbut')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		
		//alert(host+'/qdadmin/index/Upload/typeid/'+fileclas.value);
		//发送数据
		$.ajax({
			url:host+'/myadmin/upload/uploades',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				var strs= new Array(); //定义一数组 
				strs=data.split("|"); //字符分割 
				
				switch(strs[1]){
					case "0":
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>上传失败');
						break;
					case "1":
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>你上传的文件超过允许上传的最大值');
						break;
					case "2":
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>你上传的文件类型不正确');
						break;
					case "3":
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao1">&nbsp;</b>');
						$('#smlpiclook').attr("src",'/upload/images/'+strs[0]);
						
						break;
					case "4":

						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>文件上传失败');
						break;
					default:
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>文件上传失败');
				}

			},
			error:function(){
				alert('上传出错');
			}
		});
	});
	
	
	/*
	 * 批量上传图片
	 */
	$("#imgaryupload").change(function(){
		var data = new FormData();
		$.each($('#imgaryupload')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		var fileclas=$('#imgarytype').val();
		data.append('typeid',fileclas);
		$.ajax({

			url:host+'/myadmin/upload/uploades.html',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				//alert(data);
				
				var strval=new Array();
				strval=data.split(",");
				
				
				var strs= new Array();
				var title=$('#title').val();
				if(title == ''){
					title='标题';
				}
				for(i=0;i<strval.length-1;i++){
					strs=strval[i].split("|");
					if($('#imgaryurl').val() != ''){
						$('#imgaryurl').val($('#imgaryurl').val()+","+strs[0]);
					}else{
						$('#imgaryurl').val(strs[0]);
					}
					//$('#imgaryls').html($('#imgaryls').html()+'<a><img  onClick=delpicim("'+strs[0]+'") alt="单机删除" src="'+host+'/upload/images/'+strs[0]+'"><em onClick=delpicim("'+strs[0]+'")>删除</em></a>');
					$('#imgaryls').html($('#imgaryls').html()+'<em id="imaryys'+i+'"><img onClick=delpicim('+i+') alt="单机删除" src="'+host+'/upload/images/'+strs[0]+'"><i contenteditable="true" onblur=gbtitle('+i+',"'+strs[0]+'") id="imgtitleary'+i+'">'+title+'</i><input type="hidden" name="imgarycont[]" id="imgarycont'+i+'" value="'+strs[0]+'|'+title+'" /></em>');
				}
				

			},
			error:function(){
				//$('#anidoemsg').html('<b class="tubiao4">&nbsp;</b>');
			}
		});
	
	});
	
	
	/*
	 * 上传头像
	 */
	//响应文件添加成功事件(上传文档)
	$("#upimgtoux").change(function(){
		var fileclas=document.getElementById("upimgtouxty");
		var data = new FormData();
		data.append('typeid',fileclas.value);
		$.each($('#upimgtoux')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		
		//alert(host+'/qdadmin/index/Upload/typeid/'+fileclas.value);
		//发送数据
		$.ajax({
			url:host+'/myadmin/upload/uploades',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				var strs= new Array(); //定义一数组 
				strs=data.split("|"); //字符分割 
				
				switch(strs[1]){
					case "0":
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>上传失败');
						break;
					case "1":
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>你上传的文件超过允许上传的最大值');
						break;
					case "2":
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>你上传的文件类型不正确');
						break;
					case "3":
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao1">&nbsp;</b>');
						$('#smlpiclook').attr("src",'/upload/vip/'+strs[0]);
						
						break;
					case "4":

						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>文件上传失败');
						break;
					default:
						$('#smlpicval').val(strs[0]);
						$('#uppic_error_sml').html('<b class="tubiao2">&nbsp;</b>文件上传失败');
				}

			},
			error:function(){
				alert('上传出错');
			}
		});
	});
	
	
	/*
	 * 上传视频文档
	 */
	//响应文件添加成功事件(上传文档)
	$("#upvoide").change(function(){
		var fileclas=document.getElementById("upvoidetype");
		var data = new FormData();
		data.append('typeid',fileclas.value);
		$.each($('#upvoide')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		
		//alert(host+'/qdadmin/index/Upload/typeid/'+fileclas.value);
		//发送数据
		$.ajax({
			url:host+'/myadmin/upload/uploades',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				var strs= new Array(); //定义一数组 
				strs=data.split("|"); //字符分割 
				
				switch(strs[1]){
					case "0":
						$('#voideurl').val(strs[0]);
						$('#uppic_error_void').html('<b class="tubiao2">&nbsp;</b>上传失败');
						break;
					case "1":
						$('#voideurl').val(strs[0]);
						$('#uppic_error_void').html('<b class="tubiao2">&nbsp;</b>你上传的文件超过允许上传的最大值');
						break;
					case "2":
						$('#voideurl').val(strs[0]);
						$('#uppic_error_void').html('<b class="tubiao2">&nbsp;</b>你上传的文件类型不正确');
						break;
					case "3":
						$('#voideurl').val(strs[0]);
						$('#uppic_error_void').html('<b class="tubiao1">&nbsp;</b>');
						
						break;
					case "4":

						$('#voideurl').val(strs[0]);
						$('#uppic_error_void').html('<b class="tubiao2">&nbsp;</b>文件上传失败');
						break;
					default:
						$('#voideurl').val(strs[0]);
						$('#uppic_error_void').html('<b class="tubiao2">&nbsp;</b>文件上传失败');
				}

			},
			error:function(){
				alert('上传出错');
			}
		});
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
});