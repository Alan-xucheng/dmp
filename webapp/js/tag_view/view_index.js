require.config({
    paths: {
        tag_common: __base_url + 'js/tag_manage/tag_common',
        search:__base_url+'js/common/util/search'
    },
    shim:{
    	'search':{
            deps:['jquery']
        },
    }
});
require(['comoperate','validform','chart','tag_common','artDialogHelper','jsrender','search'],function(Comoperate,Validform,MyChart,TagOperation,ArtDialog){
	var urlStore={
		keywords:__base_url+'ajax/common/commonAjax/fuzzySearchCombo',
		search:__base_url+'ajax/common/commonAjax/fuzzySearchTagDetail',
		trend:__base_url+'ajax/tagView/tagViewAjax/getTagStatsDetail',
		export:__base_url+'ajax/tagView/exportTagAjax/tagExport'
	};
	$.views.converters({
		packageParent:function(data){
			return $("#tp_tag_parent").render(data);
		},
		packageChild:function(data){
			return $("#tp_third_tag").render(data);
		}
	});
	var tagManage={
		eventInit:function(){
			/*
				趋势图展开或是收起
			*/
			$("body").on('click','.to-view-trend',function(){
				var _self=$(this);
				var trendWrapper=_self.parents('.tag-list-item').find('.tag-view-trend');
				var tagId=_self.attr('operate-id');
				var trendContent=trendWrapper.find('.trend-chart');
				trendContent.html('');
				if(_self.hasClass('icon-down')){
					_self.removeClass('icon-down').attr('title','收起');
					trendWrapper.removeClass('non').slideDown().addClass('loading');
					setTimeout(function(){
						tagManage.renderTrendChart(tagId);
					},500);
					
				}else{
					trendWrapper.slideUp().addClass('non');
					_self.addClass('icon-down').attr('title','展开');
				}
				return false;
			}).on('click','.radio',function(){//有效标签和全部标签切换
				var _self=$(this);
				var name=_self.attr('name');
				if(_self.hasClass('radio-checked')){

				}else{
					$('.radio[name="'+name+'"]').removeClass('radio-checked');
					_self.addClass('radio-checked');
				}
				var value=_self.attr('value');
				Search.data['effective']=value;
				var selectedTag=$('.tag-list-item.current .tag-item');
				if(!selectedTag.length){
					if(!$("#first_tag_wrapper").length){
						$("#high_seaarch_tag .action-search").click();
					}else{	
						TagOperation.viewInit({effective:value});
					}
				}else{
					TagOperation.data['effective']=value;
					var lastSelectedTag=selectedTag[selectedTag.length-1];
					$(lastSelectedTag).click();
				}
				
			}).on('click','#export_tag_view',function(){//导出标签
				var currentTag=$('#first_tag_wrapper li.current');
				var secondTags=$("#second_tag_wrapper .tag-list-item");
				var tagId=currentTag.attr('operate-id');
				if(!currentTag.length||!secondTags.length||!tagId){
					ArtDialog.openConfirmDialog('导出失败，暂无有效标签');
				}else{
					$(this).attr('href',urlStore.export+'?tag_id='+tagId);
				}
			});
		},
		// 给特定的元素加上提示图标
		addShowTip:function(){
			var tagList=$(".show-tip-item");
			$.each(tagList,function(i,item){
				item=$(item);
				var tagId=item.attr('operate-id');
				var flag='show-tip-'+tagId;
				if(item.next('.'+flag).length){
					return;
				}
				item.addClass(flag).after('<span class="show-tip" target-selector=".'+flag+'"> </span>');
			});
		},
		
		/*
			请求趋势图数据，并渲染
		*/
		renderTrendChart:function(id){
			Comoperate.ajaxOperate({
				url:urlStore.trend,
				data:{tag_id:id},
				successFun:function(result){
					var data={
						data:[],
						categories:[],
						name:''
					};
					var tagObj=tagStore[id];
					if(tagObj){
						data['name']=tagObj['tag_name'];
					}
					for(var i in result['data']){
						var item=result['data'][i];
						data['data'].push(Number(item));
						data['categories'].push(i);
					}
					MyChart.bindData('chart_'+id, ChartMod.getOption(data));
				}
			});
		},
		/*
			搜索框 展示结果
		*/
		searchTag:function(val){
			var param={keyword:val,type:1};
			param=$.extend({},param,Search.data);
			Comoperate.ajaxOperate({
				url:urlStore.search,
				data:param,
				beforeSendFun:function(){
					$(".main-wrapper").html('').addClass('loading');
				},
				successFun:function(result){
					var template=$("#tp_search_tag");
					var data=result['data']||[],html='',noDataHtml='<p class="m10 tac">暂无匹配标签</p>';
					if(template.length){
						html=$("#tp_search_tag").render(data);
					}
					$(".main-wrapper").html(html||noDataHtml);
				},
				errorFun:function(){
					ArtDialog.openConfirmDialog('数据加载失败');
				},
				completeFun:function(){
					$(".main-wrapper").removeClass('loading');
				}
			});
		}
	};
	var ChartMod={
		getOption:function(data){
			return { 
				chart: {
		            type: 'area',
		            height:180
		        },
		        legend:{
		        	enabled:false
		        },
		        color:['#87d2f2'],
				title: {
                    text: null
                },
                lang: {
                    noData:'暂无数据'
                },
                yAxis:{
					title:{
						text:false
					},
					min:0
				},
                tooltip: {
                	useHTML:true,
                    shared: true,
                    headerFormat:'<span class="tooltips-point-icon" style="color:{series.color}">\u25A0</span> <span class="tooltips-point-name">{series.name}</span> <br/><span class="tooltips-point-name">时间：</span> <span class="tooltips-point-value">{point.key}</span>',
	                pointFormat:'<br/><span class="tooltips-point-name">用户数：</span> <span class="tooltips-point-value">'
								+ '{point.y}'+'</span><br/>'
                },
                series:[{name:data.name,data:data.data}],
                xAxis:{
                	categories:data.categories,
                	labels:{
                		step:MyChart.getAutoAxiesStep(data.categories.length,10),
                	}
                }
            };
		},
		/*
			设置趋势图 横轴显示数据
		*/
		setSeries:function(){
			var data=[];
			for(var i=0;i<10;i++){
				data.push(Math.ceil(Math.random()*10));
			}
			return data;
		}
	};
	
	tagManage.eventInit();
	$(".radio[name='tag_effective']:first").click();

	//快捷搜索框
	Search({
		action:'#high_seaarch_tag',
		url:urlStore.keywords,
		data:{type:1},
		packageData:function(data){
			return data;
		},
		searchFun:tagManage.searchTag,
		selectCallBack:function(){
			var value=$.trim($(this).text());
			tagManage.searchTag(value);
		}
	});
});