/**
 * note chart_module.js
 * author wbye
 * create date 2014-09-24
 * note 图表数据展示以及相应事件处理
 */
define(['comoperate','highcharts','highcharts_nodata','jquery'],function(Comoperate){

	    var MyChart={};
	    //默认配置
	    MyChart.defaultOptions={
	    	chart:{
	    		type:'spline'
	    	},
	        title:{
	            style:{
	                color: '#3e393e',
					fontSize: '14px',
	                fontFamily:'微软雅黑'
	            }
	        },
	       	//颜色
	       	colors:['#32b6f3','#0b8ddc','#378b49','#ed683f','#f4748b','#e9a208','#993a88','#5895cb','#94ab3f','#00aba6','#f13752','#3840e3']
	        ,
	        lang: {
	    		noData:'暂无数据'
	        },
	        noData: {
	            style: {    
	                fontWeight: 'bold',     
	                fontSize: '15px',
	                color: '#5c565e',
	                //letterSpacing:'2px',
	                fontFamily:'微软雅黑'       
	            }
	        },
	        credits:{
	        	enabled:false
	        },
	        plotOptions: {
	            series: {
	               lineWidth:2,
	               connectNulls:true,
	               marker: {
                        radius: 3,
                        lineWidth: 1,
                        lineColor: '#ffffff'
                     //    states:{
                     //        // hover:{
                     //        //     radius: 4,
                     //        //     lineColor: '#ffffff'
                     //        // }
                    	// }
                	}
	            },
	            area:{
	                
	                fillOpacity:"0.3"
	            },
	            areaspline:{
	                
	                fillOpacity:"0.3"
	                
	            }
	        },
	    	xAxis:{
	    		gridLineColor:'#f1f1f1',
	    		gridLineWidth: 1,
	    		lineColor:'#4778ae',
	    		lineWidth:2,
	    		labels:{
	    			style:{
	    				fontSize:'12px',
	    				color:'#4d4d4d'
	    			}
	    		}
	    	},
	        yAxis:{
	            gridLineColor: '#d4d4d4',
	            gridLineWidth: 1,
	            lineColor:'#4778ae',
	            lineWidth:2,
	    		labels:{
	    			style:{
	    				fontSize:'12px',
	    				color:'#4d4d4d'
	    			}
	    		}
	        },
	    	
	        tooltip:{
	        	useHTML:true,
				borderColor:'#696969',
				crosshairs: {
					width: 1,
					color: 'gray',
					dashStyle: 'shortdot'
				}
	        },
	        legend:{
	        
	        }
	    };

	    /**
	     * [bindHighchartData 绑定highcharts数据] 
	     * @param  {[type]} targetId  目标id
	     * @param  {[type]} selfOptions 自定义选项
	     * @return {[type]} 无
	     */
	    MyChart.bindData=function(targetId,selfOptions){
	    	// console.log(selfOptions);
	        var highchartsOptions=$.extend(true,{},MyChart.defaultOptions,selfOptions);
	        // console.log(highchartsOptions);
	        //暂无数据统一处理
	        
	        if(selfOptions&&selfOptions.series){
	            var length=selfOptions.series.length;
	            var noDataSum=0;
	            for(var i in selfOptions.series){
	                if(selfOptions.series[i].data){
	                    if(selfOptions.series[i].data.length==0){
	                        noDataSum++;
	                    }
	                }
	            }
	            if(noDataSum==length){
	                 highchartsOptions=$.extend(true,{},highchartsOptions,{series:null});
	                $("#"+targetId).highcharts(highchartsOptions);
	                return;
	            }
	        }
	        //调用highchart
	        $("#"+targetId).highcharts(highchartsOptions);
	    };
	    /**
	     * [getData description]
	     * @param  {[type]} set [ajax 配置] {
	     *       url: 'xxx'---url地址
	     *       data:'xxx'-发送的参数
	     *       dataType:'xxx'
	     * }
	     * @return {[type]}     [description]
	     */
	   	MyChart.getData=function(set){
	   		$.ajax({
	   			url:set.url||'/',
	   			aysnc:set.aysnc||true,
	   			data:set.data,
	   			dataType:set.dataType||'json',
	   			success:function(result){
	   				if(typeof set.successFun =='function'){
	   					set.successFun(result);
	   				}
	   			},
	   			error:function(){
	   				if(typeof console !='undefined'){
	   					console.log("获取 "+set.url+" 数据失败");
	   				}		
	   			},
	   			complete:function(){

	   			}
	   		});
	   	};
	   	/**
	   	 * [judgeIndex 根据输入判断选定的指标]
	   	 * @param  {[type]} txt [输入值]
	   	 * @return {[type]} result={
	    		unit:''---单位,
	    		key:''---key值,
	    		titlePart:''
	    	};    [description] 
	   	 * 
	   	 */
	    MyChart.judgeIndex=function(txt){
	    	var result={
	    		unit:'',
	    		key:'',
	    		//val:'',
	    		titlePart:''
	    	};
	    	switch (txt){                       
	    		case 'eCPM':
	    		case '千次展示成本':
	    			result.key='eCPM';
	    			result.unit='￥';
	    			result.titlePart='（RMB）';
	    		;break;
	    		case 'eCPC':
	    		case '单次点击成本':
	    			result.key='eCPC';
	    			result.unit='￥';
	    			result.titlePart='（RMB）';
	    		;break;
	    		//展示次数
	    		case '展示次数':
	    			result.key='IMPRESSION_NUM';
	    			result.titlePart='';
	    		break;
	    		//点击次数
	    		case '点击次数':
	    			result.key='CLICK_NUM';
	    			result.titlePart='';
	    		break;
	    		//请求次数
	    		case '请求次数':
	    			result.key='REQUEST_NUM';
	    			result.titlePart='';
	    		break;
	    		//填充率
	    		case '填充率':
	    			result.key='fill_rate';
	    			result.unit='%';
					result.titlePart='（百分比）';	    		
	    		break;
	    		//点击率
	    		case '点击率':
	    			result.key='click_rate';
	    			result.unit='%';
	    			result.titlePart='（百分比）';
	    		break;
	    		//收入金额
	    		case '总计收入':
	    			result.key='TOTAL_INCOME';
	    			result.unit='￥';
	    			result.titlePart='（RMB）';
	    		break;
	    		//广告消费
	    		case '广告支出':
	    			result.key='INCOME';
	    			result.unit='￥';
	    			result.titlePart='（RMB）';
	    		break;
	    		default:
	    		;
	    	};
	    	return result;
    	};
	    /**
	     * [changeIndex 切换chart指标]
	     * @param  {[type]} target      [目标源]
	     * @param  {[type]} completeFun [完成触发的函数]
	     * @param  {[type]} className   [额外添加的选中类]
	     * @return {[type]}             [description]
	     */
	    MyChart.changeIndex=function(target,completeFun,className){
	    	className=className||'cur-options';
    	    //应用选项点击事件
			target.on("click",function(){
				$(this).siblings().removeClass(className).end().addClass(className);
				if(typeof completeFun=='function'){
					completeFun();	
				}
			});
	    }

	    /**
	     * 获取普通的tooltip
	     * @param  {[type]} keyInfo    [judgeIndex返回的结果]
	     * @param  {[type]} Comoperate [Comoperta模块]
	     * @return {[type]}            [description]
	     */
	    MyChart.getNormalTooltip=function(keyInfo){
	  		var str='<span class="tooltips-header">'+this.x+'</span><br/>';
			str+="<span class='tooltips-point-icon' style='color:"+this.series.color+"'>\u25A0</span> <span class='tooltips-point-name'>"+this.series.name+"：</span> <span class='tooltips-point-value'>"+ (keyInfo.unit=='￥'?('￥'+Comoperate.numberFormat(this.y.toFixed(2))):(Comoperate.numberFormat(this.y)+keyInfo.unit))+'</span><br/>';
			return str;		
	    }

	    /**
	     * [getAutoAxiesStep 获取xAxies的自适应宽度]
	     * @param  {[type]} length [description]
	     * @return {[type]}        [description]
	     */
        MyChart.getAutoAxiesStep=function(length,maxStep){
	        maxStep=maxStep||7;
	        if(length<maxStep){
	            return null;
	        }else if(length>maxStep&&length<=maxStep*2){
	            return 2;
	        }else{
	            return parseInt(length/maxStep);
	        }
	    };
	    //隐藏年份
	    MyChart.hideYear=function(){
	        return function() {
	                    var temp=this.value.replace(/\d{4}/,'');
	                    return temp;
	                };
	    };
	    return MyChart;

});