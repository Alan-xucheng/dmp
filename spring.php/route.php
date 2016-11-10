<?php
class SpringRoute extends SingletonBase
{
    /**
     * 执行路由操作
     * @param string $filename 路由文件路径
     * @param string $className 控制器类名
     * @param string $action 控制器方法名，默认为index
     */
    private static function execute($filename, $className, $action, $args)
    {
        try
        {
            include ($filename);
            $params = array();
            if (method_exists($className, $action))
            {
                $reflectionMethod = new ReflectionMethod($className, $action);
                $parammeters = $reflectionMethod -> getParameters();
                foreach ($parammeters as $key => $value)
                {
                    $value = explode('[', $value);
                    // $value数组元素数跟方法参数有关
                    $value = explode(' ', trim($value[1], '] '));
                    if (count($value) == 3)
                    {
                        $value = $value[1];
                        $params[] = new $value();
                    }
                    else
                    {
                        $params[] = isset($args) ? array_shift($args) : null;
                    }
                }
                $inst = new $className();
                call_user_func_array(array($inst, $action), $params);
            }
            else
            {
                Response::display('404.html');
                exit ;
            }
        }
        catch(Exception $e)
        {
            //如果是开发或测试环境，则抛出异常
            if (SpringConfig::ENVIRONMENT == 'dev' || SpringConfig::ENVIRONMENT == 'test')
            {
                throw $e;
            }
            //todo:记录日志并退出
            try
            {
                TxtLogger::log($e, 'error');
            }
            catch(Exception $e)
            {
            }
            Response::display('403.html');
            exit ;
        }
    }

    /**
     * 分析路由路径
     * @param string $routes路由路径
     */
    private static function before($className, $routes, $action, $args)
    {
        //获取所有前置处理器并执行
        $filename = SpringConstant::CONFIG_PATH . '/route_before.inc';
        if (file_exists($filename))
        {
            include ($filename);
        }
        else
        {
            $filename = SpringConstant::CONFIG_PATH . '/route_before.php';
            if (file_exists($filename))
            {
                include ($filename);
            }
        }
    }

    /**
     * 分析路由路径
     * @param string $routes路由路径
     */
    private static function after($className, $routes, $action, $args)
    {
        //获取所有前置处理器并执行
        $filename = SpringConstant::CONFIG_PATH . '/route_after.inc';
        if (file_exists($filename))
        {
            include ($filename);
        }
        else
        {
            $filename = SpringConstant::CONFIG_PATH . '/route_after.php';
            if (file_exists($filename))
            {
                include ($filename);
            }
        }
    }

    /**
     * 分析路由路径
     * @param string $routes路由路径
     */
    private static function analysis($pathinfo)
    {
        //判断路径是否合法，只能由/包围字母数字下划线的形式
        // if (!preg_match('/^(\\/[a-z0-9_]+)+$/i', $pathinfo))
        // {
        // throw new Exception('路径只能是字母、数字和下划线是组合');
        // exit ;
        // }
        $ctrlpath = SpringConstant::CONTROLLER_PATH;
        $pathlist = explode('/', trim($pathinfo, '/'));
        $count = count($pathlist);
        $routes = $pathlist[0];
        $i = 0;
        if (!file_exists($ctrlpath . '/' . $routes . '.php'))
        {
            for ($i = 1; $i < $count; $i++)
            {
                $routes .= '/' . $pathlist[$i];

                if (file_exists($ctrlpath . '/' . $routes . '.php'))
                {
                    break;
                }
            }
        }

        //控制器文件查找失败
        if ($i == $count)
        {
            Response::display('404.html');
            exit ;
            retrun;
        }
        $filename = $ctrlpath . '/' . $routes . '.php';
        $className = ucfirst($pathlist[$i++]);
        // $classitems = explode('_', $pathlist[$i++]);
        // $className = '';
        // foreach ($classitems as $item)
        // {
        // $className .= ucfirst($item);
        // }
        if ($i == $count)
        {
            $action = SpringConfig::ACTION_NAME;
        }
        else
        {
            $action = $pathlist[$i++];
            $args = array_slice($pathlist, $i);
        }

        self::before($className, $routes, $action, $args);

        self::execute($filename, $className, $action, $args);
        //让js知道网站的根路径
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")
        {
        }
        else
        {
            //echo '<script type="text/javascript">var __base_url="' . BASE_FULLPATH . '";var __base_page="' . BASE_FILENAME . '";</script>';
        }
        @self::after($className, $routes, $action, $args);
    }

    /**
     * 路由分析和处理函数
     */
    private static function route()
    {
        //计算原始页面路径
        $arr = explode('?', $_SERVER['PHP_SELF']);
        $fullpath = $arr[0];
        if (!empty($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '/')
        {
            //获取路由路径
            $pathinfo = $_SERVER['PATH_INFO'];
            //定义php入口程序路径
            $fullpath = substr($fullpath, 0, strlen($fullpath) - strlen($pathinfo));
            define('BASE_FILENAME', $fullpath);
            $index = strrpos($fullpath, '/');
            $fullpath = substr($fullpath, 0, $index + 1);
            define('BASE_FULLPATH', $fullpath);
            //去除结尾多余的'/'
            $pathinfo = rtrim($pathinfo, '/');
            self::analysis($pathinfo);
        }
        else
        {
            //设置默认控制器
            $pathinfo = '/' . SpringConfig::CONTROLLER_NAME;
            // /index.php
            //定义php入口程序路径
            $fullpath = rtrim($fullpath, '/');
            define('BASE_FILENAME', $fullpath);
            $index = strrpos($fullpath, '/');
            $fullpath = substr($fullpath, 0, $index + 1);
            define('BASE_FULLPATH', $fullpath);
            self::analysis($pathinfo);
        }
    }

    /**
     * 加载
     */
    protected static function oncreate()
    {
        session_start();
        self::route();
    }

}

SpringRoute::create();
