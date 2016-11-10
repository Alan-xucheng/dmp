<?php
/**
 * * @Name        ExportTagAjax.php
 * * @Note        导出标签数据请求处理
 * * @Author      zcyue
 * * @Created     2016年7月7日 10:25:14
 * * @Version     v1.0.0
 * */

class ExportTagAjax extends Controller
{
    private $tag_manage_bll;
    private $tag_view_bll;
    private $es_tag_bll;

    public function __construct()
    {
        parent::__construct();
        $this->tag_manage_bll = new TagManageBLL();
        $this->tag_view_bll = new TagViewBLL();
        $this->es_tag_bll = new EsTagBLL();
    }

    private $title = array(
        'classify'=>'二级分类',
        'rate'=>'占比',
        'tag_name' => '标签名称',
        "population" => "用户数",
        "update_time" => "更新时间"
    );

    /**
     *  导出标签统计数据
     */
    public function tagExport()
    {
        //获取基本参数
        $tag_id = Request::params("tag_id");
        //获取排序参数
        $sort_key = Request::params("sort_key");
        $sort_direct = Request::params("sort_direct");

        if (empty($tag_id))
        {
            $result = UtilBLL::printReturn(false, '参数错误');
            UtilBLL::printJson($result);
        }
        $w_param = array();
        $w_param['tag_id'] = $tag_id;
        $tag_info = $this->tag_manage_bll->getTagList($w_param, 'tag_id, tag_name');
        if(empty($tag_info))
        {
            $result = UtilBLL::printReturn(false, '标签id没有对应标签');
            UtilBLL::printJson($result);
        }
        $tag_info = $tag_info[0];

        // 获取二级标签信息
        $data = $this->tag_view_bll->getChildTag($tag_id, 2);
        $this->filterExportTag($data, intval(TagConstant::LEVEL_TWO));

        //对结果集进行内容排序
        $sort_key = empty($sort_key) ? 'create_time' : $sort_key;
        $sort_direct = empty($sort_direct) ? 'desc' : $sort_direct;

        $this->sortExportTag($data, $sort_key, $sort_direct);

        //为导出文件写入数据
        $file_name = "标签统计表格";
        $title = '标签统计信息';
        $sub_title = '一级分类：'.$tag_info['tag_name'].'    导出时间：'.date('Y-m-d H:i:s');

        $this->export($file_name, $title, $sub_title, $data);
    }

    /**
     * 导出excel文件的数据写入
     * @param $filename string 导出的excel文件名
     * @param $titlename string 标题名称
     * @param $subtitle string 子标题
     * @param $res array 数据记录集，和数据综合记录
     */
    private function export($filename, $titlename, $subtitle, $res)
    {
        $data_list = $res;
        $map = $this->title;

        $objPHPExcel = new PHPExcel();

        // 默认设置
        $objPHPExcel->getDefaultStyle()->getFont()->setName("微软雅黑");
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(16);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);

        $objPHPExcel->setActiveSheetIndex(0);

        $activeSheet = $objPHPExcel->getActiveSheet();
        $activeSheet->setTitle('标签统计信息');

        $startCol = 0;
        $endCol = count($map) - 1;
        $startRow = 1;
        $startColString = PHPExcel_Cell::stringFromColumnIndex($startCol);
        $endColString = PHPExcel_Cell::stringFromColumnIndex($endCol);
        $row = $startRow;

        // 设置标题
        $activeSheet->mergeCells("{$startColString}{$row}:{$endColString}{$row}");
        $theCell = "{$startColString}{$row}";
        $activeSheet->setCellValue($theCell, $titlename);
        $activeSheet->getStyle($theCell)->getFont()->setSize('18');
        $activeSheet->getStyle($theCell)->getAlignment()->setHorizontal(PHPEXCEL_Style_Alignment::HORIZONTAL_CENTER);
        $activeSheet->getRowDimension("1")->setRowHeight(30);

