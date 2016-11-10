<?php
/**
 * 框架内部错误处理类，仅使用于框架
 */
class SpringError
{
    /**
     * 激活计数器
     */
    private static $counter = 0;

    /**
     * 错误处理句柄
     */
    private static $handlers = Array();

    /**
     * 激活错误处理
     * @param array $handlers 错误处理句柄集合
     */
    public static function resume($handlers = null)
    {
        self::$counter++;
        array_push(self::$handlers, $handlers);
    }

    /**
     * 挂起错误处理
     */
    public static function suspend()
    {
        self::$counter--;
        if (self::$handlers)
        {
            array_pop(self::$handlers);
        }
    }

    /**
     * 错误处理方法
     * @param array $e 错误信息
     */
    public static function handle($e = null)
    {
        //激活数小于0，不进行处理
        if (self::$counter <= 0)
        {
            return;
        }

        if ($e || $e = error_get_last())
        {
            //替换路径
            $file = str_replace('\\', '/', $e['file']);
            $temp_path = SpringConstant::RELEASE_PATH;
            if (strpos($file, $temp_path) === FALSE)
            {
                return;
            }
            if (self::$handlers && ($handlers = array_pop(self::$handlers)))
            {
                foreach ($handlers as $key => $handler)
                {
                    try
                    {
                        $handler -> error($e);
                    }
                    catch(Exception $e)
                    {
                    }
                }
            }

            ob_clean();

            $app_path = SpringConstant::APP_PATH;
            $file = str_replace($temp_path, $app_path, $file);
            $file = preg_replace('/.(inc|php)x$/', '.$1', $file);

            $line = $e['line'] - 14;
            $message = str_replace(SpringConstant::CLASS_PRIFIX, '', $e['message']);

            $log = '<div style="background-color:#f57900;border:solid 1px #000;padding:3px;"><span style="background-color: #cc0000; color: #fce94f; font-size: x-large;">( ! )</span> <pre style="word-break:break-all;word-wrap:break-word;">Fatal error: ' . $message . ' in ' . $file . ' on line <i>' . $line . '</i></pre></div>';
            header("Content-type: text/html; charset=utf8");
            print_r($log);
        }
    }

}
ini_set("error_log",SpringConstant::APP_PATH.'/log/php_error.log');
register_shutdown_function('SpringError::handle');
