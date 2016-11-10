<?php
/**
 * @Name        DbHelper.php
 * @Brief       解析database.php内的配置
 * @Author      taoge
 * @Created     2016/3/5 14:41
 * @Version     V1.0.0
 */
class DbHelper
{
    private static $dbCache;

    public static function databaseName($db_key)
    {
        if(!isset(self::$dbCache))
        {
            $file_path = SpringConstant::CONFIG_PATH.'/database.inc';
            if ( ! file_exists($file_path))
            {
                if ( ! file_exists($file_path = SpringConstant::CONFIG_PATH.'/database.php'))
                {
                    echo 'The configuration file database.php does not exist.';
                }
            }
            include($file_path);
            self::$dbCache = $db;
        }

        return self::$dbCache[$db_key]['database'];
    }
}