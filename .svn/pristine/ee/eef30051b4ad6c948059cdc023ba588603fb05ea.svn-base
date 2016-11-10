<?php
/**
 * * @Name        TagViewBLL.php
 * * @Note        标签视图业务访问层
 * * @Author      zcyue
 * * @Created     2016年7月1日15:00:26
 * * @Version     v1.0.0
 * */

class TagViewBLL extends Model
{
    private $tag_manage_dal;
    private $tag_view_dal;

    public function __construct()
    {
        parent::__construct();
        $this->tag_manage_dal = new TagManageDAL();
        $this->tag_view_dal = new TagViewDAL();
    }

    /**
     *  批量插入标签统计数据
     */
    public function insertTagStatsBatch($f_param_arr)
    {
        if(empty($f_param_arr))
        {
            return -1;
        }
        $result = $this->tag_view_dal->insertTagStatsBatch($f_param_arr);
        // 收集操作日期信息
        $module_sign = 'addTagStats';
        $operate_type = 'ADD';
        $log_conent = array(
            'result'=>array(
                'msg'=>$result ? '新增统计标签成功' : '新增统计标签失败'
            ),
            'add_batch_param'=>array('f_param_arr'=>$f_param_arr)
        );
        // 记录日志
        $tips = ($result===false ? '失败' : '成功');
        UtilBLL::saveLog($module_sign,$operate_type,json_encode($log_conent),$tips);
        return $result;
    }

    /**
     *  更新标签统计数据
     */
    public function updateTagStats($w_param, $f_param)
    {
        if(empty($f_param) || empty($w_param))
        {
            return -1;
        }
        $result = $this->tag_view_dal->updateTagStats($w_param, $f_param);
        // 收集操作日期信息
        $module_sign = 'updateTagStats';
        $operate_type = 'UPDATE';
        $log_conent = array(
            'result'=>array(
                'msg'=>$result ? '更新统计标签成功' : '更新统计标签失败'
            ),
            'update_param'=>array('w_param'=>$w_param, 'f_param'=>$f_param)
        );
        // 记录日志
        $tips = ($result===false ? '失败' : '成功');
        UtilBLL::saveLog($module_sign,$operate_type,json_encode($log_conent),$tips);
        return $result;
    }

    /**
     *  批量删除标签统计数据
     */
    public function deleteTagStatsBatch($w_param)
    {
        if(empty($w_param))
        {
            return -1;
        }
        $result = $this->tag_view_dal->deleteTagStats($w_param);
        // 收集操作日期信息
        $module_sign = 'deleteTagStats';
        $operate_type = 'DELETE';
        $log_conent = array(
            'result'=>array(
                'msg'=>$result ? '删除统计标签成功' : '删除统计标签失败'
            ),
            'delete_param'=>array('w_param'=>$w_param)
        );
        // 记录日志
        $tips = ($result===false ? '失败' : '成功');
        UtilBLL::saveLog($module_sign,$operate_type,json_encode($log_conent),$tips);
        return $result;
    }

    /**
     *  获取父标签id 的子标签
     * @param $p_tag_id
     */
    public function getCountWithPTagId($p_tag_id)
    {
        $w_param = array();
        $w_param['parent_id'] = $p_tag_id;
        // 获取标签的子标签数据集
        $child_tag_count = $this->tag_manage_dal->getTagCount($w_param);
        return $child_tag_count;
    }

    /**
     *  获取标签统计信息
     * @param $w_param
     */
    public function getStatsTagInfo($w_param)
    {
        if(empty($w_param))
        {
            return array();
        }
        // 获取标签的数据集
        $result = $this->tag_view_dal->getTagStatsData($w_param);
        return $result;
    }

    /**
     *  分页获取标签
     *  add by zcyue 2016-6-27 13:00:00
     * @param $p_tag_id
     * @param $sort_key
     * @param $sort_direct
     * @param $page_num
     * @return array
     */
    public function getPaginationTag($p_tag_id, $sort_key, $sort_direct, $page_size, $offset)
    {
        $w_param = array();
        $w_param['parent_id'] = $p_tag_id;
        // 获取标签的子标签数据集
        $child_tag_info = $this->tag_manage_dal->getTagList($w_param, null, $sort_key, $sort_direct, '', $page_size, $offset);
        // 转换db 中的枚举数据
        $this->tansferAndJoinStatsTagInfo($child_tag_info);
        return $child_tag_info;
    }

