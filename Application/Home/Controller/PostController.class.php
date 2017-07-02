<?php/** * 文章管理 * * 操作： *      index：文章列表 *      add：新建 *      edit：编辑 * * 消息处理： *      post：新建/编辑 *      trash：垃圾箱 *      upload：文件上传 * * @author xiaomo<xiaomo@nixiaomo.com> */namespace Home\Controller;class PostController extends BaseController{    /**     * 文章列表     */    public function index()    {        $this->initLogged();        $Post = M('post');        // 获取各分类数量        $where_count['user'] = session('user');        $counts = $Post            ->field('status,count(status) as total')            ->group('status')            ->where($where_count)            ->select();        $sum = 0;   // 记录总量        $trashFlag = false; // 回收站不计入总量        foreach ($counts as $count) {            if ($count['status'] == 3) {                $trashFlag = true;                continue;            }            $sum += intval($count['total']);        }        $this->assign("sum", $sum);        if ($sum > 0 || $trashFlag) {            $this->assign("counts", $counts);            // 判断状态            $status = I('get.post_status');            if (empty($status)) {                $where['status'] = array('neq', 3);            } else {                switch ($status) {                    case "publish": // 已发布                        $where['status'] = 1;                        break;                    case "draft":   // 草稿                        $where['status'] = 2;                        break;                    case "trash":   // 回收站                        $where['status'] = 3;                        break;                }            }            // 获取日志列表            $Post = $Post->alias('p');            $where['p.user'] = session('user');            $page = init_page($Post, $where, 10); // 分页            $data = $Post                ->field('id,title,post_date,status,count(c.post_id) as total')                ->join('LEFT JOIN __COMMENT__ c ON p.id = c.post_id')                ->group('p.id')                ->order('post_date desc, id asc')                ->where($where)                ->limit($page->firstRow . ',' . $page->listRows)                ->select();            $this->assign("logs", $data);            $this->assign('page', bootstrap_page_style($page->show()));   // 赋值分页输出        }        $this->display('index');    }    /**     * 新建     */    public function add()    {        $this->initLogged();        $this->display('add');    }    /**     * 消息处理——新建/编辑     */    public function post()    {        $this->checkLogged();        $post_date = date("Y-m-d H:i:s");        $id = I('post.p/d');        $data['title'] = I('post.title/s');        $data['content'] = I('post.content/s', '', '');        $data['status'] = I('post.status/d');        $data['edit_date'] = $post_date;        $m = M("post");        if ($id == 0) { // 新增            $data['user'] = session('user');            $data['post_date'] = $post_date;            $result = $m->add($data);            $insertId = $m->getLastInsID(); // 获取insert id        } else {    // 编辑            $where['id'] = $id;            $where['user'] = session('user');            $result = $m->where($where)->save($data);            $insertId = $id;        }        // 更新缓存文件        set_archive_cache(session('user'));        // 响应        if ($result > 0) {            $ajaxData['status'] = 1;            $ajaxData['info'] = U('Post/edit') . '?id=' . $insertId;        } else {            $ajaxData['status'] = 0;            $ajaxData['info'] = '操作失败！';        }        $this->ajaxReturn($ajaxData, 'JSON');    }    /**     * 消息处理——垃圾箱     */    public function trash()    {        $this->checkLogged();        $action = I('get.action');        $id = I('get.p/d');        if (!empty($action) && !empty($id)) {            $m = M("post");            if ($action == "trash" || $action == "untrash") {   // 移入/移出垃圾箱                $where['id'] = $id;                $where['user'] = session('user');                $action == "trash" ? $data['status'] = 3 : $data['status'] = 2;                $m->where($where)->save($data);            } else if ($action == "delete") {                $user = session('user');                // 需要同时删除评论                $sql = "DELETE p, c                           FROM `log_post` p                           LEFT JOIN `log_comment` c ON p.`id` = c.`post_id`                         WHERE p.`id` = $id AND p.`user` = '$user'";                $m->execute($sql);            }        }        // 更新缓存文件        set_archive_cache(session('user'));        $this->redirect('Post/index');    }    /**     * 编辑     */    public function edit()    {        $this->initLogged();        $id = I('get.id/d');        if (empty($id))            $this->redirect("Index/index");        else {            $where['id'] = array('eq', $id);            $where['user'] = array('eq', session('user'));            $where['status'] = array('neq', 3);            $msg = M('post')->where($where)->find();            if (empty($msg)) {                $this->error("日志不存在或已被删除！");            }            $this->assign("id", $id);            $this->assign("title", $msg['title']);            $this->assign("content", $msg['content']);            $this->assign("edit_date", $msg['edit_date']);            $this->assign("status", intval($msg['status']));            $this->display('add');        }    }    /**     * 消息处理——文件上传     */    public function upload()    {        $upload = new \Think\Upload();  // 实例化上传类        $upload->maxSize = 31457280;    // 设置附件上传大小，30M//        $upload->exts = array('xls'); // 设置附件上传类型        $upload->autoSub = false;  // 自动使用子目录保存上传文件 默认为true        $upload->subName = array('date', 'Ymd');   // 文件命名方式以日期时间戳命名        $upload->rootPath = './Public/'; // 设置附件上传根目录        $upload->savePath = 'files/'; // 设置附件上传（子）目录        // 上传文件        $info = $upload->upload();        $ajaxData['status'] = 0;        if (!$info) {   // 上传错误提示错误信息            $ajaxData['msg'] = $upload->getError();            $this->ajaxReturn($ajaxData, 'JSON');        }        // 上传成功 获取上传文件信息        $filename = ''; // 保存完整路径的文件名        foreach ($info as $file) {            $filename = $file['savename'];        }        $ajaxData['status'] = 1;        $ajaxData['msg'] = $filename;        $this->ajaxReturn($ajaxData, 'JSON');    }}