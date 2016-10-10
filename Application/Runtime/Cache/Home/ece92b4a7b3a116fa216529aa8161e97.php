<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>
        <?php if($title): ?>编辑
            <?php else: ?>
            新建<?php endif; ?>
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

    <!-- Summernote CSS-->
    <link href="/log/Public/css/summernote.css" rel="stylesheet" type="text/css">

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


    <!--警告框样式开始-->
    <div class="member-warn"></div>
    <!--警告框样式结束-->
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?php if($title): ?>编辑日志
                        <?php else: ?>
                        撰写新日志<?php endif; ?>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-8">
                <form role="form">
                    <div class="form-group">
                        <input class="form-control input-title" placeholder="在此输入标题"
                               value="<?php echo ((isset($title) && ($title !== ""))?($title):''); ?>" autofocus>
                    </div>
                    <div class="form-group">
                        <div id="summernote"><?php echo ((isset($content) && ($content !== ""))?($content):''); ?></div>
                    </div>
                </form>
            </div>
            <!-- /.col-md-8 -->
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">发布</div>
                    <div class="panel-body">
                        <div class="form-group" style="min-height: 34px;">
                            <?php if($status != 1): ?><a class="btn btn-default">保存草稿</a><?php endif; ?>
                            <?php if($status != 0): ?><a href="/log/Home/Index/detail/p/<?php echo ($id); ?>" class="btn btn-default"
                                   style="float:right;" target="_blank">查看</a><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <span class="fa fa-thumb-tack fa-fw"></span>当前状态：
                            <strong>
                                <?php if($status == 1): ?>已发布
                                    <?php else: ?>
                                    草稿<?php endif; ?>
                            </strong>
                        </div>
                        <div class="form-group">
                            <span class="fa fa-calendar fa-fw"></span>上次编辑：
                            <strong><?php echo ((isset($edit_date) && ($edit_date !== ""))?($edit_date):'待发布'); ?></strong>
                        </div>
                    </div>
                    <div class="panel-footer" style="min-height: 55px">
                        <?php if($status != 0): ?><button class="btn btn-link">移至回收站</button><?php endif; ?>
                        <button class="btn btn-primary btn-post" style="float: right;">
                            <?php if($status == 1): ?>更新
                                <?php else: ?>
                                发布<?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->
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

<!-- Summernote JavaScript -->
<script src="/log/Public/js/summernote.min.js"></script>

<!-- Summernote lang zh-CN JavaScript -->
<script src="/log/Public/js/summernote-zh-CN.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#summernote').summernote({
            height: 300,
            minHeight: 300,
            maxHeight: 300,
            lang: 'zh-CN',
            toolbar: [
                ['font', ['style']],
                ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture']]
            ],
            onImageUpload: function (files, editor, $editable) {
                sendFile(files[0], editor, $editable);
            }
        });

        function sendFile(file, editor, $editable) {
            $(".member-warn").show().html("图片正在上传，请稍候...");

            var filename = file['name'];
            // 以上防止在图片在编辑器内拖拽引发第二次上传导致的提示错误
            var ext = filename.substr(filename.lastIndexOf(".")).toLowerCase();
            data = new FormData();
            data.append("file", file);
            data.append("ext", ext);
            $.ajax({
                data: data,
                type: "post",
                url: "/log/Home/Post/upload",
                cache: false,
                contentType: false,
                processData: false,
                success: function (url) {
                    showTips("上传成功,请等待加载");
                    editor.insertImage($editable, url);
                    if (!window.addEventListener) $("input[type='file']").outerHTML("");  //IE清除inputfile
                    else $("input[type='file']").val("");   //FF清除inputfile
                },
                error: function () {
                    showTips("上传失败");
                }
            });
        }


        // 发布
        $(".btn-post").click(function () {
            var title = $(".input-title").val();
            var content = $('#summernote').code();
            var p = '<?php echo ((isset($id) && ($id !== ""))?($id):0); ?>';
            if ($.trim(title) == "" || content == "" || content == "<br>" || content == "<p><br></p>") {
                alert("标题和内容不能为空");
                return;
            }

            $.ajax({
                url: '/log/Home/Post/add',
                type: 'post',
                data: {
                    p: p,
                    title: title,
                    content: content
                },
                success: function (result) {
                    var jsonObj = $.parseJSON(result);
                    if (jsonObj.err > 0) {
                        window.location.href = "/log/Home/Post/edit/id/" + jsonObj.id;
                    } else alert(jsonObj.message);
                }
            });
        });


        // 显示2秒提示
        function showTips(tips) {
            $(".member-warn").show().html(tips);
            setTimeout(function () {
                $(".member-warn").hide();
            }, 2000);
        }
    });
</script>
</body>
</html>;