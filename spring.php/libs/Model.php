<?php
/**
 * 模型层基类，提供最基本的模型操作
 * @author jjchen
 */
class Model
{
    /**
     * 模型自动加载器
     * @deprecated
     */
    protected $load;

    /**
     * 缓存配置
     */
    private static $config_list;

    public function __construct()
    {

        $this -> load = new Loader($this);
        $this -> load -> helper($this -> config('auto_helper'));
    }
    
    /**
     * 获取配置信息
     * @param string $item 配置项名称
     * @param string $type 配置类型，与config目录下文件名匹配，默认为config
     * @return mixed
     */
    public function config($item, $type = 'config')
    {
        if (self::$config_list[$type])
        {
            return self::$config_list[$type][$item];
        }
        $filename = SpringConstant::CONFIG_PATH . '/' . $type . '.php';
        if (file_exists($filename))
        {
            include ($filename);
        }
        else
        {
            $filename = SpringConstant::CONFIG_PATH . '/' . $type . 'inc';
            if (file_exists($filename))
            {
                include ($filename);
            }
        }
        self::$config_list[$type] = $config;
        return $config[$item];
    }

    /**
     * 抛出一个错误
     * @param string $message 错误信息
     * @param int $code 错误编码
     * @param int $level 错误级别，默认为0，级别0代表些异常将发送到前台由前台处理，为1则由框架直接处理
     */
    public function error($message, $code, $level = 0)
    {
        if ($level == 0)
        {
            throw new UserExeption($message, $code);
        }
        throw new Exception($message, $code);
    }

}

class Loader
{
    private $inst;
    public function __construct($inst)
    {
        $this -> inst = $inst;
    }

    public function model($model)
    {
        $index = strrpos($model, '/');
        if ($index !== FALSE)
        {
            $model = substr($model, $index + 1);
        }
        $this -> inst -> $model = new $model();
    }

    public function helper($helpers)
    {
        if (!is_array($helpers))
        {
            $helpers = Array($helpers);
        }
        if (!empty($helpers)) 
        {
        	foreach ($helpers as $helper)
        	{
        		include_once (SpringConstant::APP_PATH . '/helpers/' . $helper . '_helper.php');
        	}	
        }
    }

}
