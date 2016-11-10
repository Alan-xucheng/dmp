<?php
/**
 * * @Name        TagViewIndex.php
 * * @Note        标签视图页面控制层
 * * @Author      zcyue
 * * @Created     2016年6月18日10:25:14
 * * @Version     v1.0.0
 * */

class TagViewIndex extends Controller
{

    private $menu_bll;

    public function __construct()
    {
        parent::__construct();
        $this->menu_bll = new MenuBLL();
    }

    /**
     * 标签视图
     */
    public function index()
    {
        // 获取模块信息, 默认是标签管理选中
        $current_module_sign = ModuleConstant::TAG_VIEW;
        $menu = $this->menu_bll->getMenu($current_module_sign);

        Response::assign('menu', $menu);
        Response::display("tag_view/view_index.html");
        exit;
    }

}