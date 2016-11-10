<?php
class testCron extends Controller
{
    private $es_tag_bll;
    private $tag_manage_bll;
    private $tag_view_bll;
    
    public function __construct()
    {
        $this->tag_view_bll = new TagViewBLL();
        $this->tag_manage_bll = new TagManageBLL();
        $this->es_tag_bll = new EsTagBLL();
    }
	public function test1()
	{
	    if (!file_exists('testcron.txt'))
	    {
	        $myfile = fopen("testcron.txt", "w") or die("Unable to open file!");
            $txt = "1";
            fwrite($myfile, $txt);
            fclose($myfile);
	    }
        $txt = file_get_contents('testcron.txt');
        $txt = $txt + 1;
        $myfile = fopen("testcron.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
	}
	
	/**
	 *  定时任务接口
	 * @param $task_type int 任务类型 'D':天  'W':周  'M':月
	 */
	public function execTask($task_type=NULL,$request_token=NULL)
	{
	    // 任务类型
// 	    $task_type = Request::params('type');
// 	    $request_token = Request::params('token');
	    // 检查权限
	    $token = $this->config('exec_task_token', 'tag.config');
	    $token = '123456';
	    if($token != $request_token)
	    {
	        $result = UtilBLL::printReturn(false, 'token错误');
	        UtilBLL::printJson($result);
	    }
	    if(empty($task_type) || !in_array($task_type, array(TagConstant::GRANULARITY_DAY, TagConstant::GRANULARITY_WEEK, TagConstant::GRANULARITY_MONTH)))
	    {
	        // 任务类型不是天、周或月
	        $result = UtilBLL::printReturn(false, '任务类型错误');
	        UtilBLL::printJson($result);
	    }
	    // 1. 从数据库中获取统计的标签
	    $w_param = array();
	    $w_param['update_granularity'] = $task_type;
	    $w_param['level'] = TagConstant::LEVEL_THREE;
	    $task_tag = $this->tag_manage_bll->getTagList($w_param, 'tag_id, level as tag_level');
	    // 2. 遍历标签，调用es接口，获取标签的统计数据
	    foreach($task_tag as $index=>$tag)
	    {
	        $tag_id = $tag['tag_id'];
	        $population = $this->es_tag_bll->countUserByTagId($tag_id);
	        $task_tag[$index]['population'] = $population;
	        $task_tag[$index]['create_time'] = date('Y-m-d h:m:s');
	    }
	    // 3. 保存统计数据到数据库中
	    $this->tag_view_bll->insertTagStatsBatch($task_tag);
	    // 返回结果
	    $result = UtilBLL::printReturn(true, '执行任务成功', $task_tag);
	    UtilBLL::printJson($result);
	}
}