<?php
/**
 * * @Name        MenuBLL.php
 * * @Note        系统模块业务访问层
 * * @Author      zcyue
 * * @Created     2016年6月22日15:00:26
 * * @Version     v1.0.0
 * */

class MenuBLL extends Model
{

    private $module_manage_bll;
    private $account_bll;

    public function __construct()
    {
        parent::__construct();
        $this->module_manage_bll = new ModuleManageBLL();
        $this->account_bll = new AccountBLL();
    }

    /**
     * 获取模块的完整的路径信息,往上层递归查询路径
     * @param string $id	模块ID
     * @param string $module_sign	模块的标识
     * @return string 路径信息
     */
    public function getModuleFullPath($id=NULL, $module_sign=NULL)
    {
        //若是无查询的参数则直接返回错误
        if ( empty($id) && empty($module_sign) ) return FALSE;

        //组成查询条件,获取模块信息
        $path = "";
        $w_param = array();
        if ( !empty($id) ) $w_param["id"] = $id;
        if ( !empty($module_sign) ) $w_param["module_sign"] = $module_sign;
        $module_list = $this->module_manage_bll->getModuleInfo($w_param);
        $path .= $module_list[0]["module_sign"];

        //若模块不为顶级模块则递归查询父级模块信息,并添加至路径数组中
        if ( $module_list[0]["parent_id"] != 0 )
        {
            if ( $path != "" ) $path .= ",";
            $path .= self::getModuleFullPath($module_list[0]["parent_id"]);
        }

        //将path的顺序调整输出完整的路径,返回
        return implode("/", array_reverse(explode(",",$path)));
    }

    /**
     *  获取菜单栏数据
     * （一级标签，对应的fullpath等）
     *  add by zcyue 2016-6-22 14:20:00
     * @param $current_module_sign string 页面选中的模块
     */
    public function getMenu($current_module_sign)
    {
        // 在用户登录的情况下去获取菜单信息,否则直接返回空数组
        $menu = array();
        // 判断用户是否已登录
        $account_info = $this->account_bll->readAccountInfoFromSession();
        if(!empty($account_info))
        {
            // 根据权限来获取菜单名称等信息
            $authority = json_decode($account_info["role_authority"]);
            if(!empty($authority))
            {
                $menu = $this->assembleMenu($authority);
                $menu = array_values($menu);
            }
        }
        // 为menu 中的记录增加 is_current 属性，为true 表示当前页面选中
        foreach($menu as $index=>$item)
        {
            $module_sign = $item['module_sign'];
            if($module_sign == $current_module_sign)
            {
                $menu[$index]['is_current'] = true;
            }else{
                $menu[$index]['is_current'] = false;
            }
        }

        return $menu;
    }

    /**
     *  根据用户权限，拼装首页菜单
     * @param $authority
     * @param $is_show 是否显示隐藏菜单,默认不显示
     * @return array 层级关系的菜单数组
     */
    private function assembleMenu($authority, $is_show=0)
    {

        //在用户登录的情况下去获取菜单信息,否则直接返回空数组
        $menu = array();

        if(empty($authority)) return $menu;

        //由于当前数据库采用的是直连方式
        //将id与module_sign获取后统一查询数据库,减少数据库的交互
        $menu_id = array();
        $menu_sign = array();
        foreach ( $authority as $id=>$module_sign )
        {
            $menu_id[] = $id;
            foreach ( $module_sign as $sign )
            {
                $menu_sign[] = $sign;
            }
        }
        //var_dump($menu_id, $menu_sign);exit;
        //查询一级菜单模块信息,并重新拼装格式array(id=>info)
        $w_param = array();
        $w_param["id"] = $menu_id;
        $top_menu_list = $this->module_manage_bll->getModuleInfo($w_param, '*', "module_order", "desc");
        $top_list = array();
        foreach ( $top_menu_list as $key=>$menu_obj )
        {
            // 如果一级菜单is_display 为不显示，则需要去除
            if($menu_obj['is_display'] == 0)
            {
                continue;
            }

            $top_list[$menu_obj["id"]] = $menu_obj;

            //获取一级菜单路径
            $full_path = $this->getModuleFullPath($menu_obj["id"]);
            $top_list[$menu_obj["id"]]["full_path"] = $full_path;
            $top_list[$menu_obj["id"]]["child"] = array();
        }

        //查询二级菜单模块信息,并重新拼装格式array(id=>info)
        $w_param = array();
        $w_param["module_sign"] = $menu_sign;
        $second_menu_list = $this->module_manage_bll->getModuleInfo($w_param, '*', "module_order", "desc");
        $second_list = array();
        foreach ( $second_menu_list as $key=>$menu_obj )
        {
            $second_list[$menu_obj["module_sign"]] = $menu_obj;

            //获取全路径
            $full_path = $this->getModuleFullPath($menu_obj["id"]);
            $second_list[$menu_obj["module_sign"]]["full_path"] = $full_path;
        }

        //组装最终的返回菜单数据格式
        foreach ( $authority as $id=>$module_sign )
        {
            foreach ( $module_sign as $sign )
            {
                $top_list[$id]["child"][] = $second_list[$sign];
            }
        }
        //var_dump($top_list);exit;
        //将menu中不显示的模块去除,若一级菜单仅有一个二级菜单且二级菜单不显示
        //则将二级菜单的路径赋值给一级菜单
        $key = 0;
        foreach ( $top_list as $id=>$menu_obj )
        {
            //将菜单信息赋值给menu后去除child
            $menu[$key] = $menu_obj;
            $menu[$key]["child"] = array();

            //根据child的display来判断是否在菜单中显示
            foreach ( $menu_obj["child"] as $child )
            {
                if ( $is_show )
                {
                    $menu[$key]["child"][] = $child;
                }
                else
                {
                    if ( $child["is_display"] ) $menu[$key]["child"][] = $child;
                }
            }
            //若是菜单child为空,则默认第一个child的路径
            $menu[$key]["full_path"] = empty($menu[$key]["child"])?$menu_obj["child"][0]["full_path"]:"";
            if ( $is_show && empty($menu[$key]["child"]) ) $menu[$key]["module_sign"] = $menu_obj["child"][0]["module_sign"];
            $key++;
        }

        return $menu;
    }

}
