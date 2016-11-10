<?php
/**
 * * @Name        welcome.php
 * * @Note        欢迎控制层
 * * @Author      jbxie
 * * @Created     2016年6月19日14:49:16
 * * @Version     v1.0.0
 * */

class Welcome extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 登录首页
	 */
	public function index()
	{
		Response::assign('tag_manage', 'tagManage/tagManageIndex');
		Response::display("welcome.html");
		exit;
	}
	
}