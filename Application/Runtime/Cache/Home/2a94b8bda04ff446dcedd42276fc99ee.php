<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>
        <?php if($author): echo ($author); ?>
            <?php else: ?>
            首页<?php endif; ?>
        - my log
    </title>

    <!-- Bootstrap Core CSS -->
    <link href="/log/Public/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="/log/Public/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/log/Public/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/log/Public/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- My CSS-->
    <link href="/log/Public/css/log.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="wrapper">
    <!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/log/Home/Index/index">my log</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <?php if(session('user')): ?><li>
                        <a href="#">
                            <i class="fa fa-user fa-fw"></i>
                            个人设置
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="/log/Home/User/logout">
                            <i class="fa fa-sign-out fa-fw"></i>
                            退出账户
                        </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <a href="/log/Home/User/register">
                            <i class="fa fa-user fa-fw"></i>
                            注册
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="/log/Home/User/login">
                            <i class="fa fa-sign-in fa-fw"></i>
                            登录
                        </a>
                    </li><?php endif; ?>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="/log/Home/Index/index">
                        <i class="glyphicon glyphicon-home"></i> 首页
                    </a>
                </li>
                <?php if(session('user')): ?><li>
                        <a href="#">
                            <i class="glyphicon glyphicon-bookmark"></i> 个人日志
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">所有日志</a>
                            </li>
                            <li>
                                <a href="/log/Home/Post/index">新建</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li><?php endif; ?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>


    <div id="page-wrapper" style="padding-top: 20px;">
        <?php if($author): ?>
            <div class="row log">
                <div class="col-lg-12">
                    <div class="well well-lg" style="border-left: 8px #333 solid;">
                        <header><h2><?php echo ($author); ?></h2></header>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div><?php endif; ?>
        <?php if(is_array($logs)): foreach($logs as $key=>$log): ?><div class="row log">
                <div class="col-lg-12">
                    <div class="well well-lg">
                        <article>
                            <header>
                                <h2><a href="/log/Home/Index/detail/p/<?php echo ($log['id']); ?>"><?php echo ($log['title']); ?></a></h2>
                            </header>
                            <div class="log-content"><?php echo ($log['content']); ?></div>
                            <footer>
                                <span class="log-footer">
                                    <span class="fa fa-calendar fa-fw"></span>
                                    <?php echo (date('Y-m-d H:i',strtotime($log['post_date']))); ?>
                                </span>
                                <span class="log-footer">
                                    <span class="fa fa-user fa-fw"></span>
                                    <a href="/log/Home/Index/index/author/<?php echo ($log['user']); ?>/"><?php echo ($log['user']); ?></a>
                                </span>
                                <span class="log-footer">
                                    <span class="fa fa-comment fa-fw"></span>
                                    <?php if($log['total'] > 0): ?><a href="/log/Home/Index/detail/p/<?php echo ($log['id']); ?>/#comments">有<?php echo ($log['total']); ?>条评论</a>
                                        <?php else: ?>
                                        <a href="/log/Home/Index/detail/p/<?php echo ($log['id']); ?>/#respond">留下评论</a><?php endif; ?>
                                </span>
                            </footer>
                        </article>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row --><?php endforeach; endif; ?>
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="/log/Public/bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/log/Public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/log/Public/bower_components/metisMenu/dist/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="/log/Public/dist/js/sb-admin-2.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script type="text/javascript">
    $(document).ready(function () {
    });
</script>
</body>
</html>