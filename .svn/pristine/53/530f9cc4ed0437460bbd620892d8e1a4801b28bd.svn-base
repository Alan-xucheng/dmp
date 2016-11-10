<?php  
// 自动将相关的请求参数,变为GET的参数
parse_str($_SERVER['QUERY_STRING'],$_GET);

// 校验用户权限
checkUserPriv();

/**
 * 检查用户
 */
function checkUserPriv()
{
    // 判断用户是否已登录
    $user_info = $_SESSION['account_info'];
    $path_info = trim($_SERVER['PATH_INFO'],"/");
    /***
     * 用户未登录
     * 1、请求为ajax请求时返回未登录提示信息
     * 2、请求不为ajax请求并且不是登录请求时直接重定向到登录页面
     */
    if (empty($user_info) && !is_numeric(stripos($path_info, 'accountManage/login')))
    {
        // 请求为ajax请求时返回未登录提示信息
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest")
        {
			$return_arr = UtilBLL::printReturn(FALSE,'用户未登录');
			UtilBLL::printJson($return_arr);
        }
        // 请求不为ajax请求并且不是登录请求时直接重定向到登录页面
        else
        {
            // 计划任务跳过权限验证
			if(!is_numeric(stripos($path_info, 'crontab')))
			{
				redirect('/accountManage/login');
				exit;
			}
        }
    }
    
    /***
     * 用户已登录
     * 1、请求为登录页面则直接跳到标签管理页面
     */
    if (!empty($user_info) && is_numeric(stripos($path_info, 'accountManage/login')) && !is_numeric(stripos($path_info, 'accountManage/login/loginOut')))
    {
        redirect('/tagManage/tagManageIndex');
        exit;
    }
}

/**
 * 检查模板标识权限
 * @param string $module_sign 模板标识
 * @return array
 */
function checkModulePriv($module_sign=NULL)
{
	// 判断模块标识是否为空
	if (empty($module_sign)) return UtilBLL::printReturn(FALSE,'模板标识为空');
	// 获取当前用户的权限集
	$role_authority = json_decode($_SESSION["account_info"]["role_authority"]);
	if (empty($role_authority)) return UtilBLL::printReturn(FALSE,'用户无访问权限');
	$auth_sign = array();
	foreach ($role_authority as $id=>$sign_arr)
	{
		foreach ( $sign_arr as $sign )
		{
			$auth_sign[] = $sign;
		}
	}
	// 判断权限并返回
	return (in_array($module_sign, $auth_sign)) ? UtilBLL::printReturn(TRUE,'有权限访问') : UtilBLL::printReturn(FALSE,'无访问权限');
}

//获取传入时间的相关查询日期
function get_date($type=NULL,$date=NULL)
{
	$date = empty($date)?date("Y-m-d 00:00:00"):$date;
	$timestamp = strtotime($date);
	if ( $type == 1 )
	{
		return date("Y-m-d 00:00:00",strtotime('1 day'));
	}
	else if ( $type == 2 )
	{
		return date('Y-m-d 00:00:00',strtotime('+1 week last monday'));
	}
	else if ( $type == 3 )
	{
		$arr = getdate($timestamp);
		if($arr['mon'] == 12)
		{
			$year = $arr['year'] +1;
			$month = $arr['mon'] -11;
			$nextmonthday = $year.'-0'.$month.'-01';
		}
		else
		{
			$time=strtotime($date);
			$nextmonthday=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)+1).'-01'));
		}
		return date("Y-m-d 00:00:00",strtotime($nextmonthday));
	}
	else if ( $type == 4 )
	{
		$getMonthDays = date("t",mktime(0, 0 , 0,date('n')+(date('n')-1)%3,1,date("Y")));//本季度未最后一月天数
		$month = date('m', mktime(23,59,59,date('n')+(date('n')-1)%3,$getMonthDays,date('Y')));
		$year =  date('Y');
		if ( $month == 12 )
		{
			$month = 1;
			$year += 1;
		}
		else
		{
			$month += 1;
		}
		return date("Y-m-01 00:00:00",strtotime($year."-".$month));
	}
	else if ( $type == 5 )
	{
		$year = date("Y")+1;
		return $year."-01-01 00:00:00";
	}
	else 
	{
		return date("Y-m-d H:i:s");
	}
}

