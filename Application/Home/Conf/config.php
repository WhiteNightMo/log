<?php
return array(
    //'配置项'=>'配置值'
    'URL_ROUTE_RULES' => array(
        // 文章
        'p/:p\d$' => array('Index/detail'),
        'p/:p\d/comment/:comment\d$' => array('Index/detail'),
        // 搜索
        's/:s\s$' => array('Index/index'),
        // 标签
        'tag/:tag\s$' => array('Index/index'),
        // 作者
        'author/:author$' => array('Index/index'),

        // 归档
        'archives' => array('Page/archives'),
        // 标签
        'tags$' => array('Page/tags'),
        // 友链
        'links' => array('Page/links'),
        // 关于
        'about' => array('Page/about'),
    ),
);