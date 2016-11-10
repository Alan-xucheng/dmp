<?php
/**
 * @Name  page_helper.php
 * @total_num  公用分页函数
 * @Author tiancq
 * @Date 2009-10-21 
 */
 
/**
 * @total_num  总数
 * @page       当前页
 * @per_page   每页显示条数
 * @param      URL参数 如：index.php/admincp/dictionary/dictionary_list/ 
 */
function page_bar($total_num,$page,$per_page,$param)
{
	$pre = $page-1;
	$next = $page+1;
	$pages = ceil($total_num/$per_page);
	
	if ($pages <2 || $per_page == 0)
	{
		return '';
	}
	
    $str = "共".$total_num."条,".$pages."页 ";
	
	if($page<>1)
	{
		$str.="<a href='".$param."&1=1&page=1' target='_self'>首页</a> ";
	}
	
	if($page>1)
	{
	   $str.= " <a href='".$param."&1=1&page=".$pre."' target='_self'>上一页</a> ";
	}
	
	$h = $page-3;
	if($h<1)
	{
	   $h = 1;
	}
	$l = $page+3;
	if($l>$pages){
	   $l = $pages;
	}
	
	for($i=$h;$i<=$l;$i++)
	{
	   if($i==$page)
	   {
			$str.=" <b>".$i."</b> ";
	   }
	   else
	   {
		   $str.=" <a href='".$param."&1=1&page=".$i."' target='_self'>".$i."</a> ";
	   }
	}
	
	if($page<$pages)
	{
	   $str.=" <a href='".$param."&1=1&page=".$next."' target='_self'>下一页</a >";
	}
	
	if($page<>$pages)
	{
		$str.=" <a href='".$param."&1=1&page=".$pages."' target='_self'>尾页</a >";
	}
	
	return $str;
}
 
/**
 * 生成js分页链接代码
 */
function getPagingHtmlProscenium( $totle_count, $pre_page, $page_num, $table_name='data_more_table', $table_id='list_', $pagestr_id='pagination_box', $show_page_num=5 )
{
	$paging_html = null;

	// page处理
	$page_count = ceil($totle_count / $pre_page);
	if( $page_num < 1)
	{
		$page_num = 1;
	}
	else if( $page_num > $page_count )
	{
		$page_num = $page_count;
	}
 
	$paging_html .= "<div class=\"left \">";
	$paging_html .= "<span class=\"mr10\">共 <b>".$totle_count."</b> 条记录，每页显示 <b> ".$pre_page."</b> 条信息</span>";
	$paging_html .= "</div>";
	$paging_html .= "<div class=\"right  page \">";
 
	if ($page_num > 1)
	{
		$previous_page = $page_num - 1;
	}
	else
	{
		$previous_page = 1;
	}
	$paging_html .= "<span class=\"button mr5\" onclick=\"changepage(1, '".$table_name."','".$table_id."','".$pagestr_id."', $totle_count, $pre_page);\">第一页</span>";
	$paging_html .= "<span class=\"button mr5\" onclick=\"changepage($previous_page, '".$table_name."','".$table_id."','".$pagestr_id."', $totle_count, $pre_page);\">上一页</span>";
	$paging_html .= "<span>";
 
	if ( $show_page_num == 1 )
	{
		$paging_html .= "<span class=\"button_active\" onclick=\"changepage($page_num, '".$table_name."','".$table_id."','".$pagestr_id."', $totle_count, $pre_page);\">$page_num</span>";
	}
	else
	{
		$is_odd = false;
		if ($show_page_num % 2 == 1)
		{
			$is_odd = true;
		}
		$step = ceil($show_page_num / 2) - 1;
		$page_first = $page_num - $step;
		$page_end = $page_num + $step;
 
		if (!$is_odd)
		{
			$page_end = $page_end + 1;
		}
		if ( $page_first < 1 )
		{
			$num = 1 - $page_first;
			$page_first = 1;
			$page_end = $page_end + $num;
			if ($page_end > $page_count)
			{
				$page_end = $page_count;
			}
		}
		if ( $page_end > $page_count )
		{
			$num = $page_end - $page_count;
			$page_end = $page_count;
			$page_first = $page_first - $num;
			if ($page_first < 1)
			{
				$page_first = 1;
			}
		}
		for($i = $page_first; $i <= $page_end; $i++) {
			if($i == $page_num) {
				$paging_html .= "<span class=\"button_active\" onclick=\"changepage($i, '".$table_name."','".$table_id."','".$pagestr_id."', $totle_count, $pre_page);\">$i</span>";
			} else {
				$paging_html .= "<span class=\"button\" onclick=\"changepage($i, '".$table_name."','".$table_id."','".$pagestr_id."', $totle_count, $pre_page);\">$i</span>";
			}
		}
	}
 
	if ($page_num < $page_count)
		$next_page = $page_num + 1;
	else
		$next_page = $page_num;
 
	$paging_html .= "</span>";
	$paging_html .= "<span class=\"button ml5\" onclick=\"changepage($next_page, '".$table_name."','".$table_id."','".$pagestr_id."', $totle_count, $pre_page);\">下一页</span>";
	$paging_html .= "<span class=\"button ml5\" onclick=\"changepage($page_count, '".$table_name."','".$table_id."','".$pagestr_id."', $totle_count, $pre_page);\">最后一页</span>";
	$paging_html .= "</div>";
	return $paging_html;
}
 
