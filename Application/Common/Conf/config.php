<?php
return array(
    //'配置项'=>'配置值'
    'URL_MODEL' => 2,   // URL访问模式


    /* 模块 */
    'MULTI_MODULE' => false,    // 关闭多模块访问
    'DEFAULT_MODULE' => 'Home', // 默认模块Home
//    'MODULE_Allow_LIST' => array('Home'),   // 允许模块组，可增，以逗号为隔


    /* 加载扩展文件*/
    'LOAD_EXT_FILE' => 'dataCache.func',


    /* 数据库设置 */
    'DB_TYPE' => 'mysql',     // 数据库类型
    'DB_HOST' => 'qdm20382310.my3w.com', // 服务器地址
    'DB_NAME' => 'qdm20382310_db',          // 数据库名
    'DB_USER' => 'qdm20382310',      // 用户名
//    'DB_HOST' => 'localhost',
//    'DB_NAME' => 'log',
//    'DB_USER' => 'root',
    'DB_PWD' => 'mysqlmysql',          // 密码
    'DB_PORT' => '3306',        // 端口
    'DB_PREFIX' => 'log_',    // 数据库表前缀


    /* 路由 */
    'URL_ROUTER_ON'   => true,  // 开启路由
    'URL_ROUTE_RULES'=>array(
        'author/:author' => array('Index/index'),
        'p/:p' => array('Index/detail'),
    ),
);