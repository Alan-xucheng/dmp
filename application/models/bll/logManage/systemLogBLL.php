<?php
/**
 * * @Name        systemLogBLL.php
 * * @Note        系统日志业务层业务访问层
 * * @Author      jbxie
 * * @Created     2016年6月19日15:00:26
 * * @Version     v1.0.0
 * */
 
class SystemLogBLL extends Model
{
    /**
     * 数据访问层对象变量
     */
    protected $system_log_dal;
    
	public function __construct()
	{
		parent::__construct();
		$this->system_log_dal = new SystemLogDAL();
	}
	
	/***
	 * 添加系统日志
	 * @param array $f_param 记录参数
	 * @return bool
	 */
	public function addSystemLog($f_param) 
	{
	    return $this->system_log_dal->addSystemLog($f_param);
	}
	
}