/**
 * parseRequset
 *
 * 将request值解析成直接的符号变量 数组形式返回，接受到返回值后进行extract即可
 *
 * @author   xinglu  <xinglu_1983@hotmail.com>
 * @Created  2009-11-04 11:15:42
 * @access   public
 * @param    null
 * @return   array   $request
 */
function parseRequset (){
    $CI         =& get_instance();     
    $requset    = array();
    foreach ($_GET as $key=>$value)
    {
        if (gettype($CI->input->get($key)) == 'string')
            $requset[$key] = trim($CI->input->get($key));
        else
            $requset[$key] = $CI->input->get($key);
    }
    foreach ($_POST as $key=>$value)
    {
        if (gettype($CI->input->post($key)) == 'string')
           $requset[$key] = trim($CI->input->post($key));
        else
            $requset[$key] = $CI->input->post($key);
    }
    foreach ($_REQUEST as $key=>$value)
    {
        if (gettype($CI->input->request($key)) == 'string')
            $requset[$key] = trim($CI->input->request($key));
        else
            $requset[$key] = $CI->input->request($key);
    }
    return $requset;
}

/**
 * mkDirs
 *
 * 递归创建多级目录
 *
 * @author   xinglu  <xinglu_1983@hotmail.com>
 * @Created  2009-11-04 11:17:42
 * @access   public
 * @param    array   new data
 * @return   void
 */
function mkDirs($path, $power = 0755)
{
    return is_dir($path) or (mkDirs(dirname($path)) and mkdir($path, $power));
}

/** 
 * {{{ getClientIp() : 获取访问客户端IP
 * @return  String  客户端来源IP
 */
function getClientIp()
{
	global $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR, $HTTP_CLIENT_IP;

	$ips = array(
		0 => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
		$HTTP_PROXY_USER,
		$HTTP_X_FORWARDED_FOR,
		$HTTP_CLIENT_IP,
	);
	while(list($k,$ip)=each($ips)){
		//检验ip的合法性
		if(0 == strcmp(long2ip(sprintf("%u", ip2long($ip))), $ip))
		{
			return $ips["$k"];
		}
	}
	return 0;
}

/**
 * 去除sql语句特殊符号
 * @param $str 待处理字符串
 * @return 处理之后的字符串
 */
