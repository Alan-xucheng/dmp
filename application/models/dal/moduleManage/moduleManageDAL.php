<?php
/**
 * * @Name        ModuleManageDAL.php
 * * @Note        系统模块数据访问层
 * * @Author      zcyue
 * * @Created     2016年6月22日16:10:42
 * * @Version     v1.0.0
 * */

class ModuleManageDAL extends DBBaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->database("default", TRUE);
    }

    /**
     *  获取模块信息
     *  add by zcyue 2016-6-22 13:00:00
     * @param array $w_param 查询条件
     * @param array $f_param 查询字段
     * @param string $order  排序字段
     * @param string $direct 排序方向
     * @param string $group_by   分组字段
     * @param string $page_size  每页记录数
     * @param string $offset   偏移量
     */
    public function getList($w_param=array(), $f_param='*', $order="", $direct="", $group_by="", $page_size='', $offset='')
    {
        // 查询表
        $this->table_name = "conf_module_info";
        return  $this->execSelectSql($w_param, $f_param,$group_by,$order,$direct,$page_size,$offset);
    }

}