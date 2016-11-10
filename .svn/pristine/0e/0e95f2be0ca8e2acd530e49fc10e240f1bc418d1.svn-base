<?php
/**
 * * @Name        login.php
 * * @Note        登录控制层
 * * @Author      jbxie
 * * @Created     2016年6月18日10:25:14
 * * @Version     v1.0.0
 * */

class Login extends Controller
{
    /**
     * 业务层对象变量
     */
    protected $account_bll;
    
	public function __construct()
	{
		parent::__construct();
		$this->account_bll = new AccountBLL();
	}
	
	/**
	 * 登录首页
	 */
	public function index()
	{
	    $this->loginLimit();
	    // 登录参数
	    $account = trim(Request::rawform('account'));
	    $pwd = trim(Request::rawform('pwd'));
	    $verify_code = trim(Request::rawform('verify_code'));
	    $flag = trim(Request::rawform('flag')); // 是否提交数据 1：提交数据 0：不是提交数据（即进入登录页面）
	    if (!$flag) 
	    {
	        Response::display("account_manage/login.html");
	        exit;
	    }
	    if (empty($account))
	    {
	        $_SESSION["LOGIN_FAILURE_NUM"] ++;
	        UtilBLL::printJson(array("flag"=>FALSE,"msg"=>"用户名为空","data"=>""));
	    }
	    if (empty($pwd))
	    {
	        $_SESSION["LOGIN_FAILURE_NUM"] ++;
	        UtilBLL::printJson(array("flag"=>FALSE,"msg"=>"密码为空","data"=>""));
	    }
	    /**
	     * add by jbxie 添加验证码
	     * 2016-8-31 20:55:41
	     */
	    if ( empty($verify_code) )
	    {
	        $_SESSION["LOGIN_FAILURE_NUM"] ++;
	        UtilBLL::printJson(array("flag"=>FALSE,"msg"=>"验证码不能为空","data"=>"verify_code"));
	    }
	    if ( $verify_code != $_SESSION["VERIFY_CODE"] )
	    {
	        $_SESSION["LOGIN_FAILURE_NUM"] ++;
	        UtilBLL::printJson(array("flag"=>FALSE,"msg"=>"验证码错误","data"=>"verify_code"));
	    }
	    
		$pwd = RSA::decode($pwd);
	    $check_result = $this->account_bll->checkAccountAndPwd($account,$pwd);
	    // 用户合法写入数据
	    if($check_result['flag']) 
	    {
	        $this->account_bll->writeAccountInfoToSession($check_result['data'][0]);
			$check_result['data'] = array();
			$domain = $this->config('domain');
			$check_result['data']['redirect_url'] = $domain.'/welcome';
			UtilBLL::printJson($check_result);
	    }
	    else 
	    {
	        $_SESSION["LOGIN_FAILURE_NUM"] ++;
	    }
	    // 清除返回用户数据
	    $check_result['data'] = array();
	    UtilBLL::printJson($check_result);
	}

	/**
	 *  退出登录
	 */
	public function loginOut()
	{
		$this->account_bll->loginOut();
		redirect('../login');
	}
	
	// 登录限制
	private function loginLimit()
	{
	    // 判断登录失败的时间点
	    if ( isset($_SESSION["LOGIN_FAILURE_TIME"]) )
	    {
	        $now_time = time();
	        if (($now_time-$_SESSION["LOGIN_FAILURE_TIME"]) < 30*60 )
	        {
	            UtilBLL::printJson(array("flag"=>FALSE,"msg"=>"登录失败超过了6次，请30分后重试","data"=>""));
	        }
	        else
	        {
	            unset($_SESSION["LOGIN_FAILURE_NUM"]);
	            unset($_SESSION["LOGIN_FAILURE_TIME"]);
	        }
	    }
	    // 判断登录次数
	    if ( isset($_SESSION["LOGIN_FAILURE_NUM"]) &&  $_SESSION["LOGIN_FAILURE_NUM"] >= 6 )
	    {
	        // 记录登录失败超过6次时的时间
	        if (!isset($_SESSION["LOGIN_FAILURE_TIME"]))
	        {
	            $_SESSION["LOGIN_FAILURE_TIME"] = time();
	        }
	        UtilBLL::printJson(array("flag"=>FALSE,"msg"=>"登录失败超过了6次，请30分后重试","data"=>""));
	    }
	}
	

}