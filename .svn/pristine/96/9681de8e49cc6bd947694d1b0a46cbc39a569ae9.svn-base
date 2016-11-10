<?php
/**
 * * @Name        systemLogDAL.php
 * * @Note        系统日志数据访问层
 * * @Author      jbxie
 * * @Created     2016年6月19日16:10:42
 * * @Version     v1.0.0
 * */

class SystemLogDAL extends DBBaseModel
{
	public function __construct()
	{
		parent::__construct();
		$this->db = $this->database("default",TRUE);
	}
	
	/***
	 * 添加系统日志
	 * @param array $f_param 记录参数
	 * @return bool
	 */
	public function addSystemLog($f_param=NULL) 
	{
	    if (empty($f_param)) 
	    {
	        return false;
	    }
	    $this->table_name = 'operate_system_log';
	    return $this->execInsertSql($f_param);
	}
	
}