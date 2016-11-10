<?php
/**
 * @Name      test_debug.php
 * @Note      测试调试
 * @Author    jbxie
 * @Created   2014年11月29日16:32:12
 * @Version   v1.0.0
 */
function my_debug()
{
	$args = func_get_args();
	foreach ($args as $k => $v)
	{
		 echo '<pre>arg'.($k+1).":\n";
		 var_dump($v)."\n";
	}
}

