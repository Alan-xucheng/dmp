<?php
/**
 * @Name      DateHandler.php
 * @Note      工具业务层处理类
 * @Author    jbxie
 * @Created   2016年6月17日15:28:21
 * @Version   v.1.0.0
 */
 
class UtilBLL extends Model
{
	//构造函数
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 静态方法,校验用户是否登录
	 * @return NULL 无返回值
	 */
	public static function checkLogin()
	{
		if ( !isset($_SESSION["USER_INFO"]) || empty($_SESSION["USER_INFO"]) )
		{
			$handler = new Model();
			Response::write("<script>window.top.location.href='".$handler->config("domain")."/"."login"."';</script>");
			exit;
		}
		// 获取登陆用户关联信息
		return ;
	}

	/**
	 * 静态方法获取登录的用户信息
	 * @return array|NULL
	 */
	public static function getLoginUserInfo()
	{
		if ( isset($_SESSION["USER_INFO"]) )
		{
			return $_SESSION["USER_INFO"];
		}
		return NULL;
	}

	/**
	 * 通过某字段值为key值处理数据数组
	 * @param array $handle_arr 数组数组
	 * @param array $field 字段key
	 * @return array
	 */
	public static function handleArrToFieldVal($handle_arr,$field)
	{
		$return_handle_arr = array();
		if(!empty($handle_arr) && !empty($field))
		{
			foreach ($handle_arr as $item)
			{
				$return_handle_arr[$item[$field]] = $item;
			}
		}
		return $return_handle_arr;
	}

	/**
	 * 获取uuid
	 * @param $length 长度
	 * @return string
	 */
	public static function getUuidStr($length=12)
	{
		$str = md5(uniqid(mt_rand(), true));
		$uuid  = substr($str,0,$length);
		return $uuid;
	}
	
	/**
	 * 生成uuid add by jbxie
	 */
	public static function guid(){
	    if (function_exists('com_create_guid')){
	        $uuid=com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = chr(123)// "{"
	        .substr($charid, 0, 8).$hyphen
	        .substr($charid, 8, 4).$hyphen
	        .substr($charid,12, 4).$hyphen
	        .substr($charid,16, 4).$hyphen
	        .substr($charid,20,12)
	        .chr(125);// "}"
	        $uuid = str_replace('-', '', $uuid);
	        $uuid = str_replace('{', '', $uuid);
	        $uuid = str_replace('}', '', $uuid);
	    }
	    return  $uuid;
	}

	/**
	 * 合法性验证
	 * $string：验证的字符串  不合法   -1
	 * $maxlength：字符串的最大长度   超过长度 -2
	 * $array：必须在某个数组里  不在数组里  -3
	 * $array：是否是特殊字符  -4
	 * 符合条件  1
	 */
	public static function checkLegalityVerification($string, $maxlength=NULL, $array=NULL, $checkStr=array('>','<','/','\'','"'))
	{
		// 判断字符串是否为空
		if(empty($string))
		{
			return array('status'=> -1,'msg'=>'不能为空');
		}
		// 判断是否是非法的特殊字符
		$specialLen = count($checkStr);
		for($i = 0 ; $i < $specialLen ; $i++)
		{
			if(self::isInString($string,$checkStr[$i]))
			{
				return array('status'=> -4,'msg'=>'含特殊字符('.implode(',',$checkStr).')');
			}
		}
		// 判断规定字符串的长度
		$string = preg_replace('/[\x80-\xff]{1,3}/', ' ', $string, -1);
		if($maxlength&&(strlen($string)  > $maxlength))
		{
			return array('status'=> -2,'msg'=>'超过了'.$maxlength.'个字符');
		}

		// 判断字符串是否在规定的数组里面
		if($array&&(!in_array($string,$array)))
		{
			return array('status'=> -3,'msg'=>'不合法');
		}
		else
		{
			return array('status'=> 1,'msg'=>'合法');
		}
	}

	/**
	 * 判断字符是否在字符串类
	 * @param unknown_type $haystack
	 * @param unknown_type $needle
	 */
	public static function isInString($haystack, $needle)
	{
		return false !== strpos($haystack, $needle);
	}

