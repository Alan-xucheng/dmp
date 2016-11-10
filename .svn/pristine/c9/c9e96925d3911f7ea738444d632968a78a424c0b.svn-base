<?php
/**
 * http输出类
 */
class Response extends SingletonBase
{
    /**
     * smarty控件
     */
    private static $smarty;

    /**
     * 加载
     */
    protected static function oncreate()
    {
        self::$smarty = new SpringSmarty();
    }

    /**
     * 向模板写入数据
     * @param mixed $tpl_var 数据集或数据的名称
     * @param mixed 数据
     * @param boolean $nocache
     */
    public static function assign($tpl_var, $value = null, $nocache = false)
    {
        self::$smarty -> assign($tpl_var, $value, $nocache);
    }

    /**
     * 跳转到指定控制器
     * @param string $url 跳转的控制器路径
     */
    public static function display($template)
    {
        self::$smarty -> display($template);
    }

    /**
     * 跳转到指定页面
     * @param string $url 跳转到的地址
     * @param string $method 跳转方法
     * @param int $http_response_code 页面状态码
     */
    public static function redirect($url, $method = 'location', $http_response_code = 302)
    {
        if (!preg_match('/^http:\\/\\//i', $url))
        {
            $url = '/' . $url;
        }
        if ($method == 'refresh')
        {
            header('Refresh:0;url=' . $url);
        }
        else
        {
            header('location:' . $url, TRUE, $http_response_code);
        }
        exit ;
    }

    /**
     * 向页面写入内容
     * @param string $txt 写入的内容
     */
    public static function write($txt)
    {
        echo $txt;
    }

}

Response::create();
