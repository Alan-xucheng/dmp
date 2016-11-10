<?php
/**
 * * @Name        TagViewDAL.php
 * * @Note        标签视图数据访问层
 * * @Author      zcyue
 * * @Created     2016年7月1日11:10:42
 * * @Version     v1.0.0
 * */

class TagViewDAL extends DBBaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->database("default", TRUE);
    }

    // 默认查询的列
    private $default_field = 'tag_id, population, update_time';

    /**
     *  获取标签统计记录
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
    public function getTagStatsData($w_param=array(), $f_param='', $order="", $direct="", $group_by="", $page_size='', $offset='')
    {
        // 查询表
        $this->table_name = "tag_stats";
        // 调整默认查询列
        if(empty($f_param)) $f_param = $this->default_field;
        $result = $this->execSelectSql($w_param, $f_param,$group_by,$order,$direct,$page_size,$offset);
        return $result;
    }

    /**
     *  根据标签数组获取标签对应的最新数据
     *  add by zcyue 2016-7-2 10:10:00
     * @param $tag_id_arr array 标签id 数组
     */
    public function getLatestStatsInfo($tag_id_arr)
    {
        // 如果为空，直接返回
        if(empty($tag_id_arr)) return array();
        // 将id 数组拼接为字符串
        $tag_id_str = implode(',', $tag_id_arr);
        $sql = "SELECT a.tag_id, a.population, a.update_time
                    FROM tag_stats a, (SELECT tag_id, MAX(update_time) AS update_time FROM tag_stats GROUP BY tag_id) b
                    WHERE a.tag_id = b.tag_id AND a.update_time = b.update_time AND a.tag_id IN ($tag_id_str)";
        $result = $this->execPureSql($sql, true);
        if(empty($result)) return array();
        return $result;
    }

    /**
     *  获取标签的历史统计信息
     *  add by zcyue 2016-7-4 10:10:00
     * @param $tag_id string 标签id
     * @param $max_count int 最大记录数
     * @return array
     */
    public function getHistoryStatsInfo($tag_id, $max_count)
    {
        // 标签id &max_count 为空，直接返回
        if(empty($tag_id) || empty($max_count)) return array();
        // 设置db
        $this->db->select('tag_id, population, update_time');
        $this->db->where('tag_id', $tag_id);
        $this->db->order_by('update_time', 'desc');
        $this->db->limit($max_count, 0);
        $query = $this->db->get('tag_stats');
        $result =array();
        if($query && $query->result_array())
        {
            foreach ($query->result_array() as $row)
            {
                $result[] =$row;
            }
        }
        return $result ;
    }

    /**
     *  新增标签统计记录
     *  add by zcyue 2016-6-24 10:10:00
     * @param $f_param
     * @return bool
     */
    public function insertTagStats($f_param)
    {
        $this->table_name = 'tag_stats';
        $result = $this->execInsertSql($f_param);
        return $result;
    }

    /**
     *  批量新增标签统计数据
     *  add by zcyue 2016-7-4 17:10:00
     * @param $f_param_arr array
     * @return bool
     */
    public function insertTagStatsBatch($f_param_arr)
    {
        $result = $this->db->insert_batch('tag_stats', $f_param_arr);
        return $result;
    }

    /**
     *  更新标签统计记录
     *  add by zcyue 2016-6-24 10:10:00
     * @param $w_param 条件
     * @param $f_param 更新信息
     * @return bool
     */
    public function updateTagStats($w_param, $f_param)
    {
        $this->table_name = 'tag_stats';
        $result = $this->execUpdateSql($w_param, $f_param, true);
        return $result;
    }

    /**
     *  删除标签统计记录
     *  add by zcyue 2016-6-24 10:45:00
     * @param $w_param
     * @return bool
     */
    public function deleteTagStats($w_param)
    {
        $this->table_name = 'tag_stats';
        $result = $this->execDeleteSql($w_param);
        return $result;
    }


}