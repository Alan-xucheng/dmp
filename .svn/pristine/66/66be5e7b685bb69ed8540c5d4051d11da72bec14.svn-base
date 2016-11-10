/*================= form valid module =================
 * form valid module:自定义表单验证
 * author:zysun
 * createDate:2015-07-27
 * createContent:
 ========================================================*/

//需要jquery
define(['jquery'], function() {
	//闭包reg
	var reg = {
		empty: /\S/,
		jsrestrict: /<|>|\/|"|'/,
		jsrestrictUrl: /<|>|"|'/,
		phomenumber: '',
		email:{
			regex:/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/,
			tip:'邮箱格式错误'
		},
		img: {
			regex: /(\.jpg|\.jpeg|\.png|\.gif)$/i,
			tip: "请上传png,jpg,gif,jpeg文件",
			path: '',
			text: '请上传',
			successText: '已上传',
			loadingText: '请勿进行其他操作，正在上传中...'
		}
	};
	var valid = {
		tip: {
			nullTip: '不能为空',
			specialWords: '不能包含\'"/<>等非法字符',
			specialWordsUrl: '不能包含\'"<>等非法字符'
		},
		regexStore:{
			email:reg.email
		},
		/**
		 * [checkValidReg 正则表达式验证]
		 * @param  {[type]} target     [目标/text]
		 * @param  {[type]} reg  reg 正在表达式
		 *  \S 判断为空
		 * @return {[type]}           [true/false]
		 */
		checkValidReg: function(target, reg) {
			var txt;
			//判断是否是对象，是对象的话默认为jquery对象 
			if (typeof target == 'object') {
				txt = $.trim(target.val());
			} else {
				//认为传入的值就是待检查的内容
				txt = target;
			}
			if (reg.test(txt)) {
				return true;
			} else {
				return false;
			}
		},
		/**
		 * [checkValidResultTip 验证结果提示]
		 * @param  {[type]} target     [目标]
		 * @param  {[type]} tip        [提示语句]
		 * @param  {[type]} addClass   [true/false 验证结果添加样式]
		 * @param  {[type]} noIconFlag [是否不需要显示验证标志]
		 */
		checkValidResultTip: function(target, tip, addClass, noIconFlag) {
			// 当提示tip不为真时
			if (!tip) {
				tip = "&nbsp;";
			}
			/*不需要结果提示*/
			if (noIconFlag) {
				target.html(tip).removeClass('form-item-true form-item-false');
				return;
			}
			target.html(tip).addClass("form-item-" + addClass).removeClass("form-item-" + !addClass);
		},
		/**
		 * [checkNotNull 空校验]
		 * @param  {[type]} val        [txt]
		 * @param  {[type]} tipTarget  [校验提示目标对象]
		 * @return {[type]} 校验结果   [true/false]
		 */
		checkNotNull: function(val, tipTarget) {
			if (!reg['empty'].test(val)) {
				this.checkValidResultTip(tipTarget, this['tip']['nullTip'], false);
				return false;
			}
			return true;
		},
		/**
		 * [checkSpecialWord 特殊字符校验]
		 * @param  {[type]} val        [txt]
		 * @param  {[type]} tipTarget  [校验提示目标对象]
		 * @param  {[type]} isUrlFlag  [是否是url校验]
		 * @return {[type]} 校验结果   [true/false]
		 */
		checkSpecialWord: function(val, tipTarget, isUrlFlag) {
			val = $.trim(val);
			var regex = reg['jsrestrict'],
				tip = this['tip']['specialWords'];
			//url 校验，不校验证斜杠
			if (isUrlFlag) {
				regex = reg['jsrestrictUrl'];
				tip = this['tip']['specialWordsUrl'];
			}
			if (!this.checkValidReg(val, regex)) {
				return true;
			} else {
				this.checkValidResultTip(tipTarget, tip, false);
				return false;
			}
		},
		/**
		 * [checkSpecialWord 特殊字符校验]
		 * @param  {[type]} target     [file控件对象]
		 * @param  {[type]} tipTarget  [错误提示对象]
		 * @param  {[type]} form       [form对象]
		 * @param  {[type]} checkFlag  [是否只进行校验]
		 *
		 */
		checkUploadImg: function(target, tipTarget, form, checkFlag) {
			var val = $(target).val();
			var ruleObject = reg['img'];

			if (this.checkNotNull(val, tipTarget)) {
				if (ruleObject['regex'].test(val)) {
					if (!checkFlag) {
						var id = target[0].id;
						var loadingTarget = $("#show_" + id + "_loading");
						form.submit();
						loadingTarget.removeClass('non');
						tipTarget.removeClass('form-item-true form-item-false').addClass('uploading').text(ruleObject['loadingText']);
					} else {
						tipTarget.removeClass('form-item-false').addClass('form-item-true').html(' ');
					}
					return true;
				} else {
					this.checkValidResultTip(tipTarget, ruleObject['tip'], false);
					return false;
				}
			} else {
				return false;
			}
		},
		/**
		 * [checkMaxLength 最大长度检查]
		 * @param  {[type]} valTarget  [txt对象]
		 * @param  {[type]} val        [txt]
		 * @param  {[type]} ruleObject [验证数据对象]
		 * @param  {[type]} callbackFun  [回调方法]
		 * @return {[type]} 校验结果   [true/false]
		 */
		checkMaxLength: function(valTarget, val, ruleObject, callbackFun,descTarget) {
			val = $.trim(val || valTarget.val());
			var descEl = $("#" + valTarget[0].id + "_desc");

			if(descTarget){
				descEl=descTarget;
			}else{
				descEl = $("#" + valTarget[0].id + "_desc");
			}
			// 检查是否为空 
			var checkFlag = true;
			// 可以为空、或是不为空
			if(ruleObject['isNullFlag']&&!val){
				this.checkValidResultTip(descEl, ruleObject['oldTip'], true, true);
				return true;
			}

			checkFlag=this.checkNotNull(val, descEl);
			
			if (!checkFlag) {
				return false;
			}
			// 检查特殊字符
			checkFlag = this.checkSpecialWord(val, descEl);
			if (!checkFlag) {
				return false;
			}
			var length = val.length;
			if (length > ruleObject['maxLength']) {
				this.checkValidResultTip(descEl, ruleObject['tip'], false);
				return false;
			} else {
				//回调函数
				if (typeof callbackFun == 'function') {
					this.checkValidResultTip(descEl, "",true, true);
					return callbackFun(val, descEl);
				} else {
					this.checkValidResultTip(descEl, "", true);
				}
				return true;

			}
		},
		/**
		 * [checkMaxLength 最大长度检查]
		 * @param  {[type]} valTarget  [txt对象或是tipTarget id]
		 * @param  {[type]} val        [txt]
		 * @param  {[type]} ruleObject [验证数据对象]
		 * @param  {[type]} isNullFlag  [是否可以为空]
		 * @param  {[type]} descTarget  [自定义提示对象]
		 * @return {[type]} 校验结果   [true/false]
		 */
		regexCheckValue: function(valTarget, val, ruleObject, isNullFlag, descTarget) {
			val = $.trim(val||valTarget.val());
			var descEl ;
			if(descTarget){
				descEl=descTarget;
			}else{
				if(typeof valTarget =='string'){
					//非对象，传错误提示的对象id
					descEl=$("#"+valTarget);
				}else{
					descEl = $("#" + valTarget[0].id + "_desc")
				}
			}
			isNullFlag = (typeof(isNullFlag) == 'undefined' ? false : isNullFlag);

			//允许为空
			if ((isNullFlag ||(typeof  ruleObject['isNullFlag']!='undefined'))&& !val) {
				this.checkValidResultTip(descEl, ruleObject['oldTip'], true, true);
				return true;
			}
			// 检查是否为空 
			var checkFlag = this.checkNotNull(val, descEl);
			if (!checkFlag) {
				return false;
			}

			// 检查特殊字符
			if (!this.checkSpecialWord(val, descEl, ruleObject['isUrlFlag'])) {
				return false;
			}

			//正则判断
			if (!ruleObject.regex.test(val)) {
				this.checkValidResultTip(descEl, ruleObject.tip, false);
				return false;
			}

			val = Number(val);
			// 检查最小极限值
			if( typeof ruleObject['limitMin'] !='undefined'){
				if(val<=ruleObject['limitMin']){
					this.checkValidResultTip(descEl,ruleObject['limitMinTip'],false);
					return false;
				}
			}

			//最小值判断
			if (typeof ruleObject['min'] != 'undefined' ) {
				if(val >= ruleObject.min){
					// this.checkValidResultTip(descEl, '', true);
						
				}else{
					this.checkValidResultTip(descEl, ruleObject.numberTip, false);
					return false;	
				}
			} 
			//检验最大值
			if (typeof ruleObject['max'] !='undefined') {
				var max = Number(ruleObject['max']);
				if ( val <= max) {
					// this.checkValidResultTip(descEl, '', true);
					
				} else {
					this.checkValidResultTip(descEl, ruleObject.maxTip, false);
					return false;
				}
			}

			this.checkValidResultTip(descEl, '', true);
			return true;
		},
		/**
		 * [showErrorMesg 提示页面出现错误]
		 * @param  {[type]} valTarget  [txt对象]
		 * @param  {[type]} text        [txt，提示文字]
		 * @param  {[type]} showTime [显示时间]
		 * @param  {[type]} hideTime  [消失延时时间]
		 *
		 */
		showErrorMesg: function(target, text, showTime, hideTime) {
			target = target || $("#save_error_msg");
			showTime = showTime || 5000;
			hideTime = hideTime || 1000;
			text = text || '请核对信息是否填写正确'

			target.show().html(text).delay(showTime).fadeOut(hideTime);
		},
		/**
		 * [submitFormByPressEnter 表单按enter键提交]
		 * @param  {[type]} mainFun  [提交方法]
		 *
		 */
		submitFormByPressEnter: function(mainFun) {
			$("body").keyup(function(event) {
				var e = event || window.event || arguments.callee.caller.arguments[0];
				if (e && e.keyCode == 13) {
					if (typeof mainFun == 'function') {
						mainFun();
					}
				}
			});
		}

	};
	return valid;
});