<?php
//-获取环境配置开始
if ($_SERVER["SERVER_NAME"] == 'dmp.cmvideo.cn' && $_SERVER["SERVER_PORT"] == 4013)
{
    define("SPRING_ENVIRONMENT", 'dev'); // 开发环境
}
elseif ($_SERVER["SERVER_NAME"] == '60.166.12.158')
{
    define("SPRING_ENVIRONMENT", 'publishing'); // 发布中环境
}
elseif ($_SERVER["SERVER_NAME"] == 'dmp.ad.cmvideo.cn')
{
    define("SPRING_ENVIRONMENT", 'publish'); // 发布环境
}
//-获取环境配置结束

class SpringConfig
{
    /**
     * 应用开发目录名
     */
    const APP_FOLDER = 'application';

    /**
     * 控制器目录名
     */
    const CONTROLLER_FOLDER = 'controllers';

    /**
     * 通用类目录
     * add by zcyue 2016-6-24 13:50:00
     */
    const COMMON_FOLDER = 'common';

    /**
     * 模型层目录名
     */
    const LIBRARY_FOLDER = 'libraries';

    /**
     * 模型层目录名
     */
    const MODEL_FOLDER = 'models';

    /**
     * 视图层目录名
     */
    const VIEW_FOLDER = 'views';

    /**
     * 视图层目录名
     */
    const CONFIG_FOLDER = 'config';

    /**
     * 网站资源目录名
     */
    const WEBAPP_FOLDER = 'webapp';

    /**
     * 默认的控制器名称
     */
    const CONTROLLER_NAME = 'index';

    /**
     * 默认的控制器行为名
     */
    const ACTION_NAME = 'index';

    /**
     * 环境配置
     * dev:开发模式，每次执行代码都会重新编译，并抛出原始错误提示
     * test:测试模式，只编译一次，并抛出原始错误提示
     * publish:发布模式，只编译一次，并屏蔽原始错误
     */
    const ENVIRONMENT = SPRING_ENVIRONMENT;

    /**
     * 区域配置，网站可能会部署在不同的地方，此配置用来区分这些区域，从而适用不同的配置
     */
    const AREA = SPRING_AREA;
}
