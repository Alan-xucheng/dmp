<?php
/**
 * * @Name        CommonAjax.php
 * * @Note        共通请求处理
 * * @Author      zcyue
 * * @Created     2016年6月27日10:25:14
 * * @Version     v1.0.0
 * */

class CommonAjax extends Controller
{

    private $account_bll;
    private $tag_manage_bll;
    private $tag_view_bll;

    private $es_tag_bll;

    public function __construct()
    {
        parent::__construct();
        $this->account_bll = new AccountBLL();;
        $this->tag_manage_bll = new TagManageBLL();
        $this->tag_view_bll = new TagViewBLL();

        $this->es_tag_bll = new EsTagBLL();
    }

    /**
     * 根据关键词、搜索类型查询标签
     */
    public function fuzzySearchTagDetail()
    {
        // 获取匹配的关键字（词）
        $key = trim(Request::rawparams('keyword'));
        // 搜索类型
        $type = Request::rawparams('type');
        // 默认类型
        if(empty($type)) $type = intval(SearchType::COMMON_TAG);
        // 不同的类型，不同的处理
        switch($type)
        {
            case intval(SearchType::COMMON_TAG):
                // 获取普通的三级标签及其对应的父标签
                $result = $this->tag_manage_bll->fuzzySearchTagDetail($key);
                break;
            case intval(SearchType::STATS_TAG):
                // 获取标签视图的标签统计数据
                $effective_flag = trim(Request::rawparams('effective'));
                $result = $this->tag_view_bll->fuzzySearchTagDetail($key);
                $this->assembleRate($result['data']);
                if($effective_flag)
                {
                    // 过滤出有效的标签
                    $p_tag_arr = $result['data'];
                    foreach($p_tag_arr as $index=>$p_tag)
                    {
                        $child_arr = $p_tag['child'];
                        $this->filterEffective($child_arr);
                        if(empty($child_arr))
                        {
                            // 子标签为空，则父标签不展示
                            unset($p_tag_arr[$index]);
                        }else{
                            $p_tag_arr[$index]['child'] = $child_arr;
                        }
                    }
                    $result['data'] = $p_tag_arr;
                }
                break;
            case intval(SearchType::INTEGRATION_TAG):
                // TODO 获取整合的标签id&name
                $result = UtilBLL::printReturn(true, '');
                break;
            case intval(SearchType::DEVICE):
                // TODO 获取设备id&name
                $result = UtilBLL::printReturn(true, '');
                break;
            default:
                $result = UtilBLL::printReturn(false, '搜索类型不合法');
                break;
        }
        // 打印结果
        UtilBLL::printJson($result);
    }

    /**
     * 模糊匹配搜索下拉框的内容
     */
    public function fuzzySearchCombo()
    {
        // 获取匹配的关键字（词）
        $key = trim(Request::rawparams('keyword'));
        // 搜索类型
        $type = Request::rawparams('type');
        if(empty($type)) $type = intval(SearchType::COMMON_TAG);
        // 不同的类型，不同的处理
        if($type == intval(SearchType::COMMON_TAG))
        {
            // 获取普通的三级标签id&name
            $result = $this->tag_manage_bll->fuzzySearchCombo($key);
        }elseif($type == intval(SearchType::STATS_TAG)){
            // 获取标签视图的标签统计id&name
            $result = $this->tag_view_bll->fuzzySearchCombo($key);
        }elseif($type == intval(SearchType::INTEGRATION_TAG)){
            // 获取整合的标签id&name
            // TODO
            $result = UtilBLL::printReturn(true, '');
        }elseif($type == intval(SearchType::DEVICE)){
            // 获取设备id&name
            // TODO
            $result = UtilBLL::printReturn(true, '');
        }else{
            $result = UtilBLL::printReturn(false, '搜索类型不合法');
        }
        // 打印结果
        UtilBLL::printJson($result);
    }

