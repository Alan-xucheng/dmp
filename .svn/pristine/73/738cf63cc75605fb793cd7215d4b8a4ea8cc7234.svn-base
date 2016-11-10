<?php
/**
 * @Name      DBBaseModel.php
 * @Note      执行数据库的数据访问层实体类
 * @Author    jbxie
 * @Created   2014年7月8日11:46:56
 * @Version   Version 2.0.0
 *
 */
include_once  'database/DB.php';
class DBBaseModel extends Model
{
	/**
	 * 默认表名
	 */
	protected $table_name = '';
	
	/**
	 * 执行结果
	 */
	private  $exec_res = true;
	
	/**
	 * 默认的数据库操作类
	 */
	protected $db;
	
	function __construct($params = '', $active_record_override = NULL)
	{
		 parent::__construct();
    }
    
    /**
     * 创建一个数据库连接
     * @param string $params 数据库连接配置参数信息
     */
    public function database($params = '', $active_record_override = NULL)
    {
    	return DB($params, $active_record_override);
    }
    
    /**
     * 查询业务信息
     * $w_param array 查询条件
     * $f_param array 查询字段
     * $group_field string 分组字段
     * $order_field string 排序字段
     * $order_direction string 排序方向
     * $limit int 查询记录数
     * return 结果集
     */
    function execSelectSql($w_param,$f_param,$group_field = null,$order_field = null ,$order_direction = 'desc',$limit = null,$offset = null)
    {
    	if(empty($this->db)  && empty($this->table_name) && empty($f_param))
    	{
    		return null; // 查询条件不为空
    	}
        // 查询字段判断
    	$this->createWhereOption($w_param);
    	$this->db->select($f_param);
    	if(!empty($group_field))$this->db->group_by($group_field);
    	if(!empty($order_field))$this->db->order_by($order_field,strtolower($order_direction));
    	if(!empty($limit) && $limit !=0 && !empty($offset))$this->db->limit($limit,$offset);
    	
    	if(intval($limit)>=1 && intval($offset) >=0)
    	{
    		$query = $this->db->get($this->table_name,$limit,$offset);
    	}
    	else
    	{
    		$query = $this->db->get($this->table_name);
    	}
    	$result =array();
//     	my_debug($query,$query->result_array());exit;
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
     * 添加业务信息
     * $f_param array 查询字段
     * return 结果集
     */
    function execInsertSql($f_param)
    {
    	if(empty($this->db)  && empty($this->table_name) && empty($f_param))
    	{
    		return false; // 条件不为空
    	}
    	try 
    	{
    		$this->exec_res = $this->db->insert($this->table_name, $f_param);
    	}
    	catch (Exception $e) 
    	{
    		$this->exec_res = false;
    	}
    	return $this->exec_res;
    }
    
    /**
     * 获取上一步 INSERT操作产生的 ID
     * return 结果集
     */
    function execInsertIdSql()
    {
    	if(empty($this->db))
    	{
    		return false; // 条件不为空
    	}
    	try 
    	{
    		$insert_id = $this->db->insert_id();
    	}
    	catch (Exception $e) 
    	{
    		return false;
    	}
    	return $insert_id;
    }
    
    /**
     * 更新业务信息
     * $w_param array 更新条件
     * $f_param array 更新字段
     * $is_escape bool 是否阻止数据被转义
     * return 结果集
     */
    function execUpdateSql($w_param,$f_param,$is_escape = FALSE)
    {
    	if(empty($this->db)  && empty($this->table_name) && empty($f_param))
    	{
    		return false; // 条件不为空
    	}
    	try 
    	{
    		$this->db->set($f_param,'',$is_escape);
    		// 查询字段判断
    		$this->createWhereOption($w_param);
    		$this->exec_res = $this->db->update($this->table_name);
    	}
    	catch (Exception $e) 
    	{
    		$this->exec_res = false;
    	}
    	return $this->exec_res;
    }
    
    /**
     * 更新业务信息
     * $w_param array 更新条件
     * $f_param array 更新字段
     * return 结果集
     */
    function execUpdateBatchSql($w_param,$f_param,$key_field)
    {
    	if(empty($this->db)  && empty($this->table_name) && empty($f_param) && empty($key_field))
    	{
    		return false; // 条件不为空
    	}
    	try 
    	{
    		// 查询字段判断
    		$this->createWhereOption($w_param);
    		$this->exec_res = $this->db->update_batch($this->table_name, $f_param, $key_field);
    	} 
    	catch (Exception $e) 
    	{
    		$this->exec_res = false;
    	}
    	return $this->exec_res;
    }
    
    /**
     * 删除业务信息
     * $w_param array 删除条件
     * return 结果集
     */
    function execDeleteSql($w_param)
    {
    	if(empty($this->db)  && empty($this->table_name))
    	{
    		return false; // 条件不为空
    	}
        try 
        {
        	if(!empty($w_param)) $this->db->where($w_param);
        	$this->exec_res = $this->db->delete($this->table_name);
        }
        catch (Exception $e)
        {
        	$this->exec_res = false;
        }
    	return $this->exec_res;
    }
    
    /**
     * 操作纯sql
     * return 结果集
     */
    function execPureSql($sql,$is_select = false)
    {
    	try 
    	{
    		$this->exec_res = $this->db->query($sql);
    		if($is_select)
    		{
    			if($this->exec_res && $this->exec_res->result_array())
    			{
    				foreach ($this->exec_res->result_array() as $row)
    				{
    					$result[] =$row;
    				}
    			}
    			$this->exec_res = $result;
    		}
    	}
    	catch (Exception $e) 
    	{
    		$this->exec_res = false;
    	}
    	return $this->exec_res;
    }
    
    /**
     * 转化的SQL条件语句
     * @param array $w_param 查询条件
     */
    private function createWhereOption($w_param)
    {
    	if(!empty($w_param))
    	{
    		while (list($key, $val) = each($w_param))
    		{
    			$mmflg = null;
    			$modkey = null;
    			if (strlen($key) > 4)
    			{
    				$mmflg = strtoupper(substr($key, strlen($key) - 4));
    				$modkey = substr($key, 0, strlen($key) - 4);
    			}
    			if ($mmflg == "_ALT"){ 
    				if (is_array($val))
    				{
    					// doing
    				}
    				else
    				{
    					$this->db->like($modkey,$val);
    				}
    			}
    			elseif ($mmflg == "_NOT")
    			{
    				$this->db->where_not_in($modkey,$val);
    			}
    			else
    			{
    				if (is_array($val))
    				{
    					$this->db->where_in($key,$val);
    				}
    				else
    				{
    					$this->db->where($key,$val);
    				}
    			}
    		}
    	}
    }
    
}
