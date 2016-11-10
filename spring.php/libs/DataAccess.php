<?php
include_once  ('database/DB.php');
/**
 * 数据层基类，提供最基本的数据操作支持
 * @author jjchen
 */
class DataAccess extends Model
{
    /**
     * 默认表名
     */
    protected static $table_name = '';

    /**
     * 默认的数据库操作类
     */
    protected $db;

    /**
     * 事务开启状态
     */
    private $trans_enabled = FALSE;

    /**
     * 是否已回滚，如果已回滚，后续操作将不再执行
     */
    private $trans_rollbacked = FALSE;

    public function __construct($params = '', $active_record_override = NULL, $trans_enabled = FALSE)
    {
        parent::__construct();
        $this -> db = DB($params, $active_record_override);

        //判断并开启事务
        if ($trans_enabled)
        {
            $this -> trans_begin();
        }
    }

    function __destruct()
    {
        //析构时提交事务
        $this -> trans_end();
    }

    public function trans_begin()
    {
        $this -> trans_rollbacked = FALSE;
        $this -> trans_enabled = TRUE;
        $this -> db -> trans_begin();
    }

    public function trans_end()
    {
        if ($this -> trans_enabled)
        {
            if ($this -> trans_rollbacked)
                return FALSE;
            $this -> db -> trans_commit();
            $this -> trans_enabled = FALSE;
        }
        return TRUE;
    }

    /**
     * 事务回滚
     */
    public function trans_rollback()
    {
        if ($this -> trans_enabled)
        {
            $this -> db -> trans_rollback();
            $this -> trans_rollbacked = TRUE;
        }
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
     * 从数据库中查询数据
     * @param mixed $w_param 查询条件
     */
    protected function select($w_param = null, $order_by = null, $direct="DESC", $limit = null, $offset = null, $table_name = null)
    {
    	if (empty($table_name)){
    		$table_name = static::$table_name;
    	}
    	$sql = "SELECT * FROM {$table_name}";
    	if (!empty($w_param)) $sql .= " WHERE " . createWhereOption($w_param);
    	$sql .= createOrderOption($order_by, $direct);
    	$sql .= createLimitOption($limit, $offset);

    	$query = $this->db->query($sql);
        $result = array();
        foreach ($query->result_array() as $row)
        {
            $result[] = $row;
        }
        return $result;
    }

    protected function getCount($w_param = null,$table_name){
    	if (empty($table_name)){
            $table_name = static::$table_name;
        }
        $sql = "SELECT COUNT(*) as count FROM {$table_name}";
        if (!empty($w_param)) $sql .= " WHERE " . createWhereOption($w_param);

        $result = $this->db->query($sql)->result_array();
        if (!empty($result)){
            return $result[0]["count"];
        }
        return 0;
    }

    /**
     * 插入数据
     * @param array $i_param 插入数据集合
     */
    protected function insert($i_param, $table_name = null)
    {
        if ($this -> trans_rollbacked)
            return FALSE;

        if (empty($table_name))
        {
            $table_name = static::$table_name;
        }
        $ret = $this -> db -> insert($table_name, $i_param);

        if ($ret)
        {
            return TRUE;
        }
        $this -> trans_rollback();
        return FALSE;
    }

    /**
     * 移除数据
     */
    protected function remove($w_param, $table_name = null)
    {
        if ($this -> trans_rollbacked)
            return FALSE;

        if (empty($table_name))
        {
            $table_name = static::$table_name;
        }

        $this -> where($w_param);

        $ret = $this -> db -> delete($table_name);

        if ($ret)
        {
            return TRUE;
        }
        $this -> trans_rollback();
        return FALSE;
    }

    /**
     * 更新数据
     */
    protected function update($u_param, $w_param, $table_name = null)
    {
        if ($this -> trans_rollbacked)
            return FALSE;

        if (empty($table_name))
        {
            $table_name = static::$table_name;
        }

        $this -> where($w_param);

        $ret = $this -> db -> update($table_name, $u_param);

        if ($ret)
        {
            return TRUE;
        }
        $this -> trans_rollback();
        return FALSE;
    }

    /**
     * 设置查询条件
     */
    private function where($w_param)
    {
        if (empty($w_param))
            return;
        if (is_string($w_param))
        {
            $this -> db -> where($w_param);
        }
        else if (is_array($w_param))
        {
            foreach ($w_param as $key => $value)
            {
                if (is_array($value))
                {
                    $this -> db -> where_in($key, $value);
                }
                else
                {
                    $this -> db -> where($key, $value);
                }
            }
        }
    }

}
?>