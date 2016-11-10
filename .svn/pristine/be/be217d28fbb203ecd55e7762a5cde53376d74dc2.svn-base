<?php

/**
 * Created by PhpStorm.
 * User: zhangqi
 * Date: 16/1/23
 * Time: 下午3:22
 */
class ErrorLog {
    public static function doOutput($title,$message,$type = "php_error"){
        $message = "[{$title}]".$message;
        self::log($message,$type);
    }
    public static function log($message,$type){
        $msg = '[' . date('Y-m-d H:i:s').']'.$message."\r\n";
        $filename = SpringConstant::APP_PATH . "/log/{$type}.log";
        $fp = fopen($filename, 'a');
        fwrite($fp, $msg);
        fclose($fp);
    }

}