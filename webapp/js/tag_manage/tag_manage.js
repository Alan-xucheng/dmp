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
require(['comoperate','validform','tag_common','artDialogHelper','jsrender','search'],function(Comoperate,Validform,TagOperation,ArtDialog){
	var urlStore={
		save:__base_url+'ajax/tagManage/tagManageAjax/addOrUpdateTag',
		del:__base_url+'ajax/tagManage/tagManageAjax/deleteTag',
		keywords:__base_url+'ajax/common/commonAjax/fuzzySearchCombo',
		search:__base_url+'ajax/common/commonAjax/fuzzySearchTagDetail'
	};
	var checkInfo={
		tag_name:{
			maxLength: 128,
            tip: '最多可输入128个字符'
		},
		tag_desc:{
			maxLength: 255,
            tip: '最多可输入255个字符'
		},
		update_span:{
			regex: /^[1-9]\d*$/,
            tip: '正整数',
          	min:1,
            numberTip:'最小值1'
		}
	};
	var tagManage={
		dialog:'',
		/*
			新增/编辑一个标签
			data:新增条件
			callback:回调处理方法
		*/
		addOrUpdateNewTag:function(args,template,target,callback){
			var data=args.data||{};
			if(target&&target.length){
				data['parent_id']=target.attr('parent-id');
				var parentData=tagStore[data['parent_id']]||{};
				data['parent_name']=parentData['tag_name']||'';	
			}
			var html=$('#'+template).render(args.data);
			tagManage.dialog=ArtDialog.openContentDialog({
				title:args.title,
				content:html,
				okVal:'确定保存',
				ok:function(){
					var data={};
					$('#tag_operate_wrapper').find('.regex,.checklength').blur();
					$('select.not-null').change();
					if($('.form-item-false').length){
						return false;
					}
					var dataList=$("#tag_operate_wrapper *[name]");

					$.each(dataList,function(i,item){
						var val=$.trim(item.value);
						if(val){
							data[item.name]=val;
						}
					});
					Comoperate.ajaxOperate({
						data:{tag:data},
						url:urlStore.save,
						successFun:function(result){
							if(result&&result['flag']){
								var resultData=result['data']||{};
								tagManage.renderTagInfoView(resultData);
								/*if(data['parent_id']&&data['parent_id']!='0'){
									$(".tag-list-item[operate-id='"+data['parent_id']+"']").find('.tag-item').click();
								}else{
									TagOperation.viewInit();
								}*/
							}else{
								ArtDialog.openConfirmDialog(result['msg']||'操作失败');
							}
						},
						errorFun:function(){
							ArtDialog.openConfirmDialog('操作失败');
						}

					});
				},
				init:function(){
					var wrapper=$("#tag_operate_wrapper");
					wrapper.find('*[placeholder]').placeholder();
					var setLengthTarget=wrapper.find('.checklength');
					$.each(setLengthTarget,function(i,item){
						var name=item.name;
						item=$(item);
						var obj=checkInfo[name];
						if(obj){
							item.attr('maxlength',obj['maxLength']);
						}
					});

				}
			});
		},
		/*
			新增编辑成功后 修改页面当前数据
		*/
		renderTagInfoView:function(data){
			var tagId=data['tag_id']||'';
			// 后端返回数据有问题，页面整体重新加载
			if(!tagId){
				TagOperation.viewInit();
			}
			var currentData=tagStore[tagId]||'';
			// 编辑
			if(currentData){
				var target=$(".tag-list-item[operate-id='"+tagId+"']").not(".th-second-title");
				var className=target.attr('class');
				var parentId=target.parents('.tag-wrapper').attr('id');
				var template='';
				switch(parentId){
					case 'first_tag_wrapper':
						template='tp_first_tag';
						if(target.hasClass('current')){
							$("#current_first_tag").text(data['tag_name']);
							$("#second_tag_wrapper").attr('parent-name',data['tag_name']);
						}
					break;
					case 'second_tag_wrapper':
						template='tp_second_tag';
						$("#parent_tag").replaceWith($("#tp_third_tag").render(data));
						$('#th_title').find('tr:not(.th-title)').addClass('th-second-title').attr('id','parent_tag');
					break;
					case 'third_tag_wrapper':
						template='tp_third_tag';
					break;
				}
				template=$("#"+template);
				if(template.length&&target.length){
					target.replaceWith(template.render(data));
					$(".tag-list-item[operate-id='"+tagId+"']").addClass(className);
				}
				
			}else{// 新增
				var parentTagId=data['parent_id']||'';
				var parentTarget=$(".tag-list-item[operate-id='"+parentTagId+"']").not(".th-second-title");
				var tagWrapper=parentTarget.parents('.tag-wrapper');
				var parentId=tagWrapper.attr('id');
				var template='',targetWrapper='';
				switch(parentId){
					//新增二级标签
					case 'first_tag_wrapper':
						template='tp_second_tag';
						targetWrapper='second_tag_wrapper';
					break;
					// 三级标签
					case 'second_tag_wrapper':
						parentTarget.find('.tag-item').click();	
					break;
					// 一级标签
					default:
						template='tp_first_tag';
						targetWrapper='first_tag_wrapper';
					break;
				}
				template=$("#"+template);
				var target=$("#"+targetWrapper);
				if(template.length&&target.length){
					target.append(template.render(data));
					if(!target.find('.current').length){
						target.find('.tag-item:first').click();
					}
				}
			}
			
		},
		/*
			删除一个标签
			id:指定标签
			callback:回调处理方法
		*/
		deleteTag:function(data,target,callback){
			var html=$("#tp_delete").render(data);
			tagManage.dialog=ArtDialog.openContentDialog({
				content:html,
				title:'提示',
				okVal:'确定删除',
				ok:function(){
					var tagId=$('#tag_id').val();
					if(!tagId){
						ArtDialog.openConfirmDialog('操作失败');
						return true;
					}
					Comoperate.ajaxOperate({
						url:urlStore.del,data:{tag_id:tagId},
						successFun:function(result){
							if(result&&result['flag']){
								ArtDialog.openConfirmDialog('删除成功',1);
								var parentWrapper=target.parents('.tag-wrapper');
								if(!parentWrapper.length){
									parentWrapper=target.parents('.th-second-title');
								}
								var wrapperId=parentWrapper.attr('id');
								switch(wrapperId){
									// 删除三级标签 点击二级选中标签
									case 'third_tag_wrapper':
										$("#second_tag_wrapper .current .tag-item").click();
									break;
									case 'parent_tag':
									case 'second_tag_wrapper':
										$("#first_tag_wrapper .current .tag-item").click();
									break;
									default:
										var tagTarget=$(".tag-list-item[operate-id='"+tagId+"']");
										if(tagTarget.length){
											tagTarget.remove();
										}
										if(!$("#first_tag_wrapper li.current").length){
											TagOperation.viewInit();
										}
									break;
								}
								delete tagStore[tagId];
							}else{
								ArtDialog.openConfirmDialog(result['msg']||'操作异常');
							}
						}
					});
				}
			});
		},
		eventInit:function(){
			$('body').on('click','.edit',function(){
				var _self=$(this);
				var tagId=_self.parents('.tag-list-item').attr('operate-id');
				var data=tagStore[tagId],tp,title;
				if(!data){
					ArtDialog.openConfirmDialog('已限制编辑操作');
				}
				var parentTarget=_self.parents('.tag-wrapper');
				var id=parentTarget.attr('id');
				switch(id){
					case 'third_tag_wrapper':
						title='编辑标签';
						tp='tp_tag';
					break;
					default:
						title='编辑分类';tp='tp_tag_type';
					break;
				}
				tagManage.addOrUpdateNewTag({title:title,data:data},tp);

			}).on('click','.delete',function(){
				var _self=$(this);
				var parentTarget=_self.parents('.tag-list-item');
				var tagId=parentTarget.attr('operate-id');
				if(!tagId){
					ArtDialog.openConfirmDialog('已限制删除操作');
				}
				var tagName=parentTarget.attr('title');
				tagManage.deleteTag({tag_id:tagId,tag_name:tagName},_self);

			}).on('click','#add_first_tag,#add_second_tag,#add_third_tag',function(){

				var _self=$(this),id=this.id;
				var title,parentTarget,tp;
				switch(id){
					case 'add_first_tag':
					case 'add_second_tag':
						title='新增分类';parentTarget=_self.parents('.tag-wrapper');tp='tp_tag_type';
					break;
					case 'add_third_tag':
						title='新增标签';
						parentTarget=$("#third_tag_wrapper");
						tp='tp_tag';
					break;
				}
				if(id!='add_first_tag'&&!parentTarget.attr('parent-id')){
					ArtDialog.openConfirmDialog('请先新增父级分类');
				}
				tagManage.addOrUpdateNewTag({title:title,data:{}},tp,parentTarget);
			}).on('keyup blur','.checklength,.regex,.not-null',function(){
				var name = this.name;
				var _self=$(this);
				
				var ruleObj=checkInfo[name];
				if(!ruleObj){
					return;
				}
				
				if(!_self.next('.check-result').length){
					_self.after('<span id="'+name+'_desc" class="check-result info-content-tip">&nbsp;</span>')
				}
				if(_self.hasClass('checklength')){
	           		 Validform.checkMaxLength($('#' + name), '', ruleObj);
				}else{
					Validform.regexCheckValue($('#' + name), '', ruleObj);
				}
			}).on('change','select.not-null',function(){
				var name = this.name;
				var _self=$(this);
				if(!_self.next('.check-result').length){
					_self.after('<span id="'+name+'_desc" class="check-result info-content-tip">&nbsp;</span>')
				}

					var tipTarget=$("#"+name+'_desc');
					if(Validform.checkNotNull(this.value,tipTarget)){
						Validform.checkValidResultTip(tipTarget,'',true);
					}
				
			});
		},
		searchTag:function(data){
			var param={keyword:data};
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
	$.views.converters({
		/*
			更新粒度
		*/
		setSelect:function(key){
			var html='';
			if(!key){
				html+="<option value=''>--请选择--</option>";
				key='';
			}
			for(var i in updateGranularity){
				html+='<option value="'+i+'" ';
				if(key==updateGranularity[i]){
					html+='selected';
				}
				html+='>'+updateGranularity[i]+'</option>';
			}
			return html;
		},
		packageTr:function(data,parent){
			if(parent){
				data['parent_flag']=parent;
			}
			return $("#tp_search_item").render(data);
		}
	});
	Search({
		action:'#high_seaarch_tag',
		url:urlStore.keywords,
		data:{type:0},
		packageData:function(data){
			return data;
		},
		searchFun:tagManage.searchTag,
		selectCallBack:function(){
			var value=$.trim($(this).text());
			tagManage.searchTag(value);
		}
	});
	TagOperation.viewInit();
	tagManage.eventInit();
});