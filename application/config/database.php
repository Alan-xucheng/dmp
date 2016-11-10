<?php
/**
 * 数据库配置
 */
$active_group = "default";
$active_record = TRUE;

// DMP基础库
if (SPRING_ENVIRONMENT == 'dev') 
{
    $default_hostname = '172.16.154.62';
    $default_username = 'root';
    $default_password = 'imsp_vcloud';
    $default_database = 'ifly_cpcc_dmp';
}
elseif (SPRING_ENVIRONMENT == 'publishing') 
{
    $default_hostname = '127.0.0.1';
    $default_username = 'ifly_ad';
    $default_password = 'ifly_ad!@#';
    $default_database = 'ifly_cpcc_dmp';
}
elseif (SPRING_ENVIRONMENT == 'publish') 
{
    $default_hostname = '10.200.63.163';
    $default_username = 'ifly_ad';
    $default_password = 'ifly_ad!@#...';
    $default_database = 'ifly_cpcc_dmp';
}
$db['default']['hostname'] = $default_hostname;
$db['default']['username'] = $default_username;
$db['default']['password'] = $default_password;
$db['default']['database'] = $default_database;
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";
