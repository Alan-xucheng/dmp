/*
	20160629 zysun
	下拉框，查询
*/
(function(win){

	var search={
		data:{},
		eventInit:function(args){
			$('body').on('click','.search-result ul li',function(){
				var _self=$(this);
				var parentTarget=_self.parents('.search-wrapper');
				var text=_self.text();
				var searchInput=parentTarget.find('.search-input');
				_self.parent('').hide();
				if(searchInput.length){
					parentTarget.find('.search-input').val(text);
				}
			}).click(function(evt) {
			    
			    var wrapper= $(evt.target).parents('.search-wrapper');
			    if(!wrapper.length){
			    	 $(".search-result ul").html('').hide();
			    }
			  });
			var actionTarget=$(args.action);
			actionTarget.on('keyup focus','.search-input',function(){
				var _self=$(this);
				var text=$.trim(_self.val());
				var parentTarget=_self.parents(".search-wrapper");
				var resultTarget=parentTarget.find('.search-result ul');

				resultTarget.html('').slideUp(100);
				
				var data={keyword:text};
				if(typeof args.data =='object'){
					data=$.extend({},data,args.data);
				}
				data=$.extend({},data,search.data);
				if(args.url){
					$.ajax({
						type:'post',
			   			url:args.url,
			   			data:data,
			   			dataType:'json',
			   			success:function(result){
			   				var data=result['data']||[];
			   				var html='';
			   				if(typeof args.packageData =='function'){
			   					data=args.packageData(data);
			   				}
			   				for(var  i in data){
			   					var item=data[i];
			   					html+='<li>'+item['name']+'</li>';
			   				}
			   				if(html){
								resultTarget.html(html).slideDown(100);
							}else{
								resultTarget.html('').slideUp(100);
							}
			   			}
			   		});
				}
			}).on('click','.action-search',function(){
				$('.search-result ul').html('').hide();
				var keywords=$.trim($(this).parents('.search-wrapper').find('.search-input').val());
				args.searchFun(keywords,win.Search.data);
			});
			if(typeof args.selectCallBack=='function'){
				actionTarget.on('click','.search-result ul li',args.selectCallBack);
				
			}
			
		}
	};
	win.Search=search.eventInit;
	win.Search.data=search.data;
})(this);