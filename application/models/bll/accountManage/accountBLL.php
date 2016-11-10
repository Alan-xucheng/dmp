<?php
/**
 * * @Name        accountBLL.php
 * * @Note        账号业务层业务访问层
 * * @Author      jbxie
 * * @Created     2016年6月19日15:00:26
 * * @Version     v1.0.0
 * */
 
class AccountBLL extends Model
{
    /**
     * 数据访问层对象变量
     */
    protected $account_dal;
    
    /**
     * 业务层对象变量
     */
    protected $role_bll;
    
	public function __construct()
	{
		parent::__construct();
		$this->account_dal = new AccountDAL();
		$this->role_bll = new RoleBLL();
	}
	
	/**
	 * 检查账号和密码
	 * @param string $account 账号
	 * @param string $pwd 密码
	 * @return array
	 */
	public function checkAccountAndPwd($account=NULL,$pwd=NULL)
	{
	    if (empty($account) || empty($pwd)) 
	    {
	        $return_arr = UtilBLL::printReturn(FALSE,'用户名或密码不能为空');
	        return $return_arr;
	    }
	    $res = $this->getAccountInfo(NULL,$account,md5($pwd));
	    if (empty($res)) 
	    {
	        $return_arr = UtilBLL::printReturn(FALSE,'用户名或密码不正确');
	    }
	    else 
	    {
	        $return_arr = UtilBLL::printReturn(TRUE,'用户名或密码正确',$res);
	    }	   
	    return $return_arr;
	}
	
    /**
     * 获取账号信息
     * @param array $need_fields 查询字段
     * @param string $account 账号
     * @param string $pwd 密码
     * @param int $is_enabled 是否启用(0:启用，1:停用)
     * @param bool $is_need_pwd 是否需要用户密码
     * $return array
     */
	public function getAccountInfo($need_fields=NULL,$account=NULL,$pwd=NULL,$is_enabled=0,$is_need_pwd=FALSE)
	{
        // 查询条件
        $w_param = array();
        if (!empty($account)) $w_param['login_account'] = $account;
        if (!empty($pwd)) $w_param['login_pwd'] = $pwd;
        if (!is_numeric($is_enabled)) $is_enabled = 0;
        $w_param['is_enabled'] = $is_enabled;
        // 查询字段
        $f_param = array();
        if (empty($need_fields)) 
        {
            $f_param = array('id','login_account','is_enabled','role_id','create_time','update_time');
            if ($is_need_pwd)
            {
                $f_param[] = 'login_pwd';
            }
        }
        else
        {
            $f_param = $need_fields;
        }
        $return_result = $this->account_dal->getAccountInfo($w_param,$f_param);
        return $return_result;
	}
	
    /**
     * 将账户信息写入服务会话
     * @param array $account_info 账户信息
     */
	public function writeAccountInfoToSession($account_info=NULL)
	{
	    // 获取账号角色信息
	    $role_info = $this->role_bll->getRoleInfoByRoleId(NULL,$account_info['role_id']);
	    // 写入session
	    $account_info['role_authority'] = $role_info[0]['role_authority'];
	    $_SESSION["account_info"] = $account_info;
	}

	/**
	 *  从会话中获取用户信息
	 *  add by zcyue 2016-6-22 15:15:00
	 */
	public function readAccountInfoFromSession()
	{
		if(!empty($_SESSION['account_info']))
		{
			return $_SESSION['account_info'];
		}else{
			return '';
		}
	}

	public function loginOut()
	{
		session_destroy();
		//返回结果
		$return_arr = UtilBLL::printReturn(true, '用户已退出');
		return $return_arr;
	}
	
}
