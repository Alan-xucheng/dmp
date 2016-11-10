<?php
/**
 * * @Name        TagManageDAL.php
 * * @Note        标签管理数据访问层
 * * @Author      zcyue
 * * @Created     2016年6月22日16:10:42
 * * @Version     v1.0.0
 * */

class TagManageDAL extends DBBaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->database("default", TRUE);
    }

    // 默认查询的列
    private $default_field = 'tag_id, tag_name, parent_id, tag_description, update_granularity, update_span';

    /**
     *  获取标签信息
     *  add by zcyue 2016-6-22 13:20:00
     * @param array $w_param
     * @param string $f_param
     * @param string $order
     * @param string $direct
     * @param string $group_by
     * @param string $page_size
     * @param string $offset
     * @return array
     */
    public function getTagList($w_param=array(), $f_param='', $order="", $direct="", $group_by="", $page_size='', $offset='')
    {
        // 查询表
        $this->table_name = "tag_set";
        if(empty($w_param['is_act'])) $w_param['is_act'] = 0;
        // 调整默认查询列
        if(empty($f_param)) $f_param = $this->default_field;
        $result = $this->execSelectSql($w_param, $f_param,$group_by,$order,$direct,$page_size,$offset);
        return $result;
    }

    /**
     *  获取标签信息，包括逻辑删除的标签
     *  add by zcyue 2016-6-22 13:20:00
     */
    public function getTagListWithDeleted($w_param=array(), $f_param='', $order="", $direct="", $group_by="", $page_size='', $offset='')
    {
        // 查询表
        $this->table_name = "tag_set";
        // 调整默认查询列
        if(empty($f_param)) $f_param = $this->default_field;
        $result = $this->execSelectSql($w_param, $f_param,$group_by,$order,$direct,$page_size,$offset);
        return $result;
    }

    /**
     *  获取标签记录数
     *  add by zcyue 2016-6-22 13:20:00
     * @param array $w_param
     * @return array
     */
    public function getTagCount($w_param=array())
    {
        $this->table_name = "tag_set";
        $f_param = 'count(*) as count';
        if(empty($w_param['is_act'])) $w_param['is_act'] = 0;
        $result = $this->execSelectSql($w_param, $f_param);
        $count = $result[0]['count'];
        $count = intval($count);
        return $count;
    }

    /**
     *  新增标签记录
     *  add by zcyue 2016-6-24 10:10:00
     * @param $f_param
     * @return bool
     */
    public function insertTag($f_param)
    {
        $this->table_name = 'tag_set';
        $result = $this->execInsertSql($f_param);
        return $result;
    }

    /**
     *  更新标签
     *  add by zcyue 2016-6-24 10:10:00
     * @param $w_param 条件
     * @param $f_param 更新信息
     * @return bool
     */
    public function updateTag($w_param, $f_param)
    {
        $this->table_name = 'tag_set';
        $result = $this->execUpdateSql($w_param, $f_param, true);
        return $result;
    }

    /**
     *  删除标签
     *  add by zcyue 2016-6-24 10:45:00
     * @param $w_param
     * @return bool
     */
    public function deleteTag($w_param)
    {
        $this->table_name = 'tag_set';
        //$result = $this->execDeleteSql($w_param);
        $f_param = array();
        $f_param['is_act'] = 1;
        $result = $this->execUpdateSql($w_param, $f_param);
        return $result;
    }

    /**
     *  根据指定标签id 区间来获取标签id
     *  add by zcyue 2016-7-20 14:28:00
     * @param $min_id  int 起始标签id
     * @param int $max_id int 终止标签id (结果中不包含该id)
     * @return array|bool
     */
    public function getTagByIdRegion($min_id, $max_id=0)
    {
        if(empty($min_id) || !is_numeric($min_id))
        {
            return array();
        }
        $sql = "select convert(tag_id,signed) as id from tag_set where convert(tag_id,signed) >= ".$min_id;
        if(!empty($max_id) && is_numeric($max_id))
        {
            $sql .= " and convert(tag_id,signed) <".$max_id;
        }
        $result = $this->execPureSql($sql, true);
        return $result;
    }


}