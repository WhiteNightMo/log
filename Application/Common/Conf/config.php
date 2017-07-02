<?php
return array(
    //'配置项'=>'配置值'
    'URL_MODEL' => 2,   // URL访问模式


    /* 模块 */
    'MULTI_MODULE' => false,    // 关闭多模块访问
    'DEFAULT_MODULE' => 'Home', // 默认模块Home
//    'MODULE_Allow_LIST' => array('Home'),   // 允许模块组，可增，以逗号为隔


    // 定义公共错误模板
    'TMPL_EXCEPTION_FILE' => APP_PATH . '/../Public/404.html',


    /* 项目配置 */
    'PROJECT_NAME' => 'my log',    // 项目名


    /* 加载扩展文件*/
    'LOAD_EXT_FILE' => 'dataCache.func',


    /* 数据库设置 */
    'DB_TYPE' => 'mysql',     // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'log',          // 数据库名
    'DB_USER' => 'root',      // 用户名
    'DB_PWD' => '123456',          // 密码
    'DB_PORT' => '3306',        // 端口
    'DB_PREFIX' => 'log_',    // 数据库表前缀


    /* cookie设置 */
    'COOKIE_PREFIX' => 'log_', // cookie 名称前缀
    'COOKIE_EXPIRE' => 3600 * 24 * 7, // cookie 保存时间


    /* 路由 */
    'URL_ROUTER_ON' => true,  // 开启路由
    'URL_ROUTE_RULES' => array(
        'p/:p\d$' => array('Index/detail'),
        'author/:author$' => array('Index/index'),
        'author/:author/archive/:archive\d$' => array('Index/index'),
    ),
);