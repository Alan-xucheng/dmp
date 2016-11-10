<?php
// 默认的自动加载项,放置在helper目录下
$config["auto_helper"] = array(
    "url",
    "page",
	"util"
);

// 网站相关配置
if (SPRING_ENVIRONMENT == 'dev')
{
    $config["domain"] = "http://dmp.cmvideo.cn:4013";
}
elseif (SPRING_ENVIRONMENT == 'publishing')
{
    $config["domain"] = "http://60.166.12.158:5077";
}
elseif (SPRING_ENVIRONMENT == 'publish')
{
    $config["domain"] = "http://dmp.ad.cmvideo.cn";
}
// 页大小
$config['page_size_arr']= array(
    'page_size_5'=> 5,
    'page_size_10'=> 10,
    'page_size_15'=> 15,
    'page_size_24'=> 24,
    'default'=>10
);