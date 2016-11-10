define(['artDialog'],function(){
	var DialogHelper={
		wapperHtml:function(html){
			return '<div style="width:100%;over-flow:auto;">'+html+'</div>';
		},
		openContentDialog:function(arg){
			return $.dialog({
				id:arg.id||'dialog',
			    title:arg.title||'',
			    content: arg.content||'',
				lock:arg.lock||true,
			    dblclick_hide:false,
			    okVal:arg.okVal||'确定',
			    cancelVal:arg.cancelVal||'取消',
			    top:arg.top||'50%',
			    left:arg.left||'50%',
			    ok:arg.ok||function(){},
			    cancel:arg.cancel||function(){},
			    init:arg.init||null,
			    drag:false
			});
		},
		//打开普通对话框-暂不用
		openNormalDialog:function(arg){
			return $.dialog({
				id:arg.id||'dialog',
				lock:true,
			    title:arg.title||'',
			    content: arg.content||'',
			    dblclick_hide:false,
			    okVal: '确定',
			    cancelVal:'取消',
			    top:'50%',
			    left:'50%',
			    ok:arg.ok||function(){},
			    cancel:arg.cancel||function(){},
			    drag:false
			});
		},
		//打开确认对话框
		/**
		 * [openConfirmDialog 打开确认对话框]
		 * @param  {[type]} msg  [提示信息]
		 * @param  {[type]} type [类型，警告或正确]
		 * @param  {[type]} ok   [ok的function]
		 * @return {[type]}      [description]
		 */
		openConfirmDialog:function(msg,time,title,ok){
			return $.dialog({
				id:'dialog',
				lock:true,
			    title:title||'提示',
			    content: msg,
			    okVal: '确定',
			    dblclick_hide:false,
			    top:'50%',
			    left:'50%',
			    ok:ok||function(){},
			    drag:false,
			    time:time||''
			});
		},
		// 没有按钮提示框
		openDailog:function(title,content,opacity,time){
			return $.dialog({
				id:'dialog',
				lock:true,
			    title:title||'提示',
			    content: DialogHelper.wapperHtml(content),
			    dblclick_hide:false,
			    top:'50%',
			    left:'50%',
			    drag:true,
			    time:time||'',
			    opacity:opacity||0.1
			});
		}
	};
	return DialogHelper;
});