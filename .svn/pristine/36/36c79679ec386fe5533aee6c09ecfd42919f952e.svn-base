<?php
class SpringAopBase
{
    /**
     * 当前正面对象
     */
    private $origin;

    /**
     * 当前正面类型反射
     */
    private $refect_class;

    /**
     * 当前正面的类名称
     */
    protected $class_name;

    public function __construct()
    {
        SpringError::resume();
        $this -> origin = new $this->class_name();
        SpringError::suspend();
        $this -> refect_class = new ReflectionClass($this -> class_name);
    }

    public function __set($name, $value)
    {
        $this -> origin -> $name = $value;
    }

    public function __get($name)
    {
        return $this -> origin -> $name;
    }

    public function __call($name, $arguments)
    {
        $handlers = array();
        $rm = $this -> refect_class -> getMethod($name);
        $comment = $rm -> getDocComment();
        preg_match_all('/\\*\\s*@([a-zA-Z0-9_]+)\\((.*)\\)/', $comment, $matches);
        foreach ($matches[1] as $key => $value)
        {
            $className = $value . 'Attribute';
            if (class_exists($className, TRUE) && in_array('Attribute', class_parents(SpringConstant::CLASS_PRIFIX . $className)))
            {
                $handler = new $className();
                $handler -> name = $name;
                $handler -> arguments = $arguments;

                //初始化
                try
                {
                    call_user_func_array(array($handler, 'init'), explode(',', $matches[2][$key]));
                    $handler -> before();
                }
                catch(Exception $e)
                {
                }

                $handlers[] = $handler;
            }
        }

        try
        {
            SpringError::resume($handlers);
            $result = call_user_func_array(array($this -> origin, $name), $arguments);
            SpringError::suspend();
        }
        catch(Exception $e)
        {
            $err = Array(type => 1);

            //重载异常位置
            $rc = new ReflectionClass(get_class($e));
            //行号
            $rp = $rc -> getProperty('line');
            $rp -> setAccessible(true);
            $err['line'] = $rp -> getValue($e);

            //文件地址
            $rp = $rc -> getProperty('file');
            $rp -> setAccessible(true);
            $err['file'] = $rp -> getValue($e);

            //消息
            $rp = $rc -> getProperty('message');
            $rp -> setAccessible(true);
            $err['message'] = 'Uncaught exception \'InvalidArgumentException\' with message \'' . $rp -> getValue($e) . '\'';
            ;
            SpringError::handle($err);
            SpringError::suspend();
            exit();
        }

        //后置处理
        foreach ($handlers as $key => $handler)
        {
            try
            {
                $handler -> after($result);
            }
            catch(Exception $e)
            {
            }
        }

        return $result;
    }

}
