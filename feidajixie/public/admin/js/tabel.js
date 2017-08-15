/*
 * 折线图
 */
function zhexiantu(name,title,titles,xzhuobiao,yzhuobiao){
	var myChar=echarts.init(document.getElementById(name));
	var option = {
		backgroundColor: 'rgba(0,0,0,0.45)',
		title: {
			text: title,
			subtext: titles,
			left:'center',
			top:10,
			textStyle:{
				color:'#fa0',
				
			},
			subtextStyle:{
					color:'#f00'
			}
		},
		grid:{
			left:50,
			right:50,
			top:70
		},
		tooltip: {
			trigger: 'axis'
		},
		xAxis: {
			type: 'category',
			boundaryGap: false,
			data: xzhuobiao,
			nameTextStyle:{
				color:'#ff0'
			}
		},
		yAxis: {
			type: 'value'
		},
		series: [
				    
			{
				name: '发布数量',
				type: 'line',
				smooth: true,
				data: yzhuobiao
			}
				
		]
	};
	// 使用刚指定的配置项和数据显示图表。
    myChar.setOption(option);
}
//饼形图
function bingxingtu(name,title,titles,obtitle,dataary){
	//饼形图
    var myChar=echarts.init(document.getElementById(name));		
    var option= {
		
		backgroundColor: 'rgba(0,0,0,0.45)',
		title:{
			text:title,
			subtext: titles,
			left:'center',
			top:15,
			textStyle: {
			    color: '#f08ab0'
			},
			subtextStyle:{
				color:'#f00'
			}						
		},
			
		tooltip : {
		    trigger: 'item',
		    formatter: "{a} <br/>{b} : {c} ({d}%)"
		},
					
		series : [
		    {
		        name:obtitle,
		        type:'pie',
		        radius : '55%',
		        center: ['50%', '50%'],
		        data:dataary,
		        label: {
		            normal: {
		                textStyle: {
		                    color: '#fa0'
		                }
		            },
		            
		            
		        }
		    }
		]
	};
    myChar.setOption(option);
}
