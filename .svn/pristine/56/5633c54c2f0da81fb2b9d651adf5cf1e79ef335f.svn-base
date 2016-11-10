<?php
/**
 * * @Name        TagManageAjax.php
 * * @Note        标签管理数据请求处理
 * * @Author      zcyue
 * * @Created     2016年6月18日10:25:14
 * * @Version     v1.0.0
 * */

class TagManageAjax extends Controller
{

    private $tag_manage_bll;
    private $production_manage_bll;

    public function __construct()
    {
        parent::__construct();
        $this->tag_manage_bll = new TagManageBLL();
        $this->production_manage_bll = new ProductionManageBLL();
    }

    /**
     *  获取一级标签
     *  add by zcyue 2016-6-24 11:20:00
     */
    public function getLevelOneTag()
    {
        $tag_info = $this->tag_manage_bll->getLevelOneTag();
        $result = UtilBLL::printReturn(true, '获取一级标签', $tag_info);
        UtilBLL::printJson($result);
    }

    /**
     *  根据标签id 获取对应的子标签
     *  add by zcyue 2016-6-22 12:20:00
     */
    public function getChildTag()
    {
        // 获取请求参数中的标签id
        $tag_id = trim(Request::rawparams('tag_id'));
        // 获取请求参数中的层级
        $depth = trim(Request::rawparams('depth'));
        // 默认只获取深度为1 的子标签
        if(empty($depth)) $depth = 1;
        // 标签id 不能为空
        if(empty($tag_id))
        {
            $tag_info = $this->tag_manage_bll->getLevelOneTag();
            $result = UtilBLL::printReturn(true, '获取一级标签', $tag_info);
        }else{
            $tag_info = $this->tag_manage_bll->getChildTag($tag_id, $depth);
            $result = UtilBLL::printReturn(true, '获取子标签', $tag_info);
        }
        UtilBLL::printJson($result);
    }

    /**
     *  获取全量的标签
     *  add by zcyue 2016-6-23 13:30:00
     */
    public function getAllTag()
    {
        $tag_info = $this->tag_manage_bll->getAllTag();
        $result = UtilBLL::printReturn(true, '获取所有标签', $tag_info);
        UtilBLL::printJson($result);
    }

    /**
     *  获取需要分页的标签（目前是指三级标签）
     *  add by zcyue 2016-6-27 11:39:00
     */
    public function getPaginationTag()
    {
        // 获取请求参数中的父标签id
        $p_tag_id = trim(Request::rawparams('tag_id'));
        // 获取排序参数
        $sort_key = Request::rawparams("sort_key");
        $sort_direct = Request::rawparams("sort_direct");
        $page_num = Request::rawparams("page_num");
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
        $total_count = $this->tag_manage_bll->getCountWithPTagId($p_tag_id);
        $offset = ($page_num - 1)*$page_size;
        $tag_info = $this->tag_manage_bll->getPaginationTag($p_tag_id, $sort_key, $sort_direct, $page_size, $offset);

        // 封装结果
        $data = array();
        $data['page_size'] = $page_size;
        $data['current_page'] = $page_num;;
        $data['total_count'] = $total_count;
        $data['data'] = $tag_info;
        $result = UtilBLL::printReturn(true, '分页获取标签信息', $data);
        UtilBLL::printJson($result);
    }

    /**
     *  适配新增和更新接口
     *  add by zcyue 2016-6-24 11:30:00
     */
    public function addOrUpdateTag()
    {
        // 获取请求参数中的标签信息
        $tag_info = Request::rawparams('tag');
        $tag_id = $tag_info['tag_id'];
        if(!empty($tag_id))
        {
            // 检查权限
            $update_auth_info = checkModulePriv('updateTag');
            if(!$update_auth_info['flag'])
            {
                // 没有更新标签权限
                $result = UtilBLL::printReturn(false, '没有更新标签的权限');
                UtilBLL::printJson($result);
            }
            // 调用业务处理层，进行更新
            $result = $this->tag_manage_bll->updateTag($tag_id, $tag_info);
            UtilBLL::printJson($result);
        }else{
            // 检查权限
            $add_auth_info = checkModulePriv('addTag');
            if(!$add_auth_info['flag'])
            {
                // 没有添加标签权限
                $result = UtilBLL::printReturn(false, '没有增加标签的权限');
                UtilBLL::printJson($result);
            }
            // 调用业务处理层，进行添加
            $result = $this->tag_manage_bll->addTag($tag_info);
            UtilBLL::printJson($result);
        }
    }

    /**
     *  删除标签
     *  add by zcyue 2016-6-23 16:00:00
     */
    public function deleteTag()
    {
        // 检查权限
        $delete_auth_info = checkModulePriv('deleteTag');
        if(!$delete_auth_info['flag'])
        {
            // 没有删除标签权限
            $result = UtilBLL::printReturn(false, '没有删除标签的权限');
            UtilBLL::printJson($result);
        }

        // 获取请求参数中的标签信息
        $tag_id = trim(Request::rawparams('tag_id'));
        // 调用业务处理层，进行删除
        $result = $this->tag_manage_bll->deleteTag($tag_id);

        // 更新产品标签映射表
        if($result['flag'])
        {
            $remove_result = $this->production_manage_bll->removeProductTag($tag_id);
            if(!$remove_result['flag']) UtilBLL::printJson($remove_result);
        }

        UtilBLL::printJson($result);
    }

}