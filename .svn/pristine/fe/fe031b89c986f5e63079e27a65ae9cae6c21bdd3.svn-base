<?php

/**
 * Created by PhpStorm.
 * User: zhangqi
 * Date: 16/1/23
 * Time: 下午3:34
 */
class StringUtil {
    /**
     * 判断email地址是否有效
     * @param $address 待检测邮件地址
     * @return 返回真假
     */
    public static function isVaildMailAddress($address)
    {
        if (preg_match("/^[\w\/\(\)\-\.]+@[\w\-]+\.[\w\-]+/i", $address))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    /**
     * 判断是否全是数字
     * @param $str 待检测字符串
     * @return 返回真假
     */
    public static function checkNumber($str)
    {
        return $result = preg_match("/^[0-9\-]+$/",$str);
    }

    /**
     * 判断是否是IP地址类型
     * @param $str 待检测字符串
     * @return 返回真假
     */
    public static function checkIP($str)
    {
        return $result = preg_match("/^(\d)[1-3]\.(\d)[1-3]\.(\d)[1-3]\.(\d)[1-3]$/",$str);
    }

    /**
     * 判断是否为手机号码
     * @param $str 待检测字符串
     * @return 返回真假
     */
    public static function checkTel($str)
    {
        return $result = preg_match("/^(131|132|133|134|135|136|137|138|139|140|141|142|143|144|145|146|147|148|149|150|151|152|153|154|155|156|157|158|159|180|181|182|183|184|185|186|187|188|189)[0-9]{8}+$/",$str);
    }

    /**
     * 去除sql语句特殊符号
     * @param $str 待处理字符串
     * @return 处理之后的字符串
     */
    public static function stripMysqlSlashesLike5C($str) {

        if (defined("MYSQL_USE_5C_ESCAPE") && (!MYSQL_USE_5C_ESCAPE))
        {
            return $str;
        }

        $search_array = array("%81%5C%5C","%83%5C%5C","%84%5C%5C","%87%5C%5C",
            "%89%5C%5C","%8A%5C%5C","%8B%5C%5C","%8C%5C%5C",
            "%8D%5C%5C","%8E%5C%5C","%8F%5C%5C","%90%5C%5C",
            "%91%5C%5C","%92%5C%5C","%93%5C%5C","%94%5C%5C",
            "%95%5C%5C","%96%5C%5C","%97%5C%5C","%98%5C%5C",
            "%99%5C%5C","%9A%5C%5C","%9B%5C%5C","%9C%5C%5C",
            "%9D%5C%5C","%9E%5C%5C","%9F%5C%5C","%E0%5C%5C",
            "%E1%5C%5C","%E2%5C%5C","%E3%5C%5C","%E4%5C%5C",
            "%E5%5C%5C","%E6%5C%5C","%E7%5C%5C","%E8%5C%5C",
            "%E9%5C%5C","%EA%5C%5C","%FA%78%5C","%FB%78%5C"
        );

        $replace_array = array("%81%5C","%83%5C","%84%5C","%87%5C",
            "%89%5C","%8A%5C","%8B%5C","%8C%5C",
            "%8D%5C","%8E%5C","%8F%5C","%90%5C",
            "%91%5C","%92%5C","%93%5C","%94%5C",
            "%95%5C","%96%5C","%97%5C","%98%5C",
            "%99%5C","%9A%5C","%9B%5C","%9C%5C",
            "%9D%5C","%9E%5C","%9F%5C","%E0%5C",
            "%E1%5C","%E2%5C","%E3%5C","%E4%5C",
            "%E5%5C","%E6%5C","%E7%5C","%E8%5C",
            "%E9%5C","%EA%5C","%FA%78","%FB%78"
        );

        $encoded = rawurlencode($str);

        $tmp = str_replace($search_array, $replace_array, $encoded);

        $raw = rawurldecode($tmp);

        return $raw;

    }

    /**
     * 去除sql语句特殊符号
     * @param $str 待处理字符串
     * @return 处理之后的字符串
     */
    public static function escapeMySQL5CwithCP932($str)
    {

        $search_array = array("%81%5C","%83%5C","%84%5C","%87%5C",
            "%89%5C","%8A%5C","%8B%5C","%8C%5C",
            "%8D%5C","%8E%5C","%8F%5C","%90%5C",
            "%91%5C","%92%5C","%93%5C","%94%5C",
            "%95%5C","%96%5C","%97%5C","%98%5C",
            "%99%5C","%9A%5C","%9B%5C","%9C%5C",
            "%9D%5C","%9E%5C","%9F%5C","%E0%5C",
            "%E1%5C","%E2%5C","%E3%5C","%E4%5C",
            "%E5%5C","%E6%5C","%E7%5C","%E8%5C",
            "%E9%5C","%EA%5C","%FA%78","%FB%78"
        );

        $replace_array = array("%81%FF","%83%FF","%84%FF","%87%FF",
            "%89%FF","%8A%FF","%8B%FF","%8C%FF",
            "%8D%FF","%8E%FF","%8F%FF","%90%FF",
            "%91%FF","%92%FF","%93%FF","%94%FF",
            "%95%FF","%96%FF","%97%FF","%98%FF",
            "%99%FF","%9A%FF","%9B%FF","%9C%FF",
            "%9D%FF","%9E%FF","%9F%FF","%E0%FF",
            "%E1%FF","%E2%FF","%E3%FF","%E4%FF",
            "%E5%FF","%E6%FF","%E7%FF","%E8%FF",
            "%E9%FF","%EA%FF","%FA%78","%FB%78"
        );

        $encoded = rawurlencode(trim($str));

        $encoded = str_replace($search_array, $replace_array, $encoded);

        if (strpos($encoded, "%FF") !== false)
        {
            $tmp_list = explode("%FF", $encoded);

            $tmp_list2 = array();
            for ($x = 0; $x < count($tmp_list); $x++)
            {

                if ($x == count($tmp_list) - 1)
                {
                    $tmp = $tmp_list[$x];
                }
                else
                {
                    $tmp = $tmp_list[$x] . "%FF";
                }

                array_push($tmp_list2,  $tmp);
            }

        }
        else
        {
            $tmp_list2 = array($encoded);
        }

        $count = 0;

        $tmp_result = "";
        foreach ($tmp_list2 as $tmp)
        {
            $count++;

            $tail = "";

            while (true)
            {
                if ((strlen($tmp) >= 6) && ((substr($tmp, -3) == "%5C") || (substr($tmp, -3) == "%27")))
                {
                    $tail = substr($tmp, -3) . $tail;
                    $tmp = substr($tmp, 0, strlen($tmp) - 3);
                }
                else
                {
                    break;
                }
            }

            $add_escape = false;
            if ((strlen($tmp) >= 6) && (substr($tmp, -3) == "%FF"))
            {
                $x = -3;
                $ok_count = 0;
                while (true)
                {
                    $chr = substr($tmp, $x -3, $x);

                    if ($chr == "")
                    {
                        break;
                    }

                    if (!StringUtil::isSJISFirstByte($chr))
                    {
                        break;
                    }
                    else
                    {
                        $ok_count++;
                    }

                    $x -= 3;
                }


                if (($ok_count > 0) && ($ok_count % 2 == 1 ))
                {

                }
                else
                {
                    $add_escape = true;
                }

            }
            else
            {

            }

            $tmp = str_replace("%5C", "%5C%5C", $tmp);
            $tail = str_replace("%5C", "%5C%5C", $tail);
            $tmp = str_replace("%27", "%5C%27", $tmp);
            $tail = str_replace("%27", "%5C%27", $tail);

            $tmp = str_replace($replace_array, $search_array, $tmp);

            if ($add_escape)
            {
                $tmp .= "%5C";
            }

            $tmp_result .= $tmp . $tail;
        }

        $raw = rawurldecode($tmp_result);

        return $raw;
    }

    /**
     * 增加sql语句转义符
     * @param $str 待处理字符串
     * @return 处理之后的字符串
     */
    public static function setMySQLMagicQuotes($string)
    {

        if (defined("MYSQL_USE_5C_ESCAPE") && (!MYSQL_USE_5C_ESCAPE))
        {
            $raw = $string;

            $raw = StringUtil::escapeMySQL5CwithCP932($raw);

        }
        else
        {
            $encoded = rawurlencode($string);

            $tmp = str_replace("%5C", "%5C%5C", $encoded);
            $raw = rawurldecode($tmp);

            $raw = str_replace("'", "\\'", $raw);
            $raw = str_replace("\"", "\\\"", $raw);
        }

        return $raw;
    }
    public static function toCamel($name){
        $arr = explode("_",$name);
        $re = "";
        foreach($arr as $tmp){
            $re .= ucfirst($tmp);
        }
        return $re;
    }
}