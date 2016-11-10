<?php
/**
 * * @Name        ProductionManageBLL.php
 * * @Note        产品标签业务访问层
 * * @Author      zcyue
 * * @Created     2016年6月27日 22:20:00
 * * @Version     v1.0.0
 * */

class ProductionManageBLL extends Model
{

    private $production_manage_dal;

    public function __construct()
    {
        parent::__construct();
        $this->production_manage_dal = new ProductionManageDAL();
    }

    /**
     *  获取表数据
     *  add by zcyue 2016-6-28 13:50:00
     */
    public function getProductTag($w_param, $f_param='*', $order="", $direct="", $group_by="", $page_size='', $offset='')
    {
        $result = $this->production_manage_dal->getList($w_param, $f_param, $order, $direct, $group_by, $page_size, $offset);
        return  $result;
    }

    /**
     *  更新产品标签表
     *  add by zcyue 2016-6-28 14:43:00
     * @param $product_id
     * @param $s_param
     */
    public function updateProductTag($product_id, $s_param)
    {
        $w_param = array();
        $w_param['product_id'] = $product_id;
        $result = $this->production_manage_dal->updateRecord($w_param, $s_param);
        return $result;
    }

    /**
     *  删除产品对应的标签分类
     *  add by zcyue 2016-6-28 13:50:00
     * @param $tag_id
     */
    public function removeProductTag($tag_id)
    {
        // 从缓存中获取标签对应的产品
        $product_arr = TagProductionCache::getTagProduction($tag_id);
        // 如果有，则更新对应的标签集
        if(!empty($product_arr))
        {
            foreach($product_arr as $product_id)
            {
                $w_param = array();
                $w_param['product_id'] = $product_id;
                $tag_info = $this->getProductTag($w_param, 'tag_id_set');
                if(!empty($tag_info) && count($tag_info)>0)
                {
                    // 解析&处理产品对应的标签集
                    $tag_id_set = $tag_info[0]['tag_id_set'];
                    $tag_id_arr = explode(TagConstant::PRODUCTION_TAG_SPLIT, $tag_id_set);
                    foreach ($tag_id_arr as $key=>$value)
                    {
                        if ($value === $tag_id)
                            unset($tag_id_arr[$key]);
                    }
                    $new_tag_id_set = implode(TagConstant::PRODUCTION_TAG_SPLIT, $tag_id_arr);
                    // 更新产品标签信息
                    $s_param = array();
                    $s_param['tag_id_set'] = $new_tag_id_set;
                    $result = $this->updateProductTag($product_id, $s_param);
                    if(!$result)
                    {
                        return UtilBLL::printReturn(false, '删除产品标签失败');
                    }
                }
            }
        }
        return UtilBLL::printReturn(true, '删除产品标签成功');
    }


}
