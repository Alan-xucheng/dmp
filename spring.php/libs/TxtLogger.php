<?php
/**
 * 文本日志操作类
 */
class TxtLogger
{
    /**
     * 记录日志
     * @param string $msg 日志内容
     * @param string $type 日志类型
     */
    public static function log($msg, $type = '')
    {
        $msg = '[' . date('Y-m-d H:i:s') . ']' . $msg;
        if ($type)
            $msg = '[' . $type . ']' . $msg;
        $msg .= "\r\n-------------------------------------------------";
        $filename = SpringConstant::APP_PATH . '/log/php_error.log';
        $fp = fopen($filename, 'a');
        fwrite($fp, $msg);
        fclose($fp);
    }

    public static function appLog($msg){
        $msg = '[' . date('Y-m-d H:i:s').']'.$msg."\r\n";
        $filename = SpringConstant::APP_PATH . '/log/app.log';
        $fp = fopen($filename, 'a');
        fwrite($fp, $msg);
        fclose($fp);
    }
    
    /**
     * 上传日志
     * @param string $msg 日志信息
     */
    public static function UploadLog($msg){
    	$time = date('Y-m-d H:i:s');
    	$log_content =  "[".$time."] desc:".$msg;
    	$filename = SpringConstant::APP_PATH . '/log/upload.log';
    	file_put_contents($filename,$log_content."\r\n",FILE_APPEND);
    }
    
}
