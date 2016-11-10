<?php
/**
 * @Name      DateHandler.php
 * @Note      日期处理类
 * @Author    jbxie
 * @Created   2014年10月10日14:17:15
 * @Version   v.1.0.0
 *
 */
class DateHandler 
{
    /**
     * 获取两日期间隔几天
     * @param string $start_dt 开始日期 （格式：2014-10-08）
     * @param string $end_dt 结束日期 （格式：2014-10-09）
     * @return number 相隔天数
     */
	public static function  getDateDiffDays($start_dt,$end_dt)
	{
		$date_diff = round((strtotime($end_dt)-strtotime($start_dt))/(24*60*60));
		return $date_diff;
	}
	
	/**
	 * 获取指定日期所在月的开始日期与结束日期
	 * @param stirng $date 指定的日期
	 * @return array
	 */
	public static function getMonthRange($date)
	{
		$ret=array();
		$timestamp=strtotime($date);
		$mdays=date('t',$timestamp);
		$ret['sdate']=date('Y-m-01 00:00:00',$timestamp);
		$ret['edate']=date('Y-m-'.$mdays.' 23:59:59',$timestamp);
		return $ret;
	}

	/**
	 *  获取指定日期的前多少天
	 *  支持的时间格式：'Y-m-d'
	 *  add by zcyue 2016-7-4
	 * @param $date string 指定日期
	 * @param $num int 天数
	 */
	public static function getPriorDate($date, $num)
	{
		// 解析出年份
		$year = substr($date, 0, 4);
		// 解析出月份
		$mon = substr($date, 5, 2);
		// 解析出日
		$day = substr($date, 8, 2);
		$prior_date = mktime(0, 0, 0, $mon, intval($day)-$num, $year);
		//得到前$days 天的日期
		$prior_date = date('Y-m-d', $prior_date);
		return $prior_date;
	}

	/**
	 *  获取日期的前一个月
	 *  支持的时间格式：'Y-m-d'
	 *  add by zcyue 2016-7-4
	 * @param $date
	 * @return bool|string
	 */
	public static function getPriorMonth($date, $num)
	{
		// 解析出年份
		$year = substr($date, 0, 4);
		// 解析出月份
		$mon = substr($date, 5, 2);
		$prior_month = mktime(0, 0, 0, intval($mon)-$num, 1, $year);
		//得到上一个月
		$prior_month = date('Y-m-d', $prior_month);
		return $prior_month;
	}

	/**
	 *  获取日期的下一个月
	 *  支持的时间格式：'Y-m-d'
	 *  add by zcyue 2016-7-4
	 * @param $date
	 * @return bool|string
	 */
	public static function getNextMonth($date, $num)
	{
		// 解析出年份
		$year = substr($date, 0, 4);
		// 解析出月份
		$mon = substr($date, 5, 2);
		$next_month = mktime(0, 0, 0, intval($mon)+$num, 1, $year);
		//得到下一个月
		$next_month = date('Y-m-d', $next_month);
		return $next_month;
	}

	/**
	 *  获取最近一个星期四的日期。
	 *  add by zcyue 2016-08-04
	 * @return bool|string 如果今天大于本周星期四，则返回本周四；如果小于星期四，则返回上周四
	 */
	public static function getLatestThursday()
    {
        if (date('l',time()) == 'Thursday') return date('Y-m-d',strtotime('this thursday'));
        return date('Y-m-d',strtotime('-1 week this thursday'));
    }

	public static function getLatestMonday()
	{
		if (date('l',time()) == 'Monday') return date('Y-m-d',strtotime('this monday'));
		return date('Y-m-d',strtotime('-1 week this monday'));
	}

	/**
	 *  获取最近一次星期几
	 * @param $week_day_str string 枚举值：Monday|...|Thursday|...
	 * @return bool|string
	 */
	public static function getLatestWeekDay($week_day_str)
	{
		if (date('l',time()) == $week_day_str) return date('Y-m-d',strtotime('this '.$week_day_str));
		return date('Y-m-d',strtotime('-1 week this '.$week_day_str));
	}

}
?>