	/**
	 * 处理文件上传错误信息
	 * @param $upload
	 * @return string
	 */
	public static function getFileError($upload)
	{
		if(empty($upload))
		{
			return "参数错误";
		}
		if(empty($upload -> error_msg[0]))
		{
			return "参数错误";
		}

		$key = $upload -> error_msg[0];
		if ($key == 'upload_invalid_filetype')
		{
			return '上传文件格式错误，只能为' . implode(',', $upload -> allowed_types);
		}
		if ($key == 'upload_invalid_filesize')
		{
			return '上传文件超过大小限制，不能超过' . ($upload -> max_size / 1024) . 'MB';
		}
		if($key == 'upload_file_exceeds_limit')
		{
			return '上传文件超过大小限制，不能超过' . ($upload -> max_size / 1024) . 'MB';
		}
		if($key == 'upload_file_partial')
		{
			return '上传文件格式错误，只能为' . implode(',', $upload -> allowed_types);
		}
		return '上传发生错误，请重新选择文件上传[' . $key . ']';
	}

	/**
	 * 获取登录用户查询UID
	 * @return 结果集
	 */
	public static function getLoginSelectUid($uid = null)
	{
	}

	/**
	 * 保存用户的操作日志信息
	 * @param  string $module_sign    模块标识
	 * @param  string $operate_type 操作类型（insert updayte delete select）
	 * @param  string $content        操作的内容
	 * @param  string $result 		  操作的结果
	 * @return boolen 日志记录的结果
	 */
	public static function saveLog($module_sign=NULL, $operate_type=NULL, $content=NULL, $result=NULL)
	{
		// 获取IP
		$client_ip = self::getClientIp();
		// 获取当前路径
		$request_url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		// 获取当前的登录用户信息
		$user_acocunt = $_SESSION["account_info"]["login_account"];
		// 根据模块标识获取模块名称
		$module_manage_bll = new ModuleManageBLL();
		$module = $module_manage_bll->getModuleInfo(array('module_sign'=>$module_sign),'module_name');
		$module_name = $module[0]['module_name'];
		// 获取调用的方法名称
		$backtrace = debug_backtrace();
		array_shift($backtrace);
		$function_name = $backtrace[0]["function"];
		// 写入数据库
		$system_log_bll = new SystemLogBLL();
		$i_param = array();
		$i_param["user_account"] = $user_acocunt;
		$i_param["client_ip"] = $client_ip;
		$i_param["function_name"] = $function_name;
		$i_param["module_name"] = $module_name;
		$i_param["operate_type"] = $operate_type;
		$i_param["content"] = is_array($content)?json_encode($content):$content;
		$i_param["result"] = $result;
		$i_param["request_url"] = $request_url;
		return $system_log_bll->addSystemLog($i_param);
	}

	//获取客户端IP
	public static function getClientIp()
	{
		global $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR, $HTTP_CLIENT_IP;

		$ips = array(
		0 => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
		$HTTP_PROXY_USER,
		$HTTP_X_FORWARDED_FOR,
		$HTTP_CLIENT_IP,
		);

		while(list($k,$ip)=each($ips))
		{
			//检验ip的合法性
			if( 0 == strcmp(long2ip(sprintf("%u", ip2long($ip))), $ip) )
			{
				return $ips["$k"];
			}
		}

		return 0;
	}

	/**
	 * 字段排序
	 * @param array $result 排序数组
	 * @param string $order_field 排序字段
	 * @param string $order_direction 排序方向
	 * @return array
	 */
	public static function arrayMultiSort($result,$order_field,$order_direction)
	{
		// 被排序数据
		$sort_arr = array();
		$order_direction = strtolower($order_direction) == 'asc' ? SORT_ASC : SORT_DESC;
		foreach ($result as $key =>$item)
		{
			$sort_arr[$key] = $item[$order_field];
		}

		array_multisort($sort_arr,$order_direction,$result);
		return $result;
	}

	/**
	 * 生成token
	 * @param string $id
	 * @param string $name
	 * @return string
	 */
	public static function createCsrfToken($id,$name="CSRF_TOKEN")
	{
		$token = md5(uniqid(mt_rand(), TRUE));
		$_SESSION["TOKEN"][$name] = $token;
		return "<input type='hidden' id='".$id."' key='".$name."' value='".$token."' />";
	}

