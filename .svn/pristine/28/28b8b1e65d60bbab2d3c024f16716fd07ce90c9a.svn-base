<?php
require ('smarty/Smarty.class.php');
/**
 * smarty扩展
 * @author jjchen
 */
class SpringSmarty extends Smarty
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this -> template_dir = SpringConstant::VIEW_PATH . '/';
        $this -> compile_dir = __DIR__ . '/smarty/compile/';
        $this -> config_dir = __DIR__ . '/smarty/configs/';
        $this -> cache_dir = __DIR__ . '/smarty/cache/';
        $this -> left_delimiter = '<{';
        $this -> right_delimiter = '}>';
        $this->debugging_ctrl = 'URL';
    }
}
?>