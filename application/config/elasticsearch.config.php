<?php
// ES配置信息
$config['elasticsearch_conf'] = array(
    // ES服务地址
    'elasticsearch_server'=>array('60.166.12.158:9200'),
    // ES-INDEX
    'elasticsearch_index_dmp'=>'dmp',
    // ES-TYPE
    'elasticsearch_index_type_imei'=>'imei',
    // ES-ROOT-PAHT
    'elasticsearch_root_path'=>SpringConstant::SPRING_PATH.'/libs/vendor/autoload.php'
);

// ES服务地址(140 148 156 155 154 153 152)
if (SPRING_ENVIRONMENT == 'publish')
{
    $config['elasticsearch_conf']['elasticsearch_server'] = array('10.200.63.140:9200','10.200.63.148:9200','10.200.63.152:9200','10.200.63.153:9200','10.200.63.154:9200','10.200.63.155:9200','10.200.63.156:9200');
}

// ES标签字段配置
$config['elasticsearch_tag_conf'] = array(
        0=>'tag.parent',
        1=>'tag.classify',
        2=>'tag.id',
);
