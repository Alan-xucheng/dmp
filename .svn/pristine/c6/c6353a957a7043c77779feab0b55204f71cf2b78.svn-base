/*================= comoperate_module =================
 * comoperate_module:
 * author:zysun
 * createDate:2015-11
 * createContent:
 ========================================================*/

define(['jquery','placeholder','artDialogHelper','tips'],function(jq,ph,ArtDialogHelper){

	var comoperate_module={
		/**
		 * [number_format js 数字 千位分组]
		 *
		 * @param  {[type]} str 	[数据]
		 * 
		 */
		numberFormat:function(str){
			var str = String(str);
			// str = str.replace(/(?=(?:\d{3})+(?!\d))/g, ',');
			// str = str.replace(/^,/, '');
			str=str.replace(/([^-]\d+)/,function($1){
				$1=$1.replace(/(?=(?:\d{3})+(?!\d))/g,',').replace(/^,/,"");
				return $1;
			});
			return str.indexOf('.') > 0 ? str.split('.')[0] + '.' + str.split('.')[1].replace(/,/g,'') : str;
		},
		handleSpecialCharacter:function(obj) {
			var str = JSON.stringify(obj);
			str = str.replace(/&/g,'&amp;');
			return JSON.parse(str);
		},
		/**
		 * [checkNull 检查数据是否为空]
		 *
		 * @param  {[type]} data 	[数据]
		 * 
		 */
		checkDataNull:function (data){
			if(data){
				return data;
			}else{
				return 0;
			}
		},
		//获取字典信息
		getDicInfo:function(targetId){
			var result={ tv:{},vt:{} };
			$("#"+targetId).find("option").each(function(){
				var tag=$(this);
				var txt=tag.text(),val=tag.attr("value")||txt;
				result['tv'][txt]=val;
				result['vt'][val]=txt;
			});
			return result;
		},
		//ajax操作
		ajaxOperate:function(set){
			$.ajax({
				type:set.type||'post',
	   			url:set.url||'/',
	   			async:(typeof set.async !='undefined')?set.async:true,
	   			data:set.data,
	   			dataType:set.dataType||'json',
	   			beforeSend:function(){
	   				if(typeof set.beforeSendFun =='function'){
	   					set.beforeSendFun();
	   				}
	   			},
	   			success:function(result){
	   				if(typeof set.successFun =='function'){
	   				 return	set.successFun(result);
	   				}
	   			},
	   			error:function(result){
	   				if(typeof console !='undefined'){
	   					console.log("获取 "+set.url+" 数据失败");
	   				}
	   				if(typeof set.errorFun =='function'){
	   					set.errorFun(result);
	   				}		
	   			},
	   			complete:function(result){
	   				
	   				if(typeof set.completeFun =='function'){
	   					set.completeFun(result);
	   				}
	   			}

	   		});
		},
		//获取select元素选中的显示文本内容
		getSelectedTxt:function(target,value){
			var result;
			target.find("option").each(function(i){
				var txt=$(this).text();
				var item_value=$(this).attr("value");
				if(value==item_value){
					result=txt;
					return false;
				}
			});
			return result;
		},
		showAdBlockTip:function(){
			var html='<div id="adblock_wrapper" class="block-layer" style="z-index:1000;position:absolute;top:0px;width:100%;height:100%; background: rgba(0, 0, 0,0.7);"><div class="block-content" style="width:624px;margin:80px auto;min-height:70px;padding:35px 200px;background:#fff;"><div style="padding:10px 0px;padding-left:105px;height:50px;background:url(/img/common/img_block.png) no-repeat 20px  center;color:#4c4c4c;font-size:14px;line-height:24px;"><p>如果启用了广告拦截软件，那么广告工具可能无法正常工作。</p><p>请关闭广告拦截软件并刷新页面。</p></div></div></div>';
			$('body').css({'overflow': 'hidden'}).append(html);
		}, 
		//兼容ie string：yyyy-MM-dd HH:mm:00
		newDate:function(string){
			if(!string){
				return string;
			}
			var tempDate=string.split(" ");
			var date=tempDate[0].split("-"),time=[];
			var newDate=new Date();
			newDate.setUTCFullYear(date[0], date[1]-1, date[2]); 
			if(tempDate[1]&&tempDate.indexOf(':')>-1){
				time=tempDate[1].split(":")
				newDate.setHours(time[0],time[1],0,0);
			}
			return newDate;
		},
		// 校验是否登录
		checkLogin:function(){
			var isLogin = false;
	        this.ajaxOperate({
	            url:  __base_url + 'ajax/common/loginAjax/checkLogin',
	            async: false,
	            successFun: function(result) {
	                if (result.flag) {
	                    isLogin = true;
	                }
	            }
	        })
	        return isLogin;
		},
		/*
			修改页面主体内容区域高度
		*/
		changeMainHeight:function(list,target){
			if(target.length){
				var height=$(window).height();
				$.each(list,function(i,item){
					item=$(item);
					height=height-(item.height()||0);
				});
				target.height(height);
			}
		}
	};
	$('*[placeholder]').placeholder();
	/*$('body').on('click','a',function(){
		
		if(comoperate_module.checkLogin()){
			return true;
		}
		ArtDialogHelper.openConfirmDialog('登录信息已失效，确认重新登录！','','',function(){
			window.top.location.reload();
		});
		event.stopPropagation();
		return false;
	})*/
	// 输入提示
	/*$('body').on('keyup mouseenter','input[type="text"]',function(){
		Tips({
			selector:$(this),
			content:$(this).val()
		});
	}).on('mouseleave','*',function(){
		TipsClear();//清楚提示
	});*/
	return comoperate_module;
});
//Date增加format
Date.prototype.formatStr = function(format) {
	var o = {
		"M+" : this.getMonth() + 1, // month
		"d+" : this.getDate(), // day
		"H+" : this.getHours(), // hour
		"m+" : this.getMinutes(), // minute
		"s+" : this.getSeconds(), // second
		"q+" : Math.floor((this.getMonth() + 3) / 3), // quarter
		"S" : this.getMilliseconds()
	};

	if (/(y+)/.test(format)) {
		format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	}

	for (var k in o) {
		if (new RegExp("(" + k + ")").test(format)) {
			format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
		}
	}
	return format;
};