/**
 * 生成分页链接代码
 */
function getPagingHtml( $url, $totle_count, $pre_page, $page_num, $show_page_num=5,$max_count="",$table_count='')
{
	$paging_html = null;
	if ( strpos($url, "?") === false )
	{
		$url .= "?";
	}
	else
	{
		$url .= "&";
	}

	// page处理
	if ( !empty($max_count) && !empty($table_count) )
	{
	    $pre = $pre_page/$table_count;
	    $page_count = ceil($max_count / $pre);
	}
	else
	{
	    $page_count = ceil($totle_count / $pre_page);
	}
	if( $page_num < 1)
	{
		$page_num = 1;
	}
	else if( $page_num > $page_count )
	{
		$page_num = $page_count;
	}
	$paging_html .= "<form action=\"$url\" method='post'>";
	$paging_html .= "<p>共 <b style=\"color:#333\">".$totle_count."</b> 条记录";
	$paging_html .= " 每页<b style=\"color:#333\"> ".$pre_page."</b> 条</p>";
	if ( $totle_count > 0 )
	{
		$total_page = ceil($totle_count/$pre_page);
		$paging_html .= "<p>总计<b style=\"color:#333\"> ".$total_page." </b> 页</p>";
	}
	$paging_html .= "<p style=\" float:right;\">";
	
	if ( $page_count >= 5 )
	{
		$paging_html .= "<input type=\"input\" name=\"page_num\" id=\"page_num\" value=\"".$page_num."\"  style=\"width:30px;\">&nbsp;<input type=\"hidden\" name=\"total_page\" id=\"total_page\" value=\"".ceil($totle_count / $pre_page)."\"  style=\"width:30px;\">&nbsp;<input type=\"submit\" onclick=\" return check_page(this)\" value=\"跳转\">&nbsp;";
	}

	if ($page_num > 1)
	{
		$previous_page = $page_num - 1;
		$paging_html .= "<a href=\"".$url."page_num=1\" title=\"First Page\">&laquo; First</a>";
		$paging_html .= "<a href=\"".$url."page_num=$previous_page\" title=\"Previous Page\">&laquo; Previous</a>";
	}
	if ( $show_page_num == 1 )
	{
		$paging_html .= "<a href=\"#\" class=\"number current\" title=\"$page_num\">$page_num</a>";
	}
	else
	{
		$is_odd = false;
		if ($show_page_num % 2 == 1)
		{
			$is_odd = true;
		}
		$step = ceil($show_page_num / 2) - 1;
		$page_first = $page_num - $step;
		$page_end = $page_num + $step;

		if (!$is_odd)
		{
			$page_end = $page_end + 1;
		}
		if ( $page_first < 1 )
		{
			$num = 1 - $page_first;
			$page_first = 1;
			$page_end = $page_end + $num;
			if ($page_end > $page_count)
			{
				$page_end = $page_count;
			}
		}
		if ( $page_end > $page_count )
		{
			$num = $page_end - $page_count;
			$page_end = $page_count;
			$page_first = $page_first - $num;
			if ($page_first < 1)
			{
				$page_first = 1;
			}
		}
		for($i = $page_first; $i <= $page_end; $i++) {
			if($i == $page_num) {
				$paging_html .= "<a href=\"#\" class=\"number current\" title=\"$i\">$i</a>";
			} else {
				$paging_html .= "<a href=\"".$url."page_num=$i\" class=\"number\" title=\"$i\">$i</a>";
			}
		}
	}

	if ($page_num < $page_count)
	{
		$next_page = $page_num + 1;
		$paging_html .= "<a href=\"".$url."page_num=$next_page\" title=\"Next Page\">Next &raquo;</a>";
		$paging_html .= "<a href=\"".$url."page_num=$page_count\" title=\"Last Page\">Last &raquo;</a>";
	}
	$paging_html .= "</p>";
	$paging_html .= "</form>";
	return $paging_html;	
}
