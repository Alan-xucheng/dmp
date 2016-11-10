<?php
define('SPRING_APP_PATH', spring_path_strict('../' . SpringConfig::APP_FOLDER));
define('SPRING_WEBAPP_PATH', spring_path_strict('../' . SpringConfig::WEBAPP_FOLDER));
define('SPRING_RELEASE_PATH', SPRING_APP_PATH . '/__release');
define("SPRING_CONTROLLER_PATH", SPRING_APP_PATH . '/' . SpringConfig::CONTROLLER_FOLDER);
define("SPRING_LIBRARY_PATH", SPRING_APP_PATH . '/' . SpringConfig::LIBRARY_FOLDER);
define("SPRING_MODEL_PATH", SPRING_APP_PATH . '/' . SpringConfig::MODEL_FOLDER);
define("SPRING_VIEW_PATH", SPRING_APP_PATH . '/' . SpringConfig::VIEW_FOLDER);
define("SPRING_CONFIG_PATH", SPRING_APP_PATH . '/' . SpringConfig::CONFIG_FOLDER);

/**
 * 常量类定义，包含spring框架的所有常量
 */
class SpringConstant
{
    /**
     * 框架代码根路径
     */
    const SPRING_PATH = __DIR__;

    /**
     * 应用开发根路径
     */
    const APP_PATH = SPRING_APP_PATH;

    /**
     * 控制器文件路径
     */
    const CONTROLLER_PATH = SPRING_CONTROLLER_PATH;

    /**
     * 库文件路径
     */
    const LIBRARY_PATH = SPRING_LIBRARY_PATH;

    /**
     * 模型文件路径
     */
    const MODEL_PATH = SPRING_MODEL_PATH;

    /**
     * 视图文件路径
     */
    const VIEW_PATH = SPRING_VIEW_PATH;

    /**
     * 配置文件路径
     */
    const CONFIG_PATH = SPRING_CONFIG_PATH;

    /**
     * 网站资源根路径
     */
    const WEBAPP_PATH = SPRING_WEBAPP_PATH;

    /**
     * 编译后的代码发布路径
     */
    const RELEASE_PATH = SPRING_RELEASE_PATH;

    /**
     * 编译的类名前缀
     */
    const CLASS_PRIFIX = '__ref_';
}

/**
 * 获取相对spf路径的严格路径字符串
 */
function spring_path_strict($path)
{
    $path = str_replace('\\', '/', $path);
    $path = trim($path, '/');
    $pathes = explode('/', $path);
    $mypath = str_replace('\\', '/', __DIR__);
    $mypath = rtrim($mypath, '/');
    foreach ($pathes as $key => $value)
    {
        if ($value == '..')
        {
            $index = strrpos($mypath, '/');
            $mypath = substr($mypath, 0, $index);
        }
        else if ($value == '.')
        {

        }
        else
        {
            $mypath .= '/' . $value;
        }
    }
    return $mypath;
}
