<?php
/**
 * http请求类，用来获取所有http请求相关的参数
 */
class Request extends SingletonBase
{
    /**
     * 包含所有get请求字段的数组
     */
    private static $get = Array();
    private static $raw_get = Array();

    /**
     * 包含所有post请求字段的数组
     */
    private static $post = Array();
    private static $raw_post = Array();

    /**
     * 包含所有请求字段的数组
     */
    private static $request = Array();
    private static $raw_request = Array();

    /**
     * 客户端访问的ip地址
     */
    public static $client_ip = '';

    /**
     * 上一次访问地址
     */
    public static $referer = '';

    /**
     * 当前访问地址
     */
    public static $url = '';

    /**
     * 获取请求参数
     * @param string $name 请求参数名称
     */
    public static function params($name = '')
    {
        if ($name == '')
            return self::$request;
        $param = self::$request[$name];
        if(is_string($param)){
            return trim($param);
        }
        return $param;
    }

    /**
     * 获取表单请求参数
     * @param string $name 请求参数名称
     */
    public static function form($name = '')
    {
        if ($name == '')
            return self::$post;
        return self::$post[$name];
    }

    /**
     * 获取url请求参数
     * @param string $name 请求参数名称
     */
    public static function query($name = '')
    {
        if ($name == '')
            return self::$get;
        return self::$get[$name];
    }
    /**
     * 获取请求参数
     * @param string $name 请求参数名称
     */
    public static function rawparams($name = '')
    {
        if ($name == '')
            return self::$raw_request;
        $param = self::$raw_request[$name];
        if(is_string($param)){
            return trim($param);
        }
        return $param;
    }

    /**
     * 获取表单请求参数
     * @param string $name 请求参数名称
     */
    public static function rawform($name = '')
    {
        if ($name == '')
            return self::$raw_post;
        return self::$raw_post[$name];
    }

    /**
     * 获取url请求参数
     * @param string $name 请求参数名称
     */
    public static function rawquery($name = '')
    {
        if ($name == '')
            return self::$raw_get;
        return self::$raw_get[$name];
    }

    /**
     * 获取页面全地址
     */
    public static function getFullUrl()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
        return urlencode($sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url);
    }

    /**
     * 加载
     */
    protected static function oncreate()
    {
        $security = new Security();
        //对请求参数进行处理
        foreach ($_GET as $key => $value)
        {
            self::$get[$key] = $security->xss_clean($value);
            self::$raw_get[$key] = $value;
        }
        foreach ($_POST as $key => $value)
        {
            self::$post[$key] = $security->xss_clean($value);
            self::$raw_post[$key] = $value;
        }
        foreach ($_REQUEST as $key => $value)
        {
            self::$request[$key] = $security->xss_clean($value);
            self::$raw_request[$key] = $value;
        }

        self::$client_ip = $_SERVER['REMOTE_ADDR'];
        self::$referer = $_SERVER['HTTP_REFERER'];
        self::$url = $_SERVER['REQUEST_URI'];
    }

}

Request::create();
