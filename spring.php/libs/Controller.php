<?php
/**
 * 控制器基类
 * @author jjchen
 */
class Controller extends Model
{
    public function __construct()
    {
        parent::__construct();
        Response::assign('base_url', BASE_FULLPATH);
        Response::assign('base_page', BASE_FILENAME);
        // 登陆用户信息
        Response::assign("account_info", $_SESSION["account_info"]);
        // 退出登陆请求
        Response::assign('logout', 'accountManage/login/loginOut');
    }

}
?>