function escapeMySQL5CwithCP932($str)
{
	$search_array = array("%81%5C","%83%5C","%84%5C","%87%5C",
							"%89%5C","%8A%5C","%8B%5C","%8C%5C",
							"%8D%5C","%8E%5C","%8F%5C","%90%5C",
							"%91%5C","%92%5C","%93%5C","%94%5C",
							"%95%5C","%96%5C","%97%5C","%98%5C",
							"%99%5C","%9A%5C","%9B%5C","%9C%5C",
							"%9D%5C","%9E%5C","%9F%5C","%E0%5C",
							"%E1%5C","%E2%5C","%E3%5C","%E4%5C",
							"%E5%5C","%E6%5C","%E7%5C","%E8%5C",
							"%E9%5C","%EA%5C","%FA%78","%FB%78"
							);

	$replace_array = array("%81%FF","%83%FF","%84%FF","%87%FF",
							"%89%FF","%8A%FF","%8B%FF","%8C%FF",
							"%8D%FF","%8E%FF","%8F%FF","%90%FF",
							"%91%FF","%92%FF","%93%FF","%94%FF",
							"%95%FF","%96%FF","%97%FF","%98%FF",
							"%99%FF","%9A%FF","%9B%FF","%9C%FF",
							"%9D%FF","%9E%FF","%9F%FF","%E0%FF",
							"%E1%FF","%E2%FF","%E3%FF","%E4%FF",
							"%E5%FF","%E6%FF","%E7%FF","%E8%FF",
							"%E9%FF","%EA%FF","%FA%78","%FB%78"
							);

	$encoded = rawurlencode(trim($str));
	
	$encoded = str_replace($search_array, $replace_array, $encoded);

	if (strpos($encoded, "%FF") !== false)
	{
		$tmp_list = explode("%FF", $encoded);
		
		$tmp_list2 = array();
		for ($x = 0; $x < count($tmp_list); $x++)
		{
			
			if ($x == count($tmp_list) - 1)
			{
				$tmp = $tmp_list[$x];
			}
			else
			{
				$tmp = $tmp_list[$x] . "%FF";
			}
			
			array_push($tmp_list2,  $tmp);
		}
		
	}
	else
	{
		$tmp_list2 = array($encoded);
	}
	$count = 0;	
	$tmp_result = "";
	foreach ($tmp_list2 as $tmp)
	{
		$count++;
		
		$tail = "";

		while (true)
		{
			if ((strlen($tmp) >= 6) && ((substr($tmp, -3) == "%5C") || (substr($tmp, -3) == "%27")))
			{
				$tail = substr($tmp, -3) . $tail;
				$tmp = substr($tmp, 0, strlen($tmp) - 3);
			}
			else
			{
				break;
			}
		}
		
		$add_escape = false;
		if ((strlen($tmp) >= 6) && (substr($tmp, -3) == "%FF"))
		{
			$x = -3;
			$ok_count = 0;
			while (true)
			{
				$chr = substr($tmp, $x -3, $x);
				
				if ($chr == "")
				{
					break;
				}
				
				$ok_count++;
				
				$x -= 3;
			}
			

			if (($ok_count > 0) && ($ok_count % 2 == 1 ))
			{

			}
			else
			{
				$add_escape = true;
			}
			
		}
		else
		{

		}

		$tmp = str_replace("%5C", "%5C%5C", $tmp);
		$tail = str_replace("%5C", "%5C%5C", $tail);
		$tmp = str_replace("%27", "%5C%27", $tmp);
		$tail = str_replace("%27", "%5C%27", $tail);
	
		$tmp = str_replace($replace_array, $search_array, $tmp);
		
		if ($add_escape)
		{
			$tmp .= "%5C";
		}

		$tmp_result .= $tmp . $tail;
	}

	$raw = rawurldecode($tmp_result);
		
	return $raw;
}

/**
 * 增加sql语句转义符
 * @param $str 待处理字符串
 * @return 处理之后的字符串
 */
function setMySQLMagicQuotes($string)
{

	if (defined("MYSQL_USE_5C_ESCAPE") && (!MYSQL_USE_5C_ESCAPE))
	{
		$raw = $string;

		$raw = escapeMySQL5CwithCP932($raw);

	}
	else
	{
		$encoded = rawurlencode($string);

		$tmp = str_replace("%5C", "%5C%5C", $encoded);
		$raw = rawurldecode($tmp);

		$raw = str_replace("'", "\\'", $raw);
		$raw = str_replace("\"", "\\\"", $raw);
	}
	return $raw;
}

/**
 * 构造SELECT SQL语句方法
 * @param $param 数组型参数
 * @return 转化后的SQL条件语句
 */
