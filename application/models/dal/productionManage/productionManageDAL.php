<?php
/**
 * * @Name        ModuleManageDAL.php
 * * @Note        标签产品数据访问层
 * * @Author      zcyue
 * * @Created     2016年6月27日 22:20:00
 * * @Version     v1.0.0
 * */

class ProductionManageDAL extends DBBaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->database("default", TRUE);
    }

    /**
     *  获取映射信息
     *  add by zcyue 2016-6-27 22:20:00
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
        $this->table_name = "product_tag_relevance";
        $result = $this->execSelectSql($w_param, $f_param,$group_by,$order,$direct,$page_size,$offset);
        return $result;
    }

    /**
     *  更新映射信息
     *  add by zcyue 2016-6-28 14:20:00
     * @param $w_param 查询条件
     * @param $s_param 设置字段
     */
    public function updateRecord($w_param, $s_param)
    {
        // 更新表
        $this->table_name = "product_tag_relevance";
        $result = $this->execUpdateSql($w_param, $s_param, true);
        return $result;
    }

}