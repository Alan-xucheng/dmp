<?php
/**
 * * @Name        TagManageBLL.php
 * * @Note        标签管理业务访问层
 * * @Author      zcyue
 * * @Created     2016年6月22日15:00:26
 * * @Version     v1.0.0
 * */

class TagManageBLL extends Model
{

    private $tag_manage_dal;

    public function __construct()
    {
        parent::__construct();
        $this->tag_manage_dal = new TagManageDAL();
    }

    /**
     *  获取所有的一级标签
     *  add by zcyue 2016-6-22 12:20:00
     */
    public function getLevelOneTag()
    {
        $w_param = array();
        $w_param['parent_id'] = 0;
        // 获取父节点为0 的标签，即一级标签
        $result = $this->tag_manage_dal->getTagList($w_param);
        return $result;
    }

    /**
     *  获取指定标签id 的子标签
     *  add by zcyue 2016-6-23 13:30:00
     * @param $tag_id string 标签id
     * @param int $depth 子标签层级，默认为1 表示获取子标签，2 表示获取子孙标签，依次类推
     * @return array
     */
    public function getChildTag($tag_id, $depth=1)
    {
        // 递归的结束条件 标签id 为空，或者level 为0
        if(empty($tag_id) || $depth==0) return array();
        $w_param = array();
        $w_param['parent_id'] = $tag_id;
        // 获取标签的子标签数据集
        $child_tag_info = $this->tag_manage_dal->getTagList($w_param);
        //var_dump($child_tag_info);exit;
        if(!empty($child_tag_info) && count($child_tag_info)>0)
        {
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
     *  获取所有标签
     *  add by zcyue 2016-6-22 12:20:00
     */
    public function getAllTag()
    {
        // 获取一级标签
        $level_one_tag = $this->getLevelOneTag();
        if(!empty($level_one_tag))
        {
            foreach($level_one_tag as $index=>$tag)
            {
                $tag_id = $tag['tag_id'];
                // 获取所有子标签（目前项目全部标签有三级）
                $child_tag = $this->getChildTag($tag_id, 2);
                $level_one_tag[$index]['child'] = $child_tag;
            }
        }
        return $level_one_tag;
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
        $this->transferTagInfo($child_tag_info);
        return $child_tag_info;
    }

    /**
     *  获取标签列表
     *  add by zcyue 2016-7-4 17:00:00
     * @param $w_param
     * @param $f_param
     * @return array
     */
    public function getTagList($w_param, $f_param='')
    {
        $reslut = $this->tag_manage_dal->getTagList($w_param, $f_param);
        return $reslut;
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
        $this->transferTagInfo($tag_info);
        // 没有数据直接返回
        if(empty($tag_info) || count($tag_info)<=0)
        {
            $result = UtilBLL::printReturn(false, '没有匹配的标签');
            return $result;
        }
        //var_dump($tag_info);exit;
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
     *  新增标签
     *  add by zcyue 2016-6-23 16:00:00
     * @param $tag_info 标签信息
     * @return array
     */
    public function addTag($tag_info)
    {
        // 检查参数中的标签信息是否有效
        $check_result = $this->checkTagInfo($tag_info);
        if(!$check_result['flag'])
        {
            // 数据不满足条件
            return $check_result;
        }
        // 存储标签到db
        $result = $this->insertOrUpdateTag($tag_info);
        return $result;
    }

    /**
     *  更新标签
     *  add by zcyue 2016-6-23 16:00:00
     * @param $tag_id 要更新的标签id
     * @param $tag_info 标签信息
     * @return array
     */
    public function updateTag($tag_id, $tag_info)
    {
        // 检查参数中的标签信息是否有效
        $check_result = $this->checkTagInfo($tag_info);
        if(!$check_result['flag'])
        {
            // 数据不满足条件
            return $check_result;
        }
        // 更新标签，必须数据库中有对应标签的id，并且更新的标签id 和更新信息中的要一致
        if($tag_id != $tag_info['tag_id'] || $check_result['data'] != intval(TagConstant::EXISTED))
        {
            $result = UtilBLL::printReturn(false, '标签信息错误');
            return $result;
        }
        // 存储标签到db
        $result = $this->insertOrUpdateTag($tag_info);
        return $result;
    }

    /**
     *  删除标签
     *  add by zcyue 2016-6-23 16:00:00
     * @param $tag_id 标签id
     * @return array
     */
    public function deleteTag($tag_id)
    {
        $w_param = array();
        $w_param['tag_id'] = $tag_id;
        $tag = $this->tag_manage_dal->getTagList($w_param);
        if(!empty($tag) && count($tag)>0)
        {
            $tag_id_arr = array();
            $this->assembleCascadingId($tag_id, $tag_id_arr);
            $w_param['tag_id'] = $tag_id_arr;
            $bool = $this->tag_manage_dal->deleteTag($w_param);
            $msg = $bool ? '删除标签成功' : '删除标签失败';
            $result = UtilBLL::printReturn($bool, $msg);

            // 收集操作日期信息
            $module_sign = 'deleteTag';
            $operate_type = 'DELETE';
            $log_conent = array(
                'result'=>array(
                    'msg'=>$msg
                ),
                'delete_param'=>array('w_param'=>$w_param)
            );
            // 记录日志
            $tips = ($bool===false?'失败':'成功');
            UtilBLL::saveLog($module_sign,$operate_type,json_encode($log_conent),$tips);
        }else{
            $result = UtilBLL::printReturn(true, '标签不存在');
        }
        return $result;
    }

    /**
     *  级联获取标签id 及其子孙标签id
     * @param $tag_id
     * @param $tag_id_arr 引用类型，保存对应的标签id
     */
    private function assembleCascadingId($tag_id, &$tag_id_arr)
    {
        // 处理子标签
        $child_tag_arr = $this->getChildTag($tag_id);
        if(!empty($child_tag_arr))
        {
            foreach($child_tag_arr as $child_tag)
            {
                $child_tag_id = $child_tag['tag_id'];
                $this->assembleCascadingId($child_tag_id, $tag_id_arr);
            }
        }
        // 处理当前标签
        array_push($tag_id_arr, $tag_id);
    }

    /**
     *  检查标签是否满足新增（更新）的要求
     *  add by zcyue 2016-6-23 16:00:00
     * @param $tag_info 标签信息
     * @return array 返回数据中，当为true 时，有两种状态，需特别注意
     */
    private function checkTagInfo($tag_info)
    {
        // 获取标签名称
        $tag_name = $tag_info['tag_name'];
        // 获取标签描述
        $tag_desc = $tag_info['tag_desc'];
        if(empty($tag_desc)) $tag_desc = '';
        // 获取父标签
        $parent_id = $tag_info['parent_id'];
        if(empty($parent_id)) $parent_id = 0;
        // 获取标签更新粒度
        $update_granularity = $tag_info['update_granularity'];
        if(empty($update_granularity)) $update_granularity = TagConstant::GRANULARITY_DEFAULT;
        // 获取标签更新跨度
        $update_span = $tag_info['update_span'];
        if(empty($update_span)) $update_span = '';

        // 检查必须项
        if(empty($tag_name))
        {
            $result = UtilBLL::printReturn(false, '标签名称不能为空', TagConstant::PARAM_ERROR);
            return $result;
        }
        // 检查父标签是否存在
        if(!empty($parent_id) && $parent_id != 0)
        {
            $w_param = array();
            $w_param['tag_id'] = $parent_id;
            $parent_tag = $this->tag_manage_dal->getTagList($w_param);
            if(empty($parent_tag))
            {
                $result = UtilBLL::printReturn(false, '没有对应的父标签', TagConstant::PARAM_ERROR);
                return $result;
            }
        }
        // 检查标签粒度&跨度
        $granularity_arr = array(TagConstant::GRANULARITY_DAY, TagConstant::GRANULARITY_WEEK, TagConstant::GRANULARITY_MONTH);
        if(!empty($update_granularity) && !in_array($update_granularity, $granularity_arr))
        {
            $result = UtilBLL::printReturn(false, '标签的更新粒度不合法', TagConstant::PARAM_ERROR);
            return $result;
        }
        if(in_array($update_granularity, $granularity_arr) && empty($update_span))
        {
            $result = UtilBLL::printReturn(false, '标签的更新跨度不合法', TagConstant::PARAM_ERROR);
            return $result;
        }

        if(!empty($tag_info['tag_id']))
        {
            // 判断是否已有对应id 的标签
            $w_param = array();
            $w_param['tag_id'] = $tag_info['tag_id'];
            $tag = $this->tag_manage_dal->getTagList($w_param);
            if(!empty($tag) && count($tag)>0)
            {
                // 状态标记为已存在
                $result = UtilBLL::printReturn(true, '更新标签存在', TagConstant::EXISTED);
            }else{
                // 状态标记为不存在
                $result = UtilBLL::printReturn(false, '更新标签不存在', TagConstant::NOT_EXISTED);
            }
        }else{
            // 状态标记为有效
            $result = UtilBLL::printReturn(true, '新增标签信息合法', TagConstant::IS_VALIDE);
        }
        // 返回结果
        return $result;
    }

    /**
     *  插入或更新标签记录
     *  add by zcyue 2016-6-24 10:40:00
     * @param $tag_info
     * @return bool
     */
    private function insertOrUpdateTag($tag_info)
    {
        $f_param = array();

        // 获取标签id
        $tag_id = $tag_info['tag_id'];
        // 获取标签名称
        $tag_name = $tag_info['tag_name'];
        if(empty($tag_name)) $tag_name = '';
        $f_param['tag_name'] = $tag_name;
        // 获取标签描述
        $tag_desc = $tag_info['tag_desc'];
        if(empty($tag_desc)) $tag_desc = '';
        $f_param['tag_description'] = $tag_desc;
        // 获取父标签
        $parent_id = $tag_info['parent_id'];
        if(empty($parent_id)) $parent_id = '0';
        $f_param['parent_id'] = $parent_id;
        // 获取标签更新粒度
        $update_granularity = $tag_info['update_granularity'];
        if(empty($update_granularity)) $update_granularity = TagConstant::GRANULARITY_DEFAULT;
        $f_param['update_granularity'] = $update_granularity;
        // 获取标签更新跨度
        $update_span = $tag_info['update_span'];
        $f_param['update_span'] = $update_span;

        // 判断标签是否存在，存在则更新，不存在则新增
        if(!empty($tag_id))
        {
            $w_param = array();
            $w_param['tag_id'] = $tag_id;
            // 根据标签id 的长度，来判断标签级别
            $f_param['level'] = $this->judgeLevel($tag_id);
            // 更新记录
            $bool = $this->tag_manage_dal->updateTag($w_param, $f_param);
            if($bool)
            {
                $msg = '更新标签成功';
                $f_param['tag_id'] = $tag_id;
                unset($f_param['create_time']);
                unset($f_param['level']);
                $this->transferSingleTag($f_param);
                $data = $f_param;
            }else{
                $msg = '更新标签失败';
                $data = array();
            }
            // 收集操作日期信息
            $module_sign = 'updateTag';
            $operate_type = 'UPDATE';
            $log_conent = array(
                'result'=>array(
                    'msg'=>$msg
                ),
                'update_param'=>array('w_param'=>$w_param,'f_param'=>$f_param)
            );
            $result = UtilBLL::printReturn($bool, $msg, $data);
        }else{
            // 新增记录
            $f_param['create_time'] = date("Y-m-d h:i:s");
            // 生成标签id
            $tag_id = $this->autoGeneratorTagId($parent_id);
            $f_param['tag_id'] = $tag_id;
            // 根据标签id 的长度，来判断标签级别
            $f_param['level'] = $this->judgeLevel($parent_id);
            // 插入记录
            $bool = $this->tag_manage_dal->insertTag($f_param);
            if($bool)
            {
                $msg = '新增标签成功';
                unset($f_param['create_time']);
                unset($f_param['level']);
                $this->transferSingleTag($f_param);
                $data = $f_param;
            }else{
                $msg = '新增标签失败';
                $data = array();
            }
            // 收集操作日期信息
            $module_sign = 'addTag';
            $operate_type = 'INSERT';
            $log_conent = array(
                'result'=>array(
                    'msg'=>$msg
                ),
                'insert_param'=>array('f_param'=>$f_param)
            );
            $result = UtilBLL::printReturn($bool, $msg, $data);
        }
        // 记录日志
        $tips = ($bool===false?'失败':'成功');
        UtilBLL::saveLog($module_sign,$operate_type,json_encode($log_conent),$tips);
        
        return $result;
    }

    /**
     *  根据标签id，获取对应的父子依赖
     *  add by zcyue 2016-7-20 14:41:00
     * @param $tag_id
     * @return array 返回id 数组 eg: [ ''一级分类id, '二级分类id',  ..., '标签id']
     */
    public function getTagIdRelation($tag_id)
    {
        /**
         * todo 不管分类多少，代码写通用
         * 目前先只考虑标签只有或少于二级分类的情况，因此这里只自下向上获取两次父标签id
         */
        if(empty($tag_id)) return array();
        $result = array();
        array_push($result, $tag_id);
        // 获取父级分类
        $w_param = array();
        $w_param['tag_id'] = $tag_id;
        $tag_info = $this->tag_manage_dal->getTagList($w_param, 'tag_id, parent_id');
        if(empty($tag_info))
        {
            return array();
        }
        $parent_id = $tag_info[0]['parent_id'];
        if($parent_id == '0')
        {
            // 将数据逆序
            $result = array_reverse($result);
            return $result;
        }
        array_push($result, $parent_id);
        // 获取父级分类
        $w_param['tag_id'] = $parent_id;
        $tag_info = $this->tag_manage_dal->getTagList($w_param, 'tag_id, parent_id');
        if(empty($tag_info))
        {
            return array();
        }
        $parent_id = $tag_info[0]['parent_id'];
        if($parent_id == '0')
        {
            // 将数据逆序
            $result = array_reverse($result);
            return $result;
        }
        array_push($result, $parent_id);
        // 将数据逆序
        $result = array_reverse($result);
        return $result;
    }

    /**
     *  根据父标签自动生成下一个子标签id
     * 【标签及分类id 生成规则】：
     *  1. 分类级别为1 时，可用id 为 1000~1998
     *  2. 分类级别为2 时，可用id 为 2000~2998
     *  3. 分类级别为3或更高，可用id 为 3000~4998
     *  4. 标签的可用id 为 5000~不限
     * @param $p_tag_id 父标签id
     * @return string 生成的标签id
     */
    private function autoGeneratorTagId($p_tag_id)
    {
        if($p_tag_id == '0')
        {
            // 新增的标签层级为一级
            $tag_id = $this->getLevelOneNextClassifyId();
        }else{
            // 根据父标签id 的首位数字来判断标签层级
            $start_num = substr($p_tag_id, 0, 1);
            if($start_num == '1')
            {
                $tag_id = $this->getLevelTwoNextClassifyId();
            }elseif($start_num == '2'){
                $tag_id = $this->getNextTagId();
            }elseif($start_num == '3' || $start_num == '4'){
                // todo 页面暂不支持三级及以上分类
                $tag_id = TagConstant::LEVEL_THREE;
            }else{
                // todo 页面暂不支持三级及以上分类
                $tag_id = TagConstant::LEVEL_THREE;
            }
        }
        return $tag_id;
    }

    /**
     *  根据父标签id 来判断标签的级别
     * @param $p_tag_id
     * @return int
     */
    private function judgeLevel($p_tag_id)
    {
        if($p_tag_id == '0')
        {
            // 新增的标签层级为一级
            $level = TagConstant::LEVEL_ONE;
        }else{
            // 根据父标签id 的首位数字来判断标签层级
            $start_num = substr($p_tag_id, 0, 1);
            if($start_num == '1')
            {
                $level = TagConstant::LEVEL_TWO;
            }elseif($start_num == '2'){
                $level = TagConstant::LEVEL_THREE;
            }elseif($start_num == '3' || $start_num == '4'){
                /**
                 *  页面暂不支持三级及以上分类
                 * todo
                 */
                $level = TagConstant::LEVEL_THREE;
            }else{
                /**
                 * todo
                 */
                $level = TagConstant::LEVEL_THREE;
            }
        }
        return $level;
    }

    private function getLevelOneNextClassifyId()
    {
        $result = $this->tag_manage_dal->getTagByIdRegion(1000, 2000);
        $max_id = 1000;
        if(!empty($result))
        {
            foreach($result as $item)
            {
                $id = $item['id'];
                if($max_id <= $id)
                {
                    $max_id = $id;
                }
            }
            $new_tag_id = $max_id + 2;
        }else{
            $new_tag_id = $max_id;
        }

        if($new_tag_id >= 2000)
        {
            throw new ErrorException('标签id 超出了上限');
        }
        return strval($new_tag_id);
    }

    private function getLevelTwoNextClassifyId()
    {
        $result = $this->tag_manage_dal->getTagByIdRegion(2000, 3000);
        $max_id = 2000;
        if(!empty($result))
        {
            foreach($result as $item)
            {
                $id = $item['id'];
                if($max_id <= $id)
                {
                    $max_id = $id;
                }
            }
            $new_tag_id = $max_id + 2;
        }else{
            $new_tag_id = $max_id;
        }
        if($new_tag_id >= 3000)
        {
            throw new ErrorException('标签id 超出了上限');
        }
        return strval($new_tag_id);
    }

    private function getNextTagId()
    {
        $result = $this->tag_manage_dal->getTagByIdRegion(5000);
        $max_id = 5000;
        if(!empty($result))
        {
            foreach($result as $item)
            {
                $id = $item['id'];
                if($max_id <= $id)
                {
                    $max_id = $id;
                }
            }
            $new_tag_id = $max_id + 2;
        }else{
            $new_tag_id = $max_id;
        }
        return strval($new_tag_id);
    }

    /**
     *  转换标签数据，为页面需要的结构
     * @param $tag_arr
     */
    private function transferTagInfo(&$tag_arr)
    {
        if(!empty($tag_arr))
        {
            $tag_granularity_conf = $this->config('update_granularity', 'tag.config');
            foreach($tag_arr as $key=>$tag)
            {
                if(!empty($tag['update_granularity']))
                {
                    $tag_arr[$key]['update_granularity'] = $tag_granularity_conf[$tag['update_granularity']];
                }
            }
        }
    }

    /**
     *  转换标签数据，为页面需要的结构
     * @param $tag
     */
    private function transferSingleTag(&$tag)
    {
        if(!empty($tag))
        {
            $tag_granularity_conf = $this->config('update_granularity', 'tag.config');
            if(!empty($tag['update_granularity']))
            {
                $tag['update_granularity'] = $tag_granularity_conf[$tag['update_granularity']];
            }
        }
    }

}
