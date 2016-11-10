<?php
/**
 * @Name      UploadUtilBLL.php
 * @Note      上传公用的模型
 * @Author    jbxie
 * @Created   2015年4月7日11:37:24
 * @Version   1.0.0
 *
 */
class UploadUtilBLL extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 对比图片的分辨率
     * @param string $image_w_h 图片分辨率 (比如 60*60)
     * @param string $field 字节流
     * @return boolean
     */
    public function compareImagewh($image_w_h,$field)
    {
    	$compare_w_h = true;
    	if(!empty($image_w_h)){
    		$file_temp = $_FILES[$field]['tmp_name'];
    		$w_h = explode('*', $image_w_h);
    		if(count($w_h) == 2 && $file_temp){
    			$D = getimagesize($file_temp);
    			if($w_h[0] != $D[0] || $w_h[1] != $D[1]) $compare_w_h = false;
    		}
    		else{
    			$compare_w_h = false;
    		}
    	}
    	else{
    		$compare_w_h = false;
    	}
    	return $compare_w_h;
    }
    
    /**
     * 获取文件格式
     *
     * @access	public
     * @param	string
     * @return	string
     */
    function get_extension($filename)
    {
    	$x = explode('.', $filename);
    	return '.'.end($x);
    }
    
   /**
    * 加载mime类型
    * @param string $mime 类型名
    * @return array
    */
    function mimes_types($mime)
    {
    	if (count($this->mimes) == 0){
    		if (@include(SpringConstant::CONFIG_PATH.'/mimes.php')){
    			$this->mimes = $mimes;
    			unset($mimes);
    		}
    	}
    	return ( ! isset($this->mimes[$mime])) ? FALSE : $this->mimes[$mime];
    }
    
    /**
     * 判断是否需求的类型
     * @param string $field file字段
     * @param array $allowed_types 允许类型
     * @param string $upload_type 上传类型
     * return array
     */
    function is_allowed_filetype($field,$allowed_types,$upload_type)
    {
    	$file_temp = $_FILES[$field]['tmp_name'];
    	$file_type = preg_replace("/^(.+?);.*$/", "\\1", $_FILES[$field]['type']);
    	$file_type = strtolower($file_type);
    	if (count($allowed_types) == 0 OR ! is_array($allowed_types)){
    		return array('result'=>false,'msg'=>'upload_no_file_types');
    	}
    	foreach ($allowed_types as $val){
    		$mime = $this->mimes_types(strtolower($val));
    		if (is_array($mime)){
    			// 检查文件格式
    			if($upload_type != $val){
    				continue;
    			}
    			else{
    				if (in_array($file_type, $mime, TRUE)){
    					return array('result'=>true,'msg'=>'');;
    				}
    			}
    		}
    		else{
    			if ($mime == $file_type){
    				return array('result'=>true,'msg'=>'');;
    			}
    		}
    	}
    	return array('result'=>false,'msg'=>'文件类型不合法');;
    }
    
    /**
     * 判断文件大小
     * @param string $field file字段
     * @param int $max_size 最大大小
     * @return boolean
     */
    function is_allowed_filesize($field,$max_size)
    {
    	$file_size = $_FILES[$field]['size'];
    	$file_size = round($file_size/1024, 2);
    	if ($max_size != 0  AND  $file_size > $max_size){
    		return FALSE;
    	}
    	else{
    		return TRUE;
    	}
    }
    
}
?>