    /**
     *  获取统计标签
     * @param $tag_id string 标签id
     * @param $depth int 深度
     */
    public function getChildTag($tag_id, $depth)
    {
        // 递归的结束条件 标签id 为空，或者level 为0
        if(empty($tag_id) || $depth==0) return array();
        $w_param = array();
        $w_param['parent_id'] = $tag_id;
        // 获取标签的子标签数据集
        $child_tag_info = $this->tag_manage_dal->getTagList($w_param);
        if(!empty($child_tag_info) && count($child_tag_info)>0)
        {
            // 转换db 中的枚举数据
            $this->tansferAndJoinStatsTagInfo($child_tag_info);
            // 子标签数据集不为空，遍历递归获取孙子标签
            foreach($child_tag_info as $index=>$child_tag)
            {
                $child_tag_id = $child_tag['tag_id'];
                // 递归获取单个子标签的子标签，需要level 减1
                $result = $this->getChildTag($child_tag_id, $depth-1);
                if(!empty($result)) $child_tag_info[$index]['child'] = $result;
            }
        }else{
            // 没有子标签
            return array();
        }
        // 返回子标签集
        return $child_tag_info;
    }

    /**
     *  获取标签统计明细，不足补零
     * @param $tag_id string 标签id
     * @param $detail_count int 历史记录数
     */
    public function getStatsDetail($tag_id, $detail_count=null)
    {
        // 设置默认值
        if(empty($detail_count))
        {
            $detail_count = intval(TagConstant::STATS_DETAIL_COUNT);
        }
        // 获取标签更新粒度
        $w_param = array();
        $w_param['tag_id'] = $tag_id;
        $tag_info = $this->tag_manage_dal->getTagList($w_param, 'tag_id, update_granularity');
        $update_granularity = $tag_info[0]['update_granularity'];

        // 定义时间游标，与标签更新粒度结合使用
        $date_cursor = date('Y-m-d');

        // 调用数据访问层获取标签统计信息记录
        $stats_info = $this->tag_view_dal->getHistoryStatsInfo($tag_id, $detail_count);
        // 结果为空，则用初始化为空数组
        if(empty($stats_info)) $stats_info = array();

        // 封装固定条数的格式数据
        $result = array();
        for($i=0; $i<count($stats_info); $i++)
        {
            $population = $stats_info[$i]['population'];
            $update_time = date('Y-m-d', strtotime($stats_info[$i]['update_time']));
            $result[$update_time] = $population;
            $date_cursor = $update_time;
        }
        for($i=count($stats_info); $i<$detail_count; $i++)
        {
            $population = 0;
            if($update_granularity == TagConstant::GRANULARITY_DAY)
            {
                $update_time = DateHandler::getPriorDate($date_cursor, 1);
            }elseif($update_granularity == TagConstant::GRANULARITY_WEEK){
                $update_time = DateHandler::getPriorDate($date_cursor, 7);
            }elseif($update_granularity == TagConstant::GRANULARITY_MONTH){
                $update_time = DateHandler::getPriorMonth($date_cursor, 1);
            }else{
                $update_time = '0000-00-00';
            }
            $date_cursor = $update_time;
            // 补0 处理
            // $result[$update_time] = $population;
        }
        // 将结果集逆序
        $result = array_reverse($result);
        return $result;
    }

