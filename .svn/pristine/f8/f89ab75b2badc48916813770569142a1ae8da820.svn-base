
require(['comoperate','validform','encode'],function(Comoperate,Validform){
	var urlStore={
		login:__base_url+'accountManage/login/index'//登录校验地址
	};
	var validTipTarget=$("#valid_tip");
	Validform.submitFormByPressEnter(submitForm);
	eventInitial();
	function eventInitial(){
		$("#img_yzm").click(function(){
			$(this).attr("src", __base_url+"captcha/captcha.php?" + Math.random());
		});
		$('body').on('keyup focus','.info-input-login',function(){
			validTipTarget.text('');
		}).on('click','#action_login',function(){
			submitForm();
		});
	}
	// 登录
	function submitForm(){
		$("#login_tip_layer").removeClass('non');
		var nameTarget=$("#account_name"),pwdTarget=$("#account_pwd"),verifyCodeTarget=$("#verify_code");
		var name=$.trim(nameTarget.val()),pwd=pwdTarget.val();
		var verifyCode=$.trim(verifyCodeTarget.val());
		var checkFlag=true;
		if(!name.length||!pwd.length){
			Validform.checkValidResultTip(validTipTarget,'用户名或密码不能为空',false);
			checkFlag=false;
		}else{
			checkFlag=Validform.regexCheckValue(nameTarget,name,Validform.regexStore.email,'',validTipTarget)&&checkFlag;
			if(checkFlag){
				checkFlag=Validform.checkNotNull(pwdTarget.val(),validTipTarget);
			}
		}
		if(checkFlag){
			if(!verifyCode.length){
				Validform.checkValidResultTip(validTipTarget,'验证码不能为空',false);
				checkFlag=false;
			}else{
				checkFlag=Validform.checkNotNull($.trim(verifyCodeTarget.val()),validTipTarget);
			}
		}
		if(!checkFlag){
			$("#login_tip_layer").addClass('non');
			return false;
		}

		pwd=rsaEncode(pwd);
		nameTarget.val(name);//去前后空格

		verifyCodeTarget.val(verifyCode);
		Comoperate.ajaxOperate({
			url:urlStore.login,
			data:{
				flag:1,
				account:name,
				pwd:pwd,
				verify_code:verifyCode
			},
			completeFun:function(){
				$("#login_tip_layer").addClass('non');
			},
			successFun:function(result){

				if(result&&result['flag']){
					if(result['data']){
						window.location.href=result['data']['redirect_url'];
					}
				}else{
					var errorTip=result&&result['msg']?result['msg']:'登录失败';
					Validform.checkValidResultTip(validTipTarget,errorTip,false);
				}
			},
			errorFun:function(){
				Validform.checkValidResultTip(validTipTarget,'登录失败',false);
			}
		});
	}
});