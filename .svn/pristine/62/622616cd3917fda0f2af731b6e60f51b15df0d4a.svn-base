<?php
/**
 * * @Name        TagViewAjax.php
 * * @Note        标签视图数据请求处理
 * * @Author      zcyue
 * * @Created     2016年6月18日10:25:14
 * * @Version     v1.0.0
 * */

class TagViewAjax extends Controller
{
    private $tag_manage_bll;
    private $tag_view_bll;
    private $es_tag_bll;

    public function __construct()
    {
        parent::__construct();
        $this->tag_manage_bll = new TagManageBLL();
        $this->tag_view_bll = new TagViewBLL();
        $this->es_tag_bll = new EsTagBLL();
    }

    /**
     *  获取统计的标签信息
     *  add by zcyue 2016年6月30日 14:40:00
     */
    public function getStatsTag()
    {
        // 获取请求的参数
        $p_tag_id = Request::rawparams('tag_id');
        // 获取排序参数
        $sort_key = Request::rawparams("sort_key");
        $sort_direct = Request::rawparams("sort_direct");
        $page_num = Request::rawparams("page_num");

        // 是否获取有效标签
        $effective_flag = Request::rawparams('effective');

        // 初始化默认参数
        $page_size_arr = $this->config('page_size_arr');
        $page_size = $page_size_arr['default'];

        // id 不能为空
        if(empty($p_tag_id))
        {
            $result = UtilBLL::printReturn(false, '参数错误', array());
            UtilBLL::printJson($result);
        }
        // 默认第一页
        if(empty($page_num) || !is_numeric($page_num))
        {
            $page_num = 1;
        }
        // 默认按创建时间逆序排列
        if(empty($sort_key)) $sort_key = 'create_time';
        if(empty($sort_direct)) $sort_direct = 'desc';

        // 根据父标签id 获取子标签总数
//         $total_count = $this->tag_view_bll->getCountWithPTagId($p_tag_id);
        $offset = ($page_num - 1)*$page_size;
        $tag_info = $this->tag_view_bll->getPaginationTag($p_tag_id, $sort_key, $sort_direct, $page_size, $offset);

        /**
         * add by jbxie
         * 2016-9-27 11:35:57
         */
        $tag_info_total = $this->tag_view_bll->getPaginationTag($p_tag_id, $sort_key, $sort_direct, 0, 0);
        
        // 获取标签用户数
        $user_count = $this->es_tag_bll->countUserByTagId($p_tag_id, intval(TagConstant::LEVEL_TWO));
        //$user_count = $this->countUserByTagId($p_tag_id);
        $total_user_count = $this->es_tag_bll->countUserByTagId();
        // 计算比率，转换为保留两位小数的百分比
        if(empty($total_user_count))
        {
            $rate = 0;
        }else{
            $rate = $user_count*100 / $total_user_count;
            $rate = round($rate, 2);
        }

        /**
         *  如果标记为仅显示有效标签，则需要把无效的过滤掉
         */
        if($effective_flag)
        {
            $this->filterEffective($tag_info);
            $this->filterEffective($tag_info_total);
        }
        $tag_info = array_values($tag_info);
        $total_count = count($tag_info_total);
        // 封装结果
        $data = array();
        $data['page_size'] = $page_size;
        $data['current_page'] = $page_num;;
        $data['total_count'] = $total_count;
        $data['data'] = $tag_info;
        $data['rate'] = $rate;
        $result = UtilBLL::printReturn(true, '分页获取标签信息', $data);
        UtilBLL::printJson($result);
    }

    /**
     *  获取标签的统计明细
     *  add by zcyue 2016年6月30日 14:40:00
     */
    public function getTagStatsDetail()
    {
        // 获取请求的参数
        $tag_id = Request::rawparams('tag_id');
        // 标签id 不能为空
        if(empty($tag_id))
        {
            $result = UtilBLL::printReturn(false, '参数错误');
            UtilBLL::printJson($result);
        }
        // 调用业务层获取统计明细
        $stats_info = $this->tag_view_bll->getStatsDetail($tag_id);

        // 返回请求结果
        $result = UtilBLL::printReturn(true, '获取统计明细成功', $stats_info);
        UtilBLL::printJson($result);
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
    private function countUserByTagId($tag_id=null)
    {
        $relation_arr = $this->tag_manage_bll->getTagIdRelation($tag_id);
        $count = $this->es_tag_bll->countUserByTagRelation($relation_arr);
        return $count;
    }

}