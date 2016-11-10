<?php
/**
 * 单例基类
 */
class SingletonBase
{
    /**
     * 是否已加载
     */
    private static $loaded_classes = Array();

    /**
     * 加载，此方法只在第一次调用有效
     */
    public static function create()
    { 
        $className = get_called_class();
        if (!in_array($className, self::$loaded_classes))
        {
            array_push(self::$loaded_classes, $className);
            static::oncreate();
        }
    }

}

/**
 * 自动加载模块
 */
class SpringAutoLoad extends SingletonBase
{
    /**
     * 映射代码内容
     * @param string $contents 原始代码内容
     * @return string 映射后的代码内容
     */
    private static function mapContents($contents)
    {
        $contents = preg_replace('/^<\\?php/', "\r\n", $contents);
        $start = strpos($contents, '/**');
        $end = strpos($contents, '*/');
        $comments = substr($contents, $start, $end - $start);
        if (preg_match('/^\\s*\\s*@aop/', $comments, $match) == 0)
        {
            return $contents;
        }
        preg_match('/class\\s+([a-zA-Z0-9_]+)/', $contents, $match);
        $contents = preg_replace('/class\\s+([a-zA-Z0-9_]+)/', 'class ' . SpringConstant::CLASS_PRIFIX . '$1', $contents);
        $classBody = <<<EOT
$match[0] extends SpringAopBase
{
    public function __construct()
    {
        \$this->class_name='__ref_$match[1]';
        parent::__construct();
    }

    public static function __callStatic(\$name, \$arguments)
    {
        return call_user_func_array(array('__ref_$match[1]', \$name), \$arguments);
    }               
}
EOT;
        $contents = "<?php\r\n" . $classBody . $contents;
        return $contents;
    }

    /**
     * 查找目录中的文件并形成与类的映射关系
     * @param string $dir 目录名
     * @param boolean $proxy 是否被托管
     * @param int $depth 遍历深度
     */
    private static function mapDir($path, $dir, $proxy = FALSE, $depth = 0)
    {
        //创建发布目录
        $release_path = SpringConstant::RELEASE_PATH . $dir;
        if (!file_exists($release_path) && $proxy)
        {
            mkdir($release_path);
        }

        $fullpath = $path . $dir;
        $maps = "";

        //得到该文件下的所有文件和文件夹并遍历处理
        $list = scandir($fullpath);
        //遍历
        foreach ($list as $file)
        {
            //生成路径
            if ($file != "." && $file != "..")
            {
                $file_location = $fullpath . '/' . $file;
                if (is_file($file_location))
                {
                    $file = strtolower($file);
                    $index = stripos($file, '.');
                    $ext = substr($file, $index);
                    if ($ext == '.inc' || $ext == '.php')
                    {
                        $file = substr($file, 0, $index);
                        $file_location = str_replace('\\', '/', $file_location);
                        if ($proxy)
                        {
                            $contents = file_get_contents($file_location);
                            $contents = self::mapContents(trim($contents));
                            $file_location = $release_path . '/' . $file . $ext . 'x';
                            file_put_contents($file_location, $contents);
                        }
                        $maps .= '$spring_class_maps[\'' . $file . '\'] = \'' . $file_location . '\';';
                    }
                }
                else
                {
                    if ($depth > 0)
                    {
                        $maps .= self::mapDir($path, $dir . '/' . $file, $proxy, $depth - 1);
                    }
                }
            }
        }
        return $maps;
    }

    /**
     * 映射文件和类并缓存入临时文件中
     */
    private static function map()
    {
        //生成类型映射表
        $filename = SpringConstant::RELEASE_PATH . '/config_spring_class_maps.inc';
        if (!file_exists($filename))
        {
            //定义并创建临时目录
            if (!file_exists(SpringConstant::RELEASE_PATH))
            {
                mkdir(SpringConstant::RELEASE_PATH, 0755);
            }
        }

        if (SpringConfig::ENVIRONMENT == 'dev' || !file_exists($filename))
        {
            $maps = '<?php global $spring_class_maps;$spring_class_maps=array();';
            $maps .= self::mapDir(SpringConstant::SPRING_PATH, '/libs');
            $maps .= self::mapDir(SpringConstant::APP_PATH, '/' . SpringConfig::MODEL_FOLDER, FALSE, 999);
            $maps .= self::mapDir(SpringConstant::APP_PATH, '/' . SpringConfig::LIBRARY_FOLDER, FALSE, 999);
            $maps .= self::mapDir(SpringConstant::APP_PATH, '/' . SpringConfig::COMMON_FOLDER, FALSE, 999);
            if (file_exists($filename))
            {
                if ($maps != file_get_contents($filename))
                {
                    file_put_contents($filename, $maps);
                }
            }
            else
            {
                file_put_contents($filename, $maps);
            }
        }
        include ($filename);
    }

    /**
     * 自动加载函数，用于添加对业务类的动态加载支持
     * @param string $className 类名称
     */
    private static function handle($className)
    {
        global $spring_class_maps;
        //从索引中查找
        $className = strtolower($className);
        $className = str_replace('\\', '_', $className);

        if (isset($spring_class_maps[$className]) && file_exists($spring_class_maps[$className]))
        {
            include_once ($spring_class_maps[$className]);
        }
    }

    /**
     * 加载
     */
    protected static function oncreate()
    {
        self::map();
        spl_autoload_register('SpringAutoLoad::handle');
    }

}

SpringAutoLoad::create();
