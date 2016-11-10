<?php
/**
 * * @Name        TagManageIndex.php
 * * @Note        标签管理页面控制层
 * * @Author      zcyue
 * * @Created     2016年6月18日10:25:14
 * * @Version     v1.0.0
 * */

class TagManageIndex extends Controller
{

    private $pre_condition_bll;
    private $menu_bll;

    public function __construct()
    {
        parent::__construct();
        $this->pre_condition_bll = new PreConditionBLL();
        $this->menu_bll = new MenuBLL();
    }

    /**
     * 标签管理
     */
    public function index()
    {
        // 缓存标签产品映射
        $this->pre_condition_bll->cacheTagProduction();

        // 获取模块信息, 默认是标签管理选中
        $current_module_sign = ModuleConstant::TAG_MANAGE;
        $menu = $this->menu_bll->getMenu($current_module_sign);

        // 添加、更新、删除标签权限
        $add_auth_info = checkModulePriv('addTag');
        $update_auth_info = checkModulePriv('updateTag');
        $delete_auth_info = checkModulePriv('deleteTag');

        // 标签更新粒度枚举值
        $update_granularity = $this->config('update_granularity', 'tag.config');

        Response::assign('menu', $menu);
        Response::assign('add_auth', $add_auth_info['flag']);
        Response::assign('update_auth', $update_auth_info['flag']);
        Response::assign('delete_auth', $delete_auth_info['flag']);
        Response::assign('update_granularity', $update_granularity);
        Response::display("tag_manage/index.html");
        exit;
    }

}