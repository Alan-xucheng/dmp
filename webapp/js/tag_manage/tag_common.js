// 分页
function tagPageList(){};
var tagStore={};
define(['comoperate','artDialogHelper','jsrender','util_page','tips'],function(Comoperate,ArtDialog){
	var tagUrlStore={
		getChild:__base_url+'ajax/tagManage/tagManageAjax/getChildTag',/*一二级标签*/
		getChildTable:__base_url+'ajax/tagManage/tagManageAjax/getPaginationTag'/*三级*/
	};
	$(window).resize(function(){
		Comoperate.changeMainHeight($("#page_header,#page_second_header"),$(".main-wrapper"));
	});
	Comoperate.changeMainHeight($("#page_header,#page_second_header"),$(".main-wrapper"));
	$.views.converters({
		/*
			封装标签数据，用处：提示、展示父级标签信息等
		*/
		packageTagData:function(id,data){
			tagStore[id]=data;
		},
		/*
			三位一分
		*/
		numberFormat:Comoperate.numberFormat

	});
	if(typeof noThirdHtml=='undefined'){
		noThirdHtml='';
	}
	// 第三级标签的链接
	if(typeof thirdTagUrl!='undefined'){
		tagUrlStore['getChildTable']=thirdTagUrl;
	}
	var TagOperattion={
		data:{},
		/*
			标签 通用事件绑定
		*/
		tagCommonEventInit:function(){
			$("#current_first_tag").after('<span class="show-tip" target-selector="#current_first_tag"> </span>');
			$('body').on('click','.tag-list-item .tag-item',function(){
				var _self=$(this),text=_self.text();
				var parentLi=_self.parent('li');
				parentLi.addClass('current').siblings('li.current').removeClass('current');
				var parentUl=_self.parents('ul.tag-wrapper');
				var tagId=parentLi.attr('operate-id');
				var parentUlId=parentUl.attr('id')||'';
				var targetWrapper=$("#"+parentUl.attr('child-target'));
				var template=targetWrapper.attr('template')||'';

				var callbackFun='';
				
				targetWrapper.find('.tag-list-item').remove();
				var url='';
				switch(parentUlId){
					case 'first_tag_wrapper':
						$("#current_first_tag").text(text).attr('operate-id',tagId);
						// 二级、三级 加loading
						$('#third_tag_wrapper').find('.tag-list-item').remove();
						TagOperattion.loading($("#second_tag_wrapper,#third_tag_content"),true);
						$('#parent_tag').html('<td colspan="6">加载数据</td>');
						var exportTarget=$('#export_tag_view');
						if(exportTarget.length){
							exportTarget.attr('href','javascript:void(null)');
						}
					break;
					case 'second_tag_wrapper':
						var tagData=tagStore[tagId];
						template='tp_third_tag';
						var parentTemplate=$("#tp_tag_parent");
						if(!parentTemplate.length){
							parentTemplate=$("#tp_third_tag");
						}
						$('#parent_tag').replaceWith(parentTemplate.render([tagData]));
						$('#th_title').find('tr:not(.th-title)').addClass('th-second-title').attr('id','parent_tag');
						TagOperattion.loading($("#third_tag_content"),true);
						url=tagUrlStore.getChildTable;
					break;
				}
				targetWrapper.attr({'parent-id':tagId,'parent-name':text});
				TagOperattion.renderChildTags({tag_id:tagId},targetWrapper,template,null,url);
			}).on('mouseenter','.show-tip',function(){
				var _self=$(this);
				var target=$(_self.attr('target-selector'));
				if(!target.length){
					return;
				}
				var id=target.attr('operate-id');
				var data=tagStore[id]||'';
				var text=$("#tp_tag_type_detail").render(data);
				if(data&&text){
					Tips({
						selector:_self,
						content:text,
						style:'black'
					});

				}

			}).on('mouseleave','*',function(){
				TipsClear();//清楚提示
			});
			
		},
		/*
			初始化页面，开始加载一级标签，其他均loading
		*/
		viewInit:function(args){
			if(typeof args !='undefined'){
				TagOperattion.data=$.extend({},args);
			}
			TagOperattion.loading($("#first_tag_wrapper,#second_tag_wrapper,#third_tag_content"),true);
			TagOperattion.renderChildTags({},$("#first_tag_wrapper"),'tp_first_tag');
		},
		/*
			loading样式
		*/
		loading:function(targets,flag){
			if(flag){
				targets.addClass('loading');
			}else{
				targets.removeClass('loading');
			}
		},
		/*
			渲染子标签
			data:查询条件
			callback:回调处理方法
		*/
		renderChildTags:function(data,target,tp,callback,url){
			if(!target){
				return;
			}
			data=$.extend({},TagOperattion.data,data);
			var pageTarget=$('#paging');
			pageTarget.addClass('non');
			Comoperate.ajaxOperate({
				data:data,
				url:url||tagUrlStore.getChild,
				successFun:function(result){
					if(target.hasClass('loading')){
						target.removeClass('loading');
					}else{
						target.parents('.loading').removeClass('loading');
					}
					if(result&&result['flag']){
						if(typeof callback =='function'){
							callback(result);
						}
						var childTag=result['data']||[],html;
						var id=target.attr('id');
						// 三级分页
						if(id=="third_tag_wrapper"){
							var rateTarget=$("#tag_rate");
							if(rateTarget.length){
								rateTarget.text(childTag['rate']||0);
							}

							html=$.trim($("#"+tp).render(childTag['data']||[]));
							var totalCount= parseInt(childTag['total_count'])||0,size=parseInt(childTag['page_size'])||1;
                           var pageCount = Math.ceil(totalCount/size)||0
                            // 分页
                            PPage('paging', parseInt(childTag['current_page']), pageCount, 'tagPageList', false, totalCount, size);
                            
                            if (totalCount > 0&&html.length) {
                                pageTarget.removeClass('non');
                            }
						}else{
							html=$("#"+tp).render(childTag);
						}
						target.find('.tag-list-item').remove();
						target.append(html);
						
						// 一二级或是三级无数据处理
						if(id=="third_tag_wrapper"&&!html){
							$("#third_tag_wrapper").html(noThirdHtml||'');
							return;
						}else if(id=='first_tag_wrapper'&&html){
							$('#export_tag_view').removeClass('non');
						}
						var firstTarget=target.find('.tag-item:first');
						if(firstTarget.length){
							firstTarget.click();
						}else{

							switch(id){
								case 'first_tag_wrapper':
									TagOperattion.loading($("#second_tag_wrapper,#third_tag_content"),false);
									$('#parent_tag').html('');
									$("#third_tag_wrapper").html(noThirdHtml||'').attr('parent-id','');
									$("#second_tag_wrapper").attr('parent-id','').find('.tag-list-item').remove();
									$('#export_tag_view').addClass('non');
								break;
								case 'second_tag_wrapper':
									TagOperattion.loading($("#third_tag_content"),false);
									$('#parent_tag').html('');
									$("#third_tag_wrapper").html(noThirdHtml||'').attr('parent-id','');
								break;
							}
							return ;
						}
					}else{
						ArtDialog.openConfirmDialog(result['msg']||'数据加载失败');
					}
					
				},
				errorFun:function(){
					ArtDialog.openConfirmDialog('数据加载失败');
				}
			});

		}
	};
	// 分页展示三级标签
	tagPageList=function(pageIndex){
		// loading 分页
		$("#third_tag_wrapper .tag-list-item").remove();
		TagOperattion.loading($("#third_tag_content"),true);

		var tagId=$("#second_tag_wrapper li.current").attr('operate-id');
		TagOperattion.renderChildTags({tag_id:tagId,page_num:pageIndex},$("#third_tag_wrapper"),'tp_third_tag',null,tagUrlStore.getChildTable);
	};
	TagOperattion.tagCommonEventInit();
	// TagOperattion.viewInit();
	return TagOperattion;
});