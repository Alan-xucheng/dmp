/*
	20160517 zysun
	输入框 提示输入文本
*/
(function(win){
	var baseArgs={
		tipId:'custom_tips',
		selector:'',//jquery选择器对象
		content:'',//提示内容，支持html
		locationTop:'',//位置 上
		locationLeft:'',//位置 下
		loading:false, //是否延迟加载
		style:'',//black
		init:function(){},//初始化内容操作
		relocate:function(){}//重新定位
	};
	// 创建提示html或是更改提示内容
	var createWrapper=function(settings){
		var tipId=settings['tipId'];
		var tipTarget=$('#'+tipId);
		if(tipTarget.length){
			tipTarget.find('.custom-tips-content').html(settings['content']);
		}else{
			var html='<div id="'+tipId+'" class="custom-tips-wrapper ';
			if(settings.style){
				html+=settings.style;
			}
			html+=' "><div class="custom-tips-content">'+settings['content']+'</div></div>';
			$('body').append(html);
			tipTarget=$('#'+tipId);
		}
		var selector=settings['selector'];
		var offset=selector.offset();
		tipTarget.css({
			top:(offset.top+selector.height()+5)+'px',
			left:offset.left+'px'
		});
	};
	// 对外提供调用接口
	win.Tips=function(args){
		var settings=$.extend({},baseArgs,args);//扩展参数
		var selector=settings['selector'];
		if(selector.length>0&&settings['content']){
			createWrapper(settings);
		}else{
			TipsClear();
		}
	};
	// 对外提供清除提示接口
	win.TipsClear=function(){
		var tipTarget=$('#'+baseArgs['tipId']);
		if(tipTarget.length){
			tipTarget.remove();
		}

	};
	/*
		添加样式
	*/
	var style=document.createElement('style');
	html='.custom-tips-wrapper{position:absolute; padding:8px; z-index:9999;max-width:500px;min-width:100px;background: rgba(253,253,253,0.8);border:1px solid #aaa;border-radius: 4px;color:#666;font-size:12px;}';
	html+='.custom-tips-wrapper.black{background: rgba(0,0,0,0.8);color:#fff;}';
	style.innerHTML=html;
	document.head.insertBefore(style,document.head.firstChild);
})(this);