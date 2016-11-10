<?php

/**
 * Created by PhpStorm.
 * User: zhangqi
 * Date: 16/1/25
 * Time: 上午10:19
 */
class ArrayHelper{
    static public function mapping($mapArray,$key,$error = ""){
        if(!self::check($mapArray)){
            return null;
        }
        if(array_key_exists($key,$mapArray)){
            return $mapArray[$key];
        }
        return $error;
    }
    /**
     * 去除数组中的空元素
     * 仅仅为'  ' ,
     * 0,false 皆不算
     */
    static public function filter($array){
        if(!self::check($array)){
            return null;
        }
        return array_filter($array,function($val){
            if(trim($val) === ''){
                return false;
            }
            return true;
        });
    }
    /**
     *
     *
     */
    static public function getSub($array,$subField){
        if(!self::check($array) || !self::check($subField)){
            return null;
        }
        $sub = array();
        foreach ($subField as $field) {
            if(array_key_exists($field, $array)){
                $sub[$field] = $array[$field];
            }
        }
        return $sub;
    }

    /**
     * 从集合中获取某字段的set
     * @param $collection  array 数据集合 e.g ［[id=>1,name=>aaa],[id=>2,name=>bbb]］
     * @param $field string  字段名   e.g id
     * @return array  e.g. [1,2]
     */
    static public function getSetFromCollection($collection,$field){
        $set = array();
        if(is_array($collection) && is_string($field)){
            foreach($collection as $tmp){
                if(is_array($tmp) && array_key_exists($field,$tmp)){
                    $set[] = $tmp[$field];
                }
            }
        }
        return array_unique($set);
    }
    /**
     * 从集合中获取某字段的map
     * @param $collection  array 数据集合 e.g ［[id=>1,name=>aaa],[id=>2,name=>bbb]］
     * @param $key string  字段名   e.g id
     * @param $value string|callable|null
     * @return array  e.g. [1,2]
     */
    static public function getMapFromCollection($collection,$key,$value = null){
        $map = array();
        if(is_array($collection) && is_string($key)){
            foreach($collection as $tmp){
                if(is_array($tmp) && array_key_exists($key,$tmp)){
                    $tmpKey = $tmp[$key];
                    if($value == null){
                        $map[$tmpKey] = $tmp;
                    }elseif(is_string($value)){
                        if(array_key_exists($value,$tmp)) {
                            $map[$tmpKey] = $tmp[$value];
                        }
                    }elseif(is_callable($value)){
                        $map[$tmpKey] = $value($tmp);
                    }
                }
            }
        }
        return $map;
    }
    /**
     * 检查是否合法输入（现为合法array）
     */
    static private function check($array){
        if(!is_array($array)){
            return false;
        }
        return true;
    }
}