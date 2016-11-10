<?php

/**
 * * @Name        TagProductionCache.php
 * * @Note        标签产品映射缓存
 * * @Author      zcyue
 * * @Created     2016年6月27日 20:50:00
 * * @Version     v1.0.0
 * */

class TagProductionCache extends Cache
{

    /**
     * 添加数据
     */
    public static function putTagProduction($tag_id, $tag_production_arr)
    {
        self::put($tag_id, $tag_production_arr, 'tag_production_cache.inc');
    }

    /**
     * 获取数据
     */
    public static function getTagProduction($tag_id)
    {
        return self::get($tag_id, 'tag_production_cache.inc');
    }

    /**
     * 初始化标签缓存
     */
    public static function initTagProductionCache($tag_production_arr)
    {
        self::clear('tag_production_cache.inc');
        if(!empty($tag_production_arr))
        {
            foreach($tag_production_arr as $key=>$tag_production)
            {
                self::put($key, $tag_production, 'tag_production_cache.inc');
            }
        }
    }


}

class Cache
{

    protected static function getCacheFilePath($name)
    {
        //生成类型映射表
        $filename = SpringConstant::RELEASE_PATH . '/'.$name;
        if (!file_exists($filename))
        {
            //定义并创建临时目录
            if (!file_exists(SpringConstant::RELEASE_PATH))
            {
                mkdir(SpringConstant::RELEASE_PATH, 0755);
            }
        }
        return $filename;
    }

    protected static function put($id, $data, $cache_file_name)
    {
        $filename = self::getCacheFilePath($cache_file_name);
        $cache = self::getCacheMap($filename);
        $content = '<?php global $cache_map;$cache_map=array();';
        foreach($cache as $key=>$value)
        {
            $content .= '$cache_map[\'' . $key . '\'] = \'' . json_encode($value) . '\';';
        }
        $content .= '$cache_map[\'' . $id . '\'] = \'' . json_encode($data) . '\';';
        file_put_contents($filename, $content);
    }

    protected static function get($id, $cache_file_name)
    {
        $filename = self::getCacheFilePath($cache_file_name);
        if (!file_exists($filename))
        {
            return null;
        }else{
            include ($filename);
            global $cache_map;
            $cache_data = json_decode($cache_map[$id], true);
            return $cache_data;
        }
    }

    protected static function clear($cache_file_name)
    {
        $filename = self::getCacheFilePath($cache_file_name);
        if (file_exists($filename))
        {
            return @unlink ($filename);
        }
    }

    protected static function getCacheMap($filename)
    {
        if (!file_exists($filename))
        {
            return array();
        }else{
            include ($filename);
            global $cache_map;
            if(empty($cache_map)) return array();
            foreach($cache_map as $key=>$value)
            {
                $cache_map[$key] = json_decode($value, true);
            }
            return $cache_map;
        }
    }

}
