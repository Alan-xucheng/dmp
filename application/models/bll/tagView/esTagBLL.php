<?php
/**
 * * @Name        esTagBLL.php
 * * @Note        es标签业务层业务访问层
 * * @Author      jbxie
 * * @Created     2016年6月28日11:34:17
 * * @Version     v1.0.0
 * */
 
class EsTagBLL extends Model
{
    /***
     * 变量
     */
    protected $elasticsearch_server;
    protected $elasticsearch_index_dmp;
    protected $elasticsearch_index_type_imei;
    protected $elasticsearch_root_path;
    protected $server_params;
    protected $elasticsearch_tag_conf_arr;
    
	public function __construct()
	{
		parent::__construct();
		$elasticsearch_conf_arr = $this->config('elasticsearch_conf','elasticsearch.config');
		$this->elasticsearch_server = $elasticsearch_conf_arr['elasticsearch_server'];
		$this->elasticsearch_index_dmp = $elasticsearch_conf_arr['elasticsearch_index_dmp'];
		$this->elasticsearch_index_type_imei = $elasticsearch_conf_arr['elasticsearch_index_type_imei'];
		$this->elasticsearch_root_path = $elasticsearch_conf_arr['elasticsearch_root_path'];
		$this->elasticsearch_tag_conf_arr = $this->config('elasticsearch_tag_conf','elasticsearch.config');
		$this->server_params = array(
		    'hosts' => $this->elasticsearch_server,
		);
		// 引入ES源
        require $this->elasticsearch_root_path;		
	}
	
	/***
	 * 获取标签用户数
	 * @param string $tag_id 标签ID
	 * @param int $level 标签分类 0：一级标签，1：二级标签，2：三级标签...
	 * @return int
	 */
	public function countUserByTagId($tag_id=NULL,$level=2)
	{
	    // 标签分类初始化
	    if (!is_numeric($level) || !array_key_exists($level,$this->elasticsearch_tag_conf_arr)) 
	    {
	        $level = 2;
	    }
	    $params = $this->assembleTagQueryParams($tag_id,$level);
	    $client = new Elasticsearch\Client($this->server_params);
	    $response = $client->count($params);
	    return intval($response['count']);
	}

	/**
	 *  根据标签的父子id数组获取标签用户数
	 *  add by zcyue 2016-7-20 15:00:00
	 * @param $relation_arr array eg: [ ''一级分类id, '二级分类id', '三级分类id', ..., '标签id']
	 */
	public function countUserByTagRelation($relation_arr)
	{
		/**
		 * todo 不管分类多少，代码写通用
		 * 目前先只考虑标签只有二级分类的情况，因此这里处理的$relation_arr 共有三个元素
		 */
		$params = $this->assembleTagQueryParamsByTagRelation($relation_arr);
		$client = new Elasticsearch\Client($this->server_params);
		$response = $client->count($params);
		return intval($response['count']);
	}

	/***
	 * 获取标签用户信息
	 * @param string $tag_id 标签ID
	 * @param int $level 标签分类 0：一级标签，1：二级标签，2：三级标签...
	 * @param int $size document大小
	 * @return array
	 */
	public function searchUserByTagId($tag_id=NULL,$level=2,$size=10)
	{
	    // 标签分类初始化
	    if (!is_numeric($level) || !array_key_exists($level,$this->elasticsearch_tag_conf_arr))
	    {
	        $level = 2;
	    }
	    $params = $this->assembleTagQueryParams($tag_id,$level);
	    $params['size'] = $size;
	    $client = new Elasticsearch\Client($this->server_params);
	    $response = $client->search($params);
	    return $response['hits']['hits'];
	}

	/***
	 *  组装查询参数
	 *  add by zcyue 2016-7-20 15:10:00
	 * @param array
	 * @return array
	 */
	public function assembleTagQueryParamsByTagRelation($relation_arr)
	{
		// 查询源
		$query_source = '';
		if (!empty($tag_id))
		{
			//$tag_id_arr = UtilBLL::strToArrayByLen($tag_id);
			$tag_id_arr = $relation_arr;
			// es标签字段配置
			$must_arr = array();
			foreach ($tag_id_arr as $num => $tag_id_tmp)
			{
				$must_arr[] = array(
						'match'=> array(
								$this->elasticsearch_tag_conf_arr[$num] => $tag_id_tmp
						)
				);
			}
			$query_arr = array(
					'query'=>array(
							'bool'=> array(
									'must'=>$must_arr
							)
					)
			);
			$query_source = json_encode($query_arr);
		}
		$params = array(
				'index' => $this->elasticsearch_index_dmp,
				'type' => $this->elasticsearch_index_type_imei,
		);
		if (!empty($query_source))  $params['source'] = $query_source;
		return $params;
	}

	/***
	 * 组装查询参数
	 * @param string $tag_id 标签ID
	 * @param int $level 标签分类 0：一级标签，1：二级标签，2：三级标签...
	 * @return array
	 */
	public function assembleTagQueryParams($tag_id=NULL,$level=2)
	{
	    // 查询源
        $query_source = '';
        if (!empty($tag_id))
        {
           $must_arr = array();
           $must_arr[] = array(
               'match'=> array(
                   $this->elasticsearch_tag_conf_arr[$level] => $tag_id
               )
           );
           $query_arr = array(
               'query'=>array(
                   'bool'=> array(
                       'must'=>$must_arr
                   )
               )
           );
           $query_source = json_encode($query_arr);
        }
        $params = array(
            'index' => $this->elasticsearch_index_dmp,
            'type' => $this->elasticsearch_index_type_imei,
        );
        if (!empty($query_source))  $params['source'] = $query_source;
        return $params;
	}
    
}
