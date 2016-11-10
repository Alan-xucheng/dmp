<?php

use Monolog\Handler\IFTTTHandler;
class Test extends Controller
{
    /**
     * 业务层对象变量
     */
    protected $account_bll;

	protected $tag_manage_bll;
    
    /***
     * 变量
     */
    protected $elasticsearch_server;
    protected $elasticsearch_index_dmp;
    protected $elasticsearch_index_type_imei;
    protected $elasticsearch_root_path;
    
	public function __construct()
	{
		parent::__construct();
		$elasticsearch_conf_arr = $this->config('elasticsearch_conf','elasticsearch.config');
		$this->elasticsearch_server = $elasticsearch_conf_arr['elasticsearch_server'];
		$this->elasticsearch_index_dmp = $elasticsearch_conf_arr['elasticsearch_index_dmp'];
		$this->elasticsearch_index_type_imei = $elasticsearch_conf_arr['elasticsearch_index_type_imei'];
		$this->elasticsearch_root_path = $elasticsearch_conf_arr['elasticsearch_root_path'];

		$this->tag_manage_bll = new TagManageBLL();
	}

	public function testTagIdRelation($tag_id = NULL)
	{
		$relation_arr = $this->tag_manage_bll->getTagIdRelation($tag_id);
		$full_tag = implode('', $relation_arr);
		return $full_tag;
	}
	
	public function testProductTag()
	{
	    $production_manage_bll = new ProductionManageBLL();
	    $product_tag_result = $production_manage_bll->getProductTag(null);
        if (!empty($product_tag_result))
        {
            foreach ($product_tag_result as $i => $item)
            {
                $tags = explode(',', $item['tag_id_set']);
                $tags_str = '';
                if (!empty($tags))
                {
                    foreach ($tags as $num => $val)
                    {
                        $full_tag = $this->testTagIdRelation($val);
                        if (empty($tags_str))
                        {
                            $tags_str = $full_tag;
                        }
                        else 
                        {
                            $tags_str .= ','.$full_tag;
                        }
                    }
                }
                $product_tag_result[$i]['tags_str'] = $tags_str;
                $production_manage_bll->updateProductTag($product_tag_result[$i]['product_id'], array('tag_id_set'=>$product_tag_result[$i]['tags_str']));
            }
        }
	}

	public function testRSA() 
	{
	   $str = 'jbxie';
	   $encrypt_str = RSA::encrypt($str,true);
	   $decrypt_str = RSA::decrypt($encrypt_str,true);
	   var_dump($str,$encrypt_str,$decrypt_str);exit;
	}
	
	public function testesSearch()
	{
	    $query_arr = array(
	        'query'=>array(
	        'bool'=> array(
	            'must'=>array(
    	            array(
    	                'match'=>array(
    	                    'parent'=>'1113',
    	                )
    	           ),
//     	            array(
//         	            'match'=>array(
//         	                'classify'=>'0000',
//         	            )
//     	            ),
//     	            array(
//         	            'match'=>array(
//         	                'id'=>'111200020002'
//         	            )
//     	            ),
// 	                array(
// 	                    'match'=>array(
// 	                        'did'=>'jbxie6',
// 	                    )
// 	                ),
	        )
	    )
	        )
	    );
	    $searchParams = array(
	        'index' => $this->elasticsearch_index_dmp,
	        'type' => $this->elasticsearch_index_type_imei,
	        'source' =>json_encode($query_arr)
	        );
// 	    my_debug($searchParams);exit;   
	    
// 	    $searchParams['body'] = $query_arr;
	    
        $es_autolod_path = SpringConstant::SPRING_PATH.'/libs/vendor/autoload.php';
        require $es_autolod_path;
        $ServerParams = array(
            'hosts' => array($this->elasticsearch_server),
        );
        $client = new Elasticsearch\Client($ServerParams);
        $retDoc = $client->search($searchParams);
        my_debug($retDoc);exit;
	}

	public function testesCount()
	{
	    $query_arr = array(
	        'query'=>array(
	        'bool'=> array(
	            'must'=>array(
//     	            array(
//     	                'match'=>array(
//     	                    'parent'=>'1113',
//     	                )
//     	           ),
//     	            array(
//         	            'match'=>array(
//         	                'classify'=>'0000',
//         	            )
//     	            ),
    	            array(
        	            'match'=>array(
        	                'id'=>'111200020002'
        	            )
    	            ),
// 	                array(
// 	                    'match'=>array(
// 	                        'did'=>'jbxie6',
// 	                    )
// 	                ),
	        )
	    )
	        )
	    );
	    
	    $countParams = array(
	        'index' => $this->elasticsearch_index_dmp,
	        'type' => $this->elasticsearch_index_type_imei,
	        'source' =>json_encode($query_arr)
	    );
	    // 	$searchParams['body']['query']['match']['did'] = 866137879578071;
	    $es_autolod_path = SpringConstant::SPRING_PATH.'/libs/vendor/autoload.php';
	    require $es_autolod_path;
	    $ServerParams = array(
	        'hosts' => array($this->elasticsearch_server),
	    );
	    $client = new Elasticsearch\Client($ServerParams);
	    $retDoc = $client->count($countParams);
	    my_debug($retDoc);exit;
	}
	
