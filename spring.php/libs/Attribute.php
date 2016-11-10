<?php
/**
 * 特性基类，主要用于面向切面编程的支持
 */
abstract class Attribute
{
    /**
     * 函数名称
     */
    public $name;

    /**
     * 函数参数列表
     */
    public $arguments;
    
    public function init(){
        
    }
    
    /*
     * 方法执行前执行，可以对参数及操作对象进行修改，并可以截断方法执行并返回结果
     */
    public function before(){
        
    }
    
    /*
     * 方法执行后执行，可以对结果进行修改和处理
     */
    public function after($result){
        
    }
    
    /*
     * 方法抛出异常时执行，可以用来记录错误日志
     */
    public function error($e){
        
    }
}