    /**
     *  查询匹配三级标签结果及父标签
     * @param $key
     */
    public function fuzzySearchTagDetail($key)
    {
        // 获取根据名称匹配到的标签
        $w_param = array();
        $w_param['tag_name_alt'] = $key;
        $w_param['level'] = TagConstant::LEVEL_THREE;
        $tag_info_a = $this->tag_manage_dal->getTagList($w_param);
        // 获取根据id 匹配到的标签
        $w_param = array();
        $w_param['tag_id_alt'] = $key;
        $w_param['level'] = TagConstant::LEVEL_THREE;
        $tag_info_b = $this->tag_manage_dal->getTagList($w_param);
        // 获取根据标签描述 匹配到的标签
        $w_param = array();
        $w_param['tag_description_alt'] = $key;
        $w_param['level'] = TagConstant::LEVEL_THREE;
        $tag_info_c = $this->tag_manage_dal->getTagList($w_param);
        // 合并结果
        $tag_info = $tag_info_a + $tag_info_b + $tag_info_c;
        // 转换db 中的枚举数据
        $this->tansferAndJoinStatsTagInfo($tag_info);
        // 没有数据直接返回
        if(empty($tag_info) || count($tag_info)<=0)
        {
            $result = UtilBLL::printReturn(false, '没有匹配的标签');
            return $result;
        }
        // 获取对应的父标签id 集合
        $p_tag_id_arr = array();
        foreach ( $tag_info as $index=>$tag )
        {
            $p_tag_id_arr[] = $tag['parent_id'];
        }
        // 查询父标签
        $w_param = array();
        $w_param['tag_id'] = $p_tag_id_arr;
        $p_tag_info = $this->tag_manage_dal->getTagList($w_param);

        // 组合父子标签
        $p_tag_info = UtilBLL::handleArrToFieldVal($p_tag_info, 'tag_id');
        foreach($tag_info as $tag)
        {
            $p_tag_id = $tag['parent_id'];
            if(!empty($p_tag_info[$p_tag_id]))
            {
                if(empty($p_tag_info[$p_tag_id]['child']))
                {
                    $p_tag_info[$p_tag_id]['child'] = array();
                }
                array_push($p_tag_info[$p_tag_id]['child'], $tag);
            }
        }
        // 转换为索引数组
        $data = array();
        foreach($p_tag_info as $tag)
        {
            $data[] = $tag;
        }
        $result = UtilBLL::printReturn(true, '获取匹配标签成功', $data);
        return $result;
    }

    /**
     *  模糊匹配搜索下拉框的标签
     * @param $key
     */
    public function fuzzySearchCombo($key)
    {
        // 查询列
        $f_param = 'tag_id as id, tag_name as name';
        // 获取根据名称匹配到的标签
        $w_param = array();
        $w_param['tag_name_alt'] = $key;
        $w_param['level'] = TagConstant::LEVEL_THREE;
        $tag_info_a = $this->tag_manage_dal->getTagList($w_param, $f_param);
        // 获取根据id 匹配到的标签
        $w_param = array();
        $w_param['tag_id_alt'] = $key;
        $w_param['level'] = TagConstant::LEVEL_THREE;
        $tag_info_b = $this->tag_manage_dal->getTagList($w_param, $f_param);
        // 获取根据标签描述 匹配到的标签
        $w_param = array();
        $w_param['tag_description_alt'] = $key;
        $w_param['level'] = TagConstant::LEVEL_THREE;
        $tag_info_c = $this->tag_manage_dal->getTagList($w_param, $f_param);
        // 合并结果
        $tag_info = $tag_info_a + $tag_info_b + $tag_info_c;
        if(!empty($tag_info) && count($tag_info)>0)
        {
            $result = UtilBLL::printReturn(true, '获取匹配标签成功', $tag_info);
        }else{
            $result = UtilBLL::printReturn(false, '没有匹配的标签');
        }
        return $result;
    }

    /**
     *  转换标签数据并且关联统计数据，为页面需要的结构
     * @param $tag_arr array 引用类型
     */
    private function tansferAndJoinStatsTagInfo(&$tag_arr)
    {
        if(!empty($tag_arr))
        {
            // 获取对应的标签id 集合
            $tag_id_arr = ArrayHelper::getSetFromCollection($tag_arr, 'tag_id');
            // 获取标签的统计数据
            $tag_stats_info = $this->tag_view_dal->getLatestStatsInfo($tag_id_arr);
            $tag_stats_info = UtilBLL::handleArrToFieldVal($tag_stats_info, 'tag_id');
            // 读取映射配置
            $tag_granularity_conf = $this->config('update_granularity', 'tag.config');
            foreach($tag_arr as $key=>$tag)
            {
                // 解析用户数
                $tag_id = $tag['tag_id'];
                if(!empty($tag_stats_info[$tag_id]) && !empty($tag_stats_info[$tag_id]['population']))
                {
                    $tag_arr[$key]['population'] = $tag_stats_info[$tag_id]['population'];
                    $update_time = $tag_stats_info[$tag_id]['update_time'];
                    $tag_arr[$key]['update_time'] = date('Y-m-d', strtotime($update_time));
                }else{
                    $tag_arr[$key]['population'] = 0;
                    $tag_arr[$key]['update_time'] = '';
                }
                // 转换更新粒度为文字
                if(!empty($tag['update_granularity']))
                {
                    $tag_arr[$key]['update_granularity'] = $tag_granularity_conf[$tag['update_granularity']];
                }
            }
        }
    }

}
