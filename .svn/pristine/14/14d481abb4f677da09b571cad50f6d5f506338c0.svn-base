<?php
/**
 * * @Name        ModuleManageBLL.php
 * * @Note        系统模块业务访问层
 * * @Author      zcyue
 * * @Created     2016年6月22日15:00:26
 * * @Version     v1.0.0
 * */

class ModuleManageBLL extends Model
{

    private $model_manage_dal;

    public function __construct()
    {
        parent::__construct();
        $this->module_manage_dal = new ModuleManageDAL();
    }

    public function getModuleInfo($w_param, $f_param='*', $order="", $direct="", $group_by="", $page_size='', $offset='')
    {
        $result = $this->module_manage_dal->getList($w_param, $f_param, $order, $direct, $group_by, $page_size, $offset);
        return  $result;
    }


}
