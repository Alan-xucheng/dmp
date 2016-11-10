<?php
/**
 * * @Name        tagStatsCron.php
 * * @Note        标签统计计划任务控制层
 * * @Author      jbxie
 * * @Created     2016年7月7日14:18:37
 * * @Version     v1.0.0
 * */
 
class TagStatsCron extends Controller
{
    protected  $es_tag_bll;
    protected $tag_manage_bll;
    protected $tag_view_bll;
    
    public function __construct()
    {
        $this->tag_view_bll = new TagViewBLL();
        $this->tag_manage_bll = new TagManageBLL();
        $this->es_tag_bll = new EsTagBLL();
    }
	
    /**
     * 执行任务
     * @param string $task_type string 任务类型 'D':天  'W':周  'M':月
     * @param string $request_token 请求令牌
	 * @param string $exec_tag_id 指定更新的标签id
     * @return json
     */
	public function execTask($task_type=NULL,$request_token=NULL, $exec_tag_id=NULL)
	{
		// 检查权限
		$token = $this->config('exec_task_token', 'tag.config');
		if($token != $request_token)
		{
			$result = UtilBLL::printReturn(false, 'token错误');
			UtilBLL::printJson($result);
		}
		// 检查类型
		if(empty($task_type) || !in_array($task_type, array(TagConstant::GRANULARITY_DAY, TagConstant::GRANULARITY_WEEK, TagConstant::GRANULARITY_MONTH)))
		{
			// 任务类型不是天、周或月
			$result = UtilBLL::printReturn(false, '任务类型错误');
			UtilBLL::printJson($result);
		}

		// 1. 根据参数的不同，获取不同的标签数据
		$task_tag = array();
		if(!empty($exec_tag_id))
		{
			// 标签id 不为空，直接统计当前标签
			$w_param = array();
			$w_param['tag_id'] = $exec_tag_id;
			$task_tag = $this->tag_manage_bll->getTagList($w_param, 'tag_id, level as tag_level, update_granularity');
			if(empty($task_tag) || count($task_tag) <= 0)
			{
				// 标签id 没有对应的标签
				$result = UtilBLL::printReturn(false, '标签id没有对应的标签');
				UtilBLL::printJson($result);
			}else{
				$tag_info = $task_tag[0];
				$update_granularity = $tag_info['update_granularity'];
				if($update_granularity !== $task_type)
				{
					// 指定的标签更新粒度和任务类型不一致
					$result = UtilBLL::printReturn(false, '标签更新粒度和任务类型不一致');
					UtilBLL::printJson($result);
				}else{
					unset($task_tag[0]['update_granularity']);
				}
			}
		}else{
			$w_param = array();
			$w_param['update_granularity'] = $task_type;
			$w_param['level'] = TagConstant::LEVEL_THREE;
			$task_tag = $this->tag_manage_bll->getTagList($w_param, 'tag_id, level as tag_level');
		}

		// 创建一个插入db 的时间
		switch($task_type)
		{
			case 'D':
				$create_time = date('Y-m-d');
				break;
			case 'W':
				$create_time = DateHandler::getLatestWeekDay('Thursday');
				break;
			case 'M':
				$create_time = Date("Y-m-d", strtotime(Date("Y-m-01")));
				break;
		}

		// 2. 遍历标签，调用es接口，获取标签的统计数据
		foreach($task_tag as $index=>$tag)
		{
			$tag_id = $tag['tag_id'];
			$population = $this->es_tag_bll->countUserByTagId($tag_id);
			// 用户数为0 的不存储
			if($population == 0)
			{
				unset($task_tag[$index]);
				continue;
			}

			$task_tag[$index]['population'] = $population;
			$task_tag[$index]['create_time'] = $create_time;

			// 判断是否已存在统计数据
			$w_param = [
					'tag_id'=>$tag_id,
					'create_time'=>$create_time
			];
			$info = $this->tag_view_bll->getStatsTagInfo($w_param);
			if(!empty($info) && count($info)>0)
			{
				// 更新同一时间已有的统计记录
				$f_param = [
						'population'=>$population
				];
				$this->tag_view_bll->updateTagStats($w_param, $f_param);
				unset($task_tag[$index]);
			}
		}
		// 3. 保存统计数据到数据库中。如果$task_tag 中有多余的字段，需要在此之前去除
		$this->tag_view_bll->insertTagStatsBatch($task_tag);
		// 返回结果
		$result = UtilBLL::printReturn(true, '执行任务成功', $task_tag);
		UtilBLL::printJson($result);
	}
}