        $row++;
        // 设置子标题
        $activeSheet->mergeCells("{$startColString}{$row}:{$endColString}{$row}");
        $theCell = "{$startColString}{$row}";
        $activeSheet->setCellValue($theCell, $subtitle);
        $activeSheet->getStyle($theCell)->getFont()->setSize('11');
        $activeSheet->getStyle($theCell)->getAlignment()->setHorizontal(PHPEXCEL_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->getRowDimension("2")->setRowHeight(24);
        $activeSheet->getStyle("{$startColString}{$row}:{$endColString}{$row}")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        // 设置列标题
        $row++;
        $col = $startCol;
        foreach ($map as $key => $title) {
            $col_string = PHPExcel_Cell::stringFromColumnIndex($col);
            $activeSheet->setCellValue($col_string . $row, $title);
            $activeSheet->getStyle($col_string . $row)->getFont()->setSize('12');
            $activeSheet->getStyle($col_string . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $activeSheet->getStyle($col_string . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $activeSheet->getStyle($col_string . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $activeSheet->getStyle($col_string . $row)->getFill()->getStartColor()->setRGB("667287");
            $col++;
        }
        $activeSheet->getRowDimension("3")->setRowHeight(24);

        $row++;
        $current_dimension = 4;

        if(empty($data_list))
        {
            $activeSheet->mergeCells("{$startColString}{$row}:{$endColString}{$row}");
            $theCell = "{$startColString}{$row}";
            $activeSheet->setCellValue($theCell, '暂无有效标签');
            $activeSheet->getStyle($theCell)->getFont()->setSize('12');
            $activeSheet->getStyle($theCell)->getAlignment()->setHorizontal(PHPEXCEL_Style_Alignment::HORIZONTAL_CENTER);
            $activeSheet->getRowDimension($current_dimension)->setRowHeight(24);
            $activeSheet->getStyle("{$startColString}{$row}:{$endColString}{$row}")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }

        // 写数据
        foreach ($data_list as $rowData) {
            if(empty($rowData['child']))
            {
                continue;
            }else{
                $child_record_count = count($rowData['child']);
            }

            $cursor_column_start = 0;
            $cursor_row_end = $row+$child_record_count-1; // 在过滤标签时，如果父标签没有子标签，则是无效数据，导出报表时不存在
            // 父标签名称
            $startColString = PHPExcel_Cell::stringFromColumnIndex($cursor_column_start);
            $current_cell = "{$startColString}{$row}";
            $activeSheet->mergeCells("{$startColString}{$row}:{$startColString}{$cursor_row_end}");
            $activeSheet->setCellValue($current_cell, $rowData['tag_name']);
            $activeSheet->getStyle("{$startColString}{$row}:{$startColString}{$cursor_row_end}")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $cursor_column_start ++;

            // 父标签占比
            $startColString = PHPExcel_Cell::stringFromColumnIndex($cursor_column_start);
            $current_cell = "{$startColString}{$row}";
            $activeSheet->mergeCells("{$startColString}{$row}:{$startColString}{$cursor_row_end}");
            $activeSheet->setCellValue($current_cell, $rowData['rate'].'%');
            $activeSheet->getStyle("{$startColString}{$row}:{$startColString}{$cursor_row_end}")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $cursor_column_start ++;

            // 子标签列表
            $child_tag = $rowData['child'];
            foreach($child_tag as $item)
            {
                $tmp_column_start = $cursor_column_start;
                // 子标签名称
                $tag_name = $item['tag_name'];
                $startColString = PHPExcel_Cell::stringFromColumnIndex($tmp_column_start);
                $current_cell = "{$startColString}{$row}";
                $activeSheet->setCellValue($current_cell, $tag_name);
                $activeSheet->getStyle($current_cell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $tmp_column_start ++;
                // 子标签用户数
                $population = $item['population'];
                $startColString = PHPExcel_Cell::stringFromColumnIndex($tmp_column_start);
                $current_cell = "{$startColString}{$row}";
                $activeSheet->setCellValue($current_cell, $population);
                $activeSheet->getStyle($current_cell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $tmp_column_start ++;
                // 更新时间
                $update_time = $item['update_time'];
                $startColString = PHPExcel_Cell::stringFromColumnIndex($tmp_column_start);
                $current_cell = "{$startColString}{$row}";
                $activeSheet->setCellValue($current_cell, $update_time);
                $activeSheet->getStyle($current_cell)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $activeSheet->getRowDimension($current_dimension++)->setRowHeight(24);
                $row ++;
            }

            $row = $cursor_row_end + 1;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        ob_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/octet-stream");
        header('Content-Disposition:attachment;filename="' . $filename . '.xlsx"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }

    /**
     *  过滤出标签数组中的有效标签
     * @param $tag_arr
     */
    private function filterExportTag(&$tag_arr, $level)
    {
        // 获取标签总用户数，方便统计占比
        $total_user_count = $this->es_tag_bll->countUserByTagId();
        foreach($tag_arr as $index=>$item)
        {
            $population = $item['population'];
            $tag_id = $item['tag_id'];
            // 判断统计信息中是不有用户
            if($population == 0)
            {
                // 调用es 接口获取最新用户数
                $user_count = $this->es_tag_bll->countUserByTagId($tag_id, $level);
                //$user_count = $this->countUserByTagId($tag_id);
                if($user_count == 0)
                {
                    // 统计信息里没有用户，实时调用接口也没有用户，则认为标签无效
                    unset($tag_arr[$index]);
                }else{
                    $tag_arr[$index]['population'] = $user_count;
                    // 实际只需要统计二级标签的占比即可，这里也统计了三级标签
                    $rate = $user_count*100 / $total_user_count;
                    $rate = round($rate, 2);
                    $tag_arr[$index]['rate'] = $rate;
                }
            }
            if(!empty($tag_arr[$index]['child']))
            {
                $this->filterExportTag($tag_arr[$index]['child'], intval(TagConstant::LEVEL_THREE));
            }
        }
    }

    /**
     *  导出标签数据排序
     * @param $tag_arr
     * @param $sort_key
     * @param $sort_direct
     */
    private function sortExportTag(&$tag_arr, $sort_key, $sort_direct)
    {
        foreach($tag_arr as $index=>$item)
        {
            if (!empty($tag_arr[$index]['child'])) {
                $this->sortExportTag($tag_arr[$index]['child'], $sort_key, $sort_direct);
            }
        }
        $tag_arr = UtilBLL::arrayMultiSort($tag_arr, $sort_key, $sort_direct);
    }


    /**
     *  临时适配的获取标签用户数。以方便代码的低耦合
     *  add by zcyue 2016-7-20 15:10:00
     * @param $tag_id
     * @return int
     * @throws ErrorException
     */
    private function countUserByTagId($tag_id=null)
    {
        $relation_arr = $this->tag_manage_bll->getTagIdRelation($tag_id);
        $count = $this->es_tag_bll->countUserByTagRelation($relation_arr);
        return $count;
    }

}