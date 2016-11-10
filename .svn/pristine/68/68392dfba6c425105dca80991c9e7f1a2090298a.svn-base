<?php
/**
 * * @Name        PreConditionBLL.php
 * * @Note        系统预处理业务层
 * * @Author      zcyue
 * * @Created     2016年6月27日 22:20:00
 * * @Version     v1.0.0
 * */

class PreConditionBLL extends Model
{

    private $production_manage_bll;

    public function __construct()
    {
        parent::__construct();
        $this->production_manage_bll = new ProductionManageBLL();
    }

    /**
     *  读取产品标签映射表，转换为标签产品映射，并缓存保存
     *  add by zcyue 2016-6-28 9:30:00
     */
    public function cacheTagProduction()
    {
        // 获取全量的产品标签数据
        $w_param = array();
        $production_tag_info = $this->production_manage_bll->getProductTag($w_param);
        // 重组映射数据
        $tag_production_arr = $this->reAssembleTagProduction($production_tag_info);
        // 将重组后的标签-产品映射关系存入缓存
        TagProductionCache::initTagProductionCache($tag_production_arr);
    }

    /**
     *  将产品数据，转换为标签id-产品id的对应关系数组
     * @param $data
     * @return $tag_production_arr
     */
    private function reAssembleTagProduction($data)
    {
        $tag_production_arr = array();
        foreach($data as $key=>$item)
        {
            $production_id = $item['product_id'];
            $tag_id_set = $item['tag_id_set'];
            $tag_arr = explode(TagConstant::PRODUCTION_TAG_SPLIT, $tag_id_set);
            foreach($tag_arr as $tag)
            {
                if(empty($tag_production_arr[$tag]))
                {
                    $tag_production_arr[$tag] = array();
                }
                array_push($tag_production_arr[$tag], $production_id);
            }
        }
        return $tag_production_arr;
    }

}