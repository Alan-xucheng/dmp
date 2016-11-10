#!/opt/php
<?php
set_time_limit(0);
ini_set('memory_limit', -1);

if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');

define('CMD', 1);
unset($argv[0]);
$_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'] = '/' . implode('/', $argv) . '/';
$_SERVER["SERVER_NAME"] = 'dmp.cmvideo.cn';
$_SERVER["SERVER_PORT"] = '4013';
$_SERVER["SERVER_ADDR"] = '172.16.154.106';
include ("/var/www/gnome2.0/cmvideo_dmp2.0/spring.php/boot.php");