	public function testesIndex() 
	{
	    $indexParams = array(
	        'index' => $this->elasticsearch_index_dmp,
	        'type' => $this->elasticsearch_index_type_imei,
	    );
	    $id = 'zcyue';
	    $indexParams['id'] = $id;
	    // 	    my_debug($searchParams);exit;
	     
	    // 	    $searchParams['body'] = $query_arr;
	    
	    $indexParams['body']  = array(
	        'did'=>$id,
//             'tag'=>array(
//                 array(
//                     'classify'=>array('11120002'),
//                     'id'=>'111200020002',
//                     'parent'=>array('1112')
//                 ),
//                 array(
//                     'classify'=>array('11120002'),
//                     'id'=>'111200020006',
//                     'parent'=>array('1112')
//                 ),
//             ),
	        'tag'=>array(
	            array(
	                'classify'=>array('0004'),
	                'id'=>'0002',
	                'parent'=>array('1113')
	            ),
	            array(
	                'classify'=>array('0004'),
	                'id'=>'0004',
	                'parent'=>array('1113')
	            ),
	        ),
	    );
	    $es_autolod_path = SpringConstant::SPRING_PATH.'/libs/vendor/autoload.php';
	    require $es_autolod_path;
	    $ServerParams = array(
	        'hosts' => array($this->elasticsearch_server),
	    );
	    $client = new Elasticsearch\Client($ServerParams);
	    $retDoc = $client->index($indexParams);
	    my_debug($retDoc);exit;
	}
	
	public function testNewCount() 
	{
		$tag_id = Request::params('tag_id');
		$level = Request::params('level');
		if(empty($tag_id))
		{
			//echo '参数错误'; exit;
		}
	    $es_tag_bll = new EsTagBLL();
	    $count = $es_tag_bll->countUserByTagId($tag_id,$level);
	    echo 'count:'.$count;
	}
	
	public function testNewSearch()
	{
	    $tag_id = Request::params('tag_id');
	    $level = Request::params('level');
	    $size = Request::params('size');
	    $es_tag_bll = new EsTagBLL();
	    $result = $es_tag_bll->searchUserByTagId($tag_id,$level,$size);
	    my_debug($result);exit;
	}

	/**
	 *  索引数据
	 *  add by zcyue 2016-7-5 13:00:00
	 */
	public function testIndexDocument()
	{
		$client = $this->getClient();
		$params = [
			'index' => 'my_index',
			'type' => 'my_type',
			'id' => 'my_id',
			'body' => [ 'testField' => 'abc' ]
		];
		$response = $client->index($params);
		print_r($response);
	}

	/**
	 *  获取索引数据
	 *  add by zcyue 2016-7-5 13:00:00
	 */
	public function testGetDocument()
	{
		$client = $this->getClient();
		$params = [
			'index' => 'my_index',
			'type' => 'my_type',
			'id' => 'my_id'
		];
		$response = $client->get($params);
		print_r($response);
	}

	/**
	 *  搜索索引数据
	 *  add by zcyue 2016-7-5 13:00:00
	 */
	public function testSearchDocument()
	{
		$client = $this->getClient();
		$params = [
				'index' => 'my_index',
				'type' => 'my_type',
				'body' => [
					'query' => [
						'match' => [
							'testField' => 'abc'
						]
					]
				]
		];
		$response = $client->search($params);
		print_r($response);
	}

	/**
	 *  删除索引数据
	 *  add by zcyue 2016-7-5 13:00:00
	 */
	public function testDeleteDocument()
	{
		$client = $this->getClient();
		$params = [
				'index' => 'my_index',
				'type' => 'my_type',
				'id' => 'my_id'
		];
		$response = $client->delete($params);
		print_r($response);
	}

	/**
	 *  删除索引
	 *  add by zcyue 2016-7-5 13:00:00
	 */
	public function testDeleteIndex()
	{
		$client = $this->getClient();
		$params = [
				'index' => 'my_index'
		];
		$response = $client->indices()->delete($params);
		print_r($response);
	}

	/**
	 *  初始化客户端
	 * @return \Elasticsearch\Client
	 */
	private function getClient()
	{
		$es_autolod_path = SpringConstant::SPRING_PATH.'/libs/vendor/autoload.php';
		require $es_autolod_path;
		$ServerParams = array(
				'hosts' => array($this->elasticsearch_server),
		);
		$client = new Elasticsearch\Client($ServerParams);
		return $client;
	}

}