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
    'DEFAULT_USER_ID' => 6,    // 归档、友链默认内容的user_id


    /* 数据库设置 */
    'DB_TYPE' => 'mysql',     // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'log',          // 数据库名
    'DB_USER' => 'root',      // 用户名
    'DB_PWD' => '123456',          // 密码
    'DB_PORT' => '3306',        // 端口
    'DB_PREFIX' => 'log_',    // 数据库表前缀


    /* Cookie设置 */
    'COOKIE_DOMAIN' => 'nixiaomo.com',  // Cookie有效域名
    'COOKIE_PREFIX' => 'log_', // Cookie 名称前缀
    'COOKIE_EXPIRE' => 3600 * 24 * 7, // Cookie 保存时间


    /* 路由 */
    'URL_ROUTER_ON' => true,  // 开启路由
    'URL_ROUTE_RULES' => array(
        // 文章
        'p/:p\d$' => array('Index/detail'),
        'p/:p\d/comment/:comment\d$' => array('Index/detail'),
        // 文章管理
        'post_status/:post_status' => array('Post/index'),
        // 搜索
        's/:s\s$' => array('Index/index'),
        // 标签
        'tag/:tag\s$' => array('Index/index'),
        // 作者
        'author/:author$' => array('Index/index'),

        // 归档
        'archives' => array('Archive/index'),
        // 标签
        'tags$' => array('Tags/index'),
        // 友链
        'links' => array('Link/index'),
        // 关于
        'about' => array('Archive/about'),
    ),
);