function createWhereOption($param)
{

	$option = "";

	while (@list($key, $val) = each($param))
	{
		
		if ($option != "")
		{
			$option .= " AND ";
		}
		
		$mmflg = null;
		$modkey = null;
		if (strlen($key) > 4)
		{
			$mmflg = strtoupper(substr($key, strlen($key) - 4));
			$modkey = substr($key, 0, strlen($key) - 4);
			$modkey =  $modkey;
		}

		if ($mmflg == "_ALT")
		{
			if (is_array($val))
			{
				$option .= " (";
				foreach ($val as $index => $value)
				{
					$option .= $modkey . " like '%" . setMySQLMagicQuotes($value) . "%' ";
					if ($index < (count($val) - 1)) $option .= " OR ";
				}
				$option .= " ) ";
			}
			else 
			{
				if (get_magic_quotes_gpc() == 1) {
					$option .= $modkey . " like '" . $val . "'";
				} else {
					$option .= $modkey . " like '" . setMySQLMagicQuotes($val) . "'";
				}
			}
		}
		else if ($mmflg == "_MAX")
		{
			if (get_magic_quotes_gpc() == 1)
			{
				$option .= $modkey . " <= '" . $val . "'";
			}
			else
			{
				$option .= $modkey . " <= '" . setMySQLMagicQuotes($val) . "'";
			}
		}
		else if ($mmflg == "_MIN")
		{
			if (get_magic_quotes_gpc() == 1)
			{
				$option .= $modkey . " >= '" . $val . "'";
			}
			else
			{
				$option .= $modkey . " >= '" . setMySQLMagicQuotes($val) . "'";
			}
		}
		else if ($mmflg == "_LTN")
		{
			if (get_magic_quotes_gpc() == 1)
			{
				$option .= $modkey . " < '" . $val . "'";
			}
			else
			{
				$option .= $modkey . " < '" . setMySQLMagicQuotes($val) . "'";
			}
		}
		else if ($mmflg == "_GTN")
		{
			if (get_magic_quotes_gpc() == 1)
			{
				$option .= $modkey . " > '" . $val . "'";
			}
			else
			{
				$option .= $modkey . " > '" .setMySQLMagicQuotes($val) . "'";
			}
		}
		else if ($mmflg == "_NLT")
		{
			if (get_magic_quotes_gpc() == 1) {
				$option .= $modkey . " not like '" . $val . "'";
			} else {
				$option .= $modkey . " not like '" . setMySQLMagicQuotes($val) . "'";
			}				
		}
		else if ($mmflg == "_NOT")
		{
			if (is_array($val))
			{
				if (count($val) > 0)
				{
					if (get_magic_quotes_gpc() == 1)
					{
						$option .= $modkey . " not in ('";
						$option .= implode("','", $val);
						$option .= "')";
					}
					else
					{
						$tmp_list = array();
						foreach ($val as $tmp)
						{
							$tmp = setMySQLMagicQuotes($tmp);
							array_push($tmp_list, $tmp);
						}

						$option .= $modkey . " not in ('";
						$option .= implode("','", $tmp_list);
						$option .= "')";
					}
				}
				else
				{
					$option .= $modkey . " is not null";
				}
				
			}
			else if (is_int($val))
			{
				if (get_magic_quotes_gpc() == 1)
				{
					$option .= $modkey . " != '" . $val . "'";
				}
				else
				{
					$option .= $modkey . " != '" . setMySQLMagicQuotes($val) . "'";
				}
			}
			else if (is_bool($val))
			{
				if ($val)
				{
					$option .= $modkey . " != 1";
				}
				else
				{
					$option .= $modkey . " != 0";
				}
			}
			else if(($val == "null")||($val == ""))
			{
				$option .= $modkey . " is not null";
			}
			else
			{
				if (get_magic_quotes_gpc() == 1)
				{
					$option .= "(" . $modkey . " != '" . $val . "' OR " . $modkey . " is null)";
				}
				else
				{
					$option .= "(" . $modkey . " != '" . setMySQLMagicQuotes($val) . "' OR " . $modkey . " is null)";
				}
			}
		}
		else
		{
			if (is_array($val))
			{
				if (count($val)> 0)
				{
					if (get_magic_quotes_gpc() == 1)
					{
						$option .=  $key . " in ('";
						$option .= implode("','", $val);
						$option .= "')";
					}
					else
					{	
						$tmp_list = array();
						foreach ($val as $tmp)
						{
							$tmp = setMySQLMagicQuotes($tmp);
							array_push($tmp_list, $tmp);
						}
						$option .=  $key .  " in ('";
						$option .= implode("','", $tmp_list);
						$option .= "')";
					}
				}
				else
				{
					$option .= $key . " is null";
				}
			}
			else if (is_int($val))
			{
				$option .= $key  . " = '" . $val . "'";
			}
			else if (is_bool($val))
			{
				if ($val)
				{
					$option .=  $key . " = 1";
				}
				else
				{
					$option .=  $key . " = 0";
				}
			}
			else if ((is_null($val))||($val == ""))
			{
				$option .=  $key . " is null";
			}
			else
			{
				if (get_magic_quotes_gpc() == 1)
				{
					$option .= $key . " = '" . $val . "'";
				}
				else
				{
					$option .=  $key . " = '" . setMySQLMagicQuotes($val) . "'";
				}
			}
		}
	}
	
	return $option;
}