    /**
     *  定时任务接口
     * @param $task_type int 任务类型 'D':天  'W':周  'M':月
     */
    public function execTask()
    {
        // 请求参数
        $task_type = Request::rawparams('type');
        $request_token = Request::rawparams('token');
        $exec_tag_id = Request::rawparams('tag_id');
        // 检查权限
        $token = $this->config('exec_task_token', 'tag.config');
        if($token != $request_token)
        {
            $result = UtilBLL::printReturn(false, 'token错误');
            UtilBLL::printJson($result);
        }
        // 检查类型
        if(empty($task_type) || !in_array($task_type, array(TagConstant::GRANULARITY_DAY, TagConstant::GRANULARITY_WEEK, TagConstant::GRANULARITY_MONTH)))
        {
            // 任务类型不是天、周或月
            $result = UtilBLL::printReturn(false, '任务类型错误');
            UtilBLL::printJson($result);
        }

        // 1. 根据参数的不同，获取不同的标签数据
        $task_tag = array();
        if(!empty($exec_tag_id))
        {
            // 标签id 不为空，直接统计当前标签
            $w_param = array();
            $w_param['tag_id'] = $exec_tag_id;
            $task_tag = $this->tag_manage_bll->getTagList($w_param, 'tag_id, level as tag_level, update_granularity');
            if(empty($task_tag) || count($task_tag) <= 0)
            {
                // 标签id 没有对应的标签
                $result = UtilBLL::printReturn(false, '标签id没有对应的标签');
                UtilBLL::printJson($result);
            }else{
                $tag_info = $task_tag[0];
                $update_granularity = $tag_info['update_granularity'];
                if($update_granularity !== $task_type)
                {
                    // 指定的标签更新粒度和任务类型不一致
                    $result = UtilBLL::printReturn(false, '标签更新粒度和任务类型不一致');
                    UtilBLL::printJson($result);
                }else{
                    unset($task_tag[0]['update_granularity']);
                }
            }
        }else{
            $w_param = array();
            $w_param['update_granularity'] = $task_type;
            $w_param['level'] = TagConstant::LEVEL_THREE;
            $task_tag = $this->tag_manage_bll->getTagList($w_param, 'tag_id, level as tag_level, update_granularity');
        }

        // 创建一个插入db 的时间
        switch($task_type)
        {
            case 'D':
                $create_time = date('Y-m-d');
                break;
            case 'W':
                $create_time = DateHandler::getLatestWeekDay('Thursday');
                break;
            case 'M':
                $create_time = Date("Y-m-d", strtotime(Date("Y-m-01")));
                break;
        }

        // 2. 遍历标签，调用es接口，获取标签的统计数据
        foreach($task_tag as $index=>$tag)
        {
            $tag_id = $tag['tag_id'];
            $population = $this->es_tag_bll->countUserByTagId($tag_id);
            //$population = $this->countUserByTagId($tag_id);
            // 用户数为0 的不存储
            if($population == 0)
            {
                unset($task_tag[$index]);
                continue;
            }

            $task_tag[$index]['population'] = $population;
            $task_tag[$index]['create_time'] = $create_time;

            // 判断是否已存在统计数据
            $w_param = [
                'tag_id'=>$tag_id,
                'create_time'=>$create_time
            ];
            $info = $this->tag_view_bll->getStatsTagInfo($w_param);
            if(!empty($info) && count($info)>0)
            {
                // 更新同一时间已有的统计记录
                $f_param = [
                    'population'=>$population
                ];
                $this->tag_view_bll->updateTagStats($w_param, $f_param);
                unset($task_tag[$index]);
            }
        }
        // 3. 保存统计数据到数据库中。如果$task_tag 中有多余的字段，需要在此之前去除
        $this->tag_view_bll->insertTagStatsBatch($task_tag);
        // 返回结果
        $result = UtilBLL::printReturn(true, '执行任务成功', $task_tag);
        UtilBLL::printJson($result);
    }

    /**
     *  为父标签添加标签比率
     *  add by zcyue 2016-7-4 16:00:00
     * @param $tag_arr array
     */
    private function assembleRate(&$tag_arr)
    {
        // 为父标签添加标签比率
        if(!empty($tag_arr)) {
            foreach ($tag_arr as $index => $item) {
                $tag_id = $item['tag_id'];
                // 获取标签用户数
                $user_count = $this->es_tag_bll->countUserByTagId($tag_id, intval(TagConstant::LEVEL_TWO));
                //$user_count = $this->countUserByTagId($tag_id);
                $total_user_count = $this->es_tag_bll->countUserByTagId();
                // 计算比率，转换为保留两位小数的百分比
                if(empty($total_user_count))
                {
                    $rate = 0;
                }else{
                    $rate = $user_count*100 / $total_user_count;
                    $rate = round($rate, 2);
                }
                $tag_arr[$index]['rate'] = $rate;
            }
        }
    }

    /**
     *  过滤出标签数组中的有效标签 指三级标签
     * @param $tag_arr
     */
    private function filterEffective(&$tag_arr)
    {
        foreach($tag_arr as $index=>$item)
        {
            $population = $item['population'];
            $tag_id = $item['tag_id'];
            // 判断统计信息中是不有用户
            if($population == 0)
            {
                // 调用es 接口获取最新用户数
                $user_count = $this->es_tag_bll->countUserByTagId($tag_id, intval(TagConstant::LEVEL_THREE));
                //$user_count = $this->countUserByTagId($tag_id);
                if($user_count == 0)
                {
                    // 统计信息里没有用户，实时调用接口也没有用户，则认为标签无效
                    unset($tag_arr[$index]);
                }
            }
        }
    }

    /**
     *  临时适配的获取标签用户数。以方便代码的低耦合
     *  add by zcyue 2016-7-20 15:10:00
     * @param $tag_id
     * @return int
     * @throws ErrorException
     */
    private function countUserByTagId($tag_id)
    {
        $relation_arr = $this->tag_manage_bll->getTagIdRelation($tag_id);
        $count = $this->es_tag_bll->countUserByTagRelation($relation_arr);
        return $count;
    }

}