	/**
	 * 验证token
	 * @param string $name
	 * @return bool
	 */
	public static function VerifyCsrfToken($name="CSRF_TOKEN")
	{
		$token = Request::params($name);
		if($token == $_SESSION["TOKEN"][$name])
		{
			unset($_SESSION["TOKEN"][$name]);
			return true;
		}
		else
		{
			echo json_encode(array("flag"=>false,"msg"=>"token已失效"));
			exit;
		}
	}

	/**
	 * CURL模拟请求
	 * @param string $data
	 * @param string $url
	 * @param string $header
	 * @param bool $is_json_data
	 */
	public static function curlPost($data=NULL,$url=NULL,$header=NULL,$is_json_data = FALSE)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    if ( !empty($header) ) curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
	    if($is_json_data)
	    {
	        curl_setopt($ch, CURLOPT_POST, TRUE);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    }
	    elseif ( !empty($data) )
	    {
	        curl_setopt($ch, CURLOPT_POST, TRUE);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	    }
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
	
	/**
	 * 生成随机数
	 * @param number $length
	 * @return string
	 */
	public static function generateRand($length = 4)
	{
	    // 密码字符集，可任意添加你需要的字符
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $rand_str = '';
	    for ( $i = 0; $i < $length; $i++ )
	    {
	        $rand_str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	    }
	    return $rand_str;
	}
	
	/**
	 * 记录日志
	 * @param string $msg 日志信息
	 * @param string $file
	 */
	public function txtLog($msg=NULL,$file=NULL)
	{
	    // 获取IP
	    $client_ip = self::getClientIp();
	    // 获取当前路径
	    $request_url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	    // 获取调用的方法名称
	    $backtrace = debug_backtrace();
	    array_shift($backtrace);
	    $function_name = $backtrace[0]["function"];
	    $sys_log = array('client_ip'=>$client_ip,'request_url'=>$request_url,'function'=>$function_name);
	
	    $time = date('Y-m-d H:i:s');
	    if (is_array($msg)) {
	        $msg = json_encode($msg);
	    }
	    $log_content =  "[".$time."] ".$msg;
	    $log_content .=  ' req_desc:'.json_encode($sys_log);
	    $file = empty($file) ? 'business.log' : $file;
	    $filename = dirname($_SERVER['DOCUMENT_ROOT']).'/log/'.$file;
	    if (!file_exists($filename))
	    {
	        @touch($filename);
	    }
	    file_put_contents($filename,$log_content."\n",FILE_APPEND);
	}
	
	/**
	 * URL安全base64加密
	 * @param string $data 原始数据
	 */
	public static function base64url_encode($data) 
	{
	    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
	
	/**
	 * URL安全base64解密 
	 * @param string $data 密文
	 * @return string
	 */
	public static function base64url_decode($data) 
	{
	    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}

	/**
	 * 输出json
	 * @param array $data
	 * $return string
	 */
	public static function printReturn($falg=FALSE,$msg='',$data=NULL)
	{
	    if (empty($data)) 
	    {
	        $data = array();
	    }	       
        return array('flag'=>$falg,'msg'=>$msg,'data'=>$data);
	    exit;
	}
	
	/**
	 * 输出json
	 * @param array $data
	 * $return string
	 */
	public static function printJson($data)
	{
	    header("Content-type: application/json;charset=UTF-8");
	    echo json_encode($data);
	    exit;
	}
	
	/**
	 * 将字符串按照指定的长度转化为数组
	 * @param string $str 原串
	 * @param number $step 步调
	 * @return array
	 */
	public static function strToArrayByLen($str=NULL,$step=4)
	{
	    if (empty($str))
	    {
	        return array();
	    }
	    $str_len = strlen($str);
	    $return_result = array(); // 最终返回的数组
	    if (empty($step)) $step = 4; // 默认每4个字符长度存成一个数组
	    for ($i=0; $i<$str_len; $i+=$step) 
	    {
	        if (strlen($str) >= $step) 
	        {
	            $return_result[] = substr($str, 0, $step);
	            $str = substr($str, $step);
	        } else {
	            $return_result[] = $str;
	        }
	    }
	    return $return_result;
	}
	
}