/**
 * 构造UPDATE SQL语句方法
 * @param $param 数组型参数
 * @return 转化后的SQL条件语句
 */
function createUpdateOption($param)
{

	$option = "";

	while(list($key, $val) = each($param))
	{
		
		if($option != "")
		{
			$option .= ", ";
		}
		
		if (is_bool($val))
		{
			if ($val)
			{
				$option .= "`" . $key . "`" . " = '1'";
			}
			else
			{
				$option .= "`" . $key . "`" . " = '0'";
			}
		}
		else if (is_null($val))
		{
			$option .= "`" . $key . "`" . " = null";
		}
		else
		{
			if (get_magic_quotes_gpc() == 1)
			{
				$option .= "`" . $key . "`" . " = '" . $val . "'";
			}
			else
			{
				$option .= "`" . $key . "`" . " = '" . setMySQLMagicQuotes($val) . "'";
			}
		}
		
	}
	
	return $option;
}

/**
 * 构造UPDATE 
 * @param array $param
 */
function createOnDuplicateAddUpdateOption($param)
{
	$option = "";
	if (!empty($param))
	{
		foreach ($param as $key => $val)
		{
			if($option != "")
			{
				$option .= ", ";
			}		

			if (get_magic_quotes_gpc() == 1)
			{
				$option .= "`" . $key . "`" . " = `" . $key . "` + '" . $val . "'";
			}
			else
			{
				$option .= "`" . $key . "`" . " = `" . $key . "` + '" . setMySQLMagicQuotes($val) . "'";
			}
		}
	}

	return $option;
}

/**
 * 构造INSERT SQL语句方法
 * @param $param 数组型参数
 * @return 转化后的SQL条件语句
 */
function createInsertOption($param)
{

	$str = null;
	$cols = null;
	$vals = null;
	
	while(list($key, $val) = each($param))
	{
		
		if($cols != "")
		{
			$cols .= ", ";
		}
		if($vals != "")
		{
			$vals .= ", ";
		}
		
		$cols .= "`" . $key . "`";
		if (is_bool($val))
		{
			if ($val)
			{
				$vals .= "1";
			}
			else
			{
				$vals .= "0";
			}
		}
		else if (is_null($val))
		{
			$vals .= "null";
		}
		else
		{
			if (get_magic_quotes_gpc() == 1)
			{
				$vals .= "'" . $val . "'";
			}
			else
			{
				$vals .= "'" . setMySQLMagicQuotes($val) . "'";
			}
		}
		
		
	}
	
	$str = "(" . $cols . ") VALUES (" . $vals . ")";
	
	return $str;
}
function createMultiInsertOption($params){

    if(empty($params) || !is_array($params) || !is_array($params[0])) return "";
    $cols = array_keys($params[0]);
    $colStr ="";
    foreach($cols as $col){
        $colStr .= ",`{$col}`";
    }
    $colStr = substr($colStr,1);

    $valArr = array();
    foreach($params as $param){
        $valStr = "";
        while(list($key, $val) = each($param)) {
            $valStr .=",";
            if (is_bool($val)) {
                if ($val) {
                    $valStr .= "1";
                } else {
                    $valStr .= "0";
                }
            } else if (is_null($val)) {
                $valStr .= "null";
            } else {
                if (get_magic_quotes_gpc() == 1) {
                    $valStr .= "'" . $val . "'";
                } else {
                    $valStr .= "'" . setMySQLMagicQuotes($val) . "'";
                }
            }
        }
        $valArr[] = "(".substr($valStr,1).")";
    }

    $str = "(" . $colStr . ") VALUES ".implode(",",$valArr);

    return $str;
}
/**
 * 构造GROUP SQL语句方法
 * @param $cols 分组字段
 * @return 分组SQL语句
 */
function createGroupOption($cols)
{
	$group_query = "";

	if (empty($cols)) return $group_query;
	if ( is_array($cols) )
	{
	    $group_str = implode(",",$cols["group by"]);
	}
	else 
	{
	    $group_str = $cols;
	}
	$group_query = " GROUP BY $group_str ";

	return $group_query;
}
	
/**
 * 构造ORDER sql语句方法
 * @param $order 排序字段
 * @param $direct 排序方式
 * @return 排序SQL语句
 */
function createOrderOption($order, $direct)
{

	$order_query = "";

	if (empty($order)) return $order_query;
	if (empty($direct)) $direct = "ASC";
	$order_query = " ORDER BY $order $direct ";

	return $order_query;
}

/**
 * 构造LIMIT OFFSET SQL语句方法
 * @param $offset 游标位置
 * @param $limit 取值范围
 * @return 转化后的SQL条件语句
 */
function createLimitOption($limit, $offset)
{

	$subquery = "";

	if (($limit !== null) && (is_numeric($limit)))
	{
		$subquery .= " LIMIT " . $limit;
	}
	if (($offset != null) && (is_numeric($offset)) )
	{
		$subquery .= " OFFSET " . $offset;
	}
	
	return $subquery;
}

/**
 * 构造SELECT SQL语句方法
 * @param $param 数组型参数
 * @return 转化后的SQL条件语句
 */
