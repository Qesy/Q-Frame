<?php
function url_config(){ //$str2 = '/^\/news\/detail\/(\w+)\/bbb\/(\d+)/'; //匹配不到走原来的模式
    return array(
        array('search' =>'category/(\d+)' , 'action' => 'index/category/{$1}'),
        array('search' =>'detail/(\d+)' , 'action' => 'index/detail/{$1}'),
        array('search' =>'detailClass/(\d+)' , 'action' => 'index/detailClass/{$1}'),
        array('search' =>'enroll' , 'action' => 'index/enroll'),
        array('search' =>'question' , 'action' => 'index/question'),
        array('search' =>'code' , 'action' => 'index/code'),
        array('search' =>'enrollSuccess' , 'action' => 'index/enrollSuccess'),
        array('search' =>'evaluate' , 'action' => '/index/evaluate'),
        array('search' =>'complaint' , 'action' => '/index/complaint'),
    );
}