<?php
/**
 * * @Name        roleBLL.php
 * * @Note        角色业务层业务访问层
 * * @Author      jbxie
 * * @Created     2016年6月20日15:51:01
 * * @Version     v1.0.0
 * */

 class RoleBLL extends Model 
 {
     /**
      * 数据访问层对象变量
      */
     protected $role_dal;
     
    public function __construct()
    {
        parent::__construct();
        $this->role_dal = new RoleDAL();
    }
    
    /**
     * 通过角色ID获取用户权限信息
     * @param array $need_fields 查询字段
     * @param int|array $role_id 查询字段
     * $return array
     */
    public function getRoleInfoByRoleId($need_fields=NULL,$role_id=NULL)
    {
        // 查询条件
        $w_param = array();
        if (!empty($role_id)) $w_param['id'] = $role_id;
        // 查询字段
        $f_param = array();
        if (empty($need_fields))
        {
            $f_param = '*';
        }
        else
        {
            $f_param = $need_fields;
        }
        $return_result = $this->role_dal->getRoleInfo($w_param,$f_param);
        return $return_result;
    }
    
 }