function createOptionCondition($param){
	
	$option = "";

	while (list($key, $val) = each($param)) {
		
		if ($option != "") {
			$option .= " AND ";
		}
		
		$mmflg = null;
		$modkey = null;
		if(strlen($key) > 4){
			$mmflg = strtoupper(substr($key, strlen($key) - 4));
			$modkey = substr($key, 0, strlen($key) - 4);
		}

		if($mmflg == "_ALT"){
			if (get_magic_quotes_gpc() == 1) {
				$option .= $modkey . " like '" . $val . "'";
			} else {
				$option .= $modkey . " like '" . setMySQLMagicQuotes($val) . "'";
			}
		}
		else if($mmflg == "_NLT"){
			if (get_magic_quotes_gpc() == 1) {
				$option .= $modkey . " not like '" . $val . "'";
			} else {
				$option .= $modkey . " not like '" . setMySQLMagicQuotes($val) . "'";
			}
		} else if($mmflg == "_MAX"){
			if (get_magic_quotes_gpc() == 1) {
				$option .= $modkey . "<='" . $val . "'";
			} else {
				$option .= $modkey . "<='" . setMySQLMagicQuotes($val) . "'";
			}
		} else if ($mmflg == "_MIN"){
			if (get_magic_quotes_gpc() == 1) {
				$option .= $modkey . ">='" . $val . "'";
			} else {
				$option .= $modkey . ">='" . setMySQLMagicQuotes($val) . "'";
			}
		} else if ($mmflg == "_LTN") {
			if (get_magic_quotes_gpc() == 1) {
				$option .= $modkey . "<'" . $val . "'";
			} else {
				$option .= $modkey . "<'" . setMySQLMagicQuotes($val) . "'";
			}
		} else if ($mmflg == "_GTN") {
			if (get_magic_quotes_gpc() == 1) {
				$option .= $modkey . ">'" . $val . "'";
			} else {
				$option .= $modkey . ">'" . setMySQLMagicQuotes($val) . "'";
			}
		} else if ($mmflg == "_NOT"){
			if(is_array($val)){
				if (count($val) > 0) {
					if (get_magic_quotes_gpc() == 1) {
						$option .= $modkey . " not in ('";
						$option .= implode("','", $val);
						$option .= "')";
					} else {
						$tmp_list = array();
						foreach ($val as $tmp) {
							$tmp = setMySQLMagicQuotes($tmp);
							array_push($tmp_list, $tmp);
						}

						$option .= $modkey . " not in ('";
						$option .= implode("','", $tmp_list);
						$option .= "')";
					}
				} else {
					$option .= $modkey . " is not null";
				}
				
			} else if (is_int($val)) {
				if (get_magic_quotes_gpc() == 1) {
					$option .= $modkey . "!='" . $val . "'";
				} else {
					$option .= $modkey . "!='" . setMySQLMagicQuotes($val) . "'";
				}
			} else if (is_bool($val)) {
				if ($val) {
					$option .= $modkey . "!=1";
				} else {
					$option .= $modkey . "!=0";
				}
			} else if(($val == "null")||($val == "")){
				$option .= $modkey . " is not null";
			} else {
				if (get_magic_quotes_gpc() == 1) {
					$option .= "(" . $modkey . "!='" . $val . "' OR " . $modkey . " is null)";
				} else {
					$option .= "(" . $modkey . "!='" . setMySQLMagicQuotes($val) . "' OR " . $modkey . " is null)";
				}
			}
		}else{
			if (is_array($val)) {
				if (count($val)> 0) {
					if (get_magic_quotes_gpc() == 1) {
						$option .= $key . " in ('";
						$option .= implode("','", $val);
						$option .= "')";
					} else {	
						$tmp_list = array();
						foreach ($val as $tmp) {
							$tmp = setMySQLMagicQuotes($tmp);
							array_push($tmp_list, $tmp);
						}
						$option .= $key . " in ('";
						$option .= implode("','", $tmp_list);
						$option .= "')";
					}
				} else {
					$option .= $key . " is null";
				}
			} else if (is_int($val)) {
				$option .= $key . "='" . $val . "'";
			} else if (is_bool($val)) {
				if ($val) {
					$option .= $key . "=1";
				} else {
					$option .= $key . "=0";
				}
			} else if ((is_null($val))||($val == "")) {
				$option .= $key . " =''";
			} else {
				if (get_magic_quotes_gpc() == 1) {
					$option .= $key . "='" . $val . "'";
				} else {
					$option .= $key . "='" . setMySQLMagicQuotes($val) . "'";
				}
			}
		}
		
	}
	
	return $option;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * Alpha 是否是字母数字下划线
 * @access	public
 * @param	string
 * @return	bool
 */
function alpha($str)
{
	return ( ! preg_match("/^([a-zA-Z0-9_])+$/i", $str)) ? FALSE : TRUE;
}

/**
 * 银行家圆整
 * @param $num
 * @param $a
 * @return float
 */
function bankRound($num,$precision){
	$pow = pow(10,$precision);
	if(  (floor($num * $pow * 10) % 5 == 0) && (floor( $num * $pow * 10) == $num * $pow * 10) && (floor($num * $pow) % 2 ==0) ){
		$re = floor($num * $pow)/$pow;
	}else{//四舍五入
		$re = round($num,$precision);
	}
	$formatterStr = "%.{$precision}f";
	return sprintf($formatterStr,$num);
}
