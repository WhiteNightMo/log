<?php/** * 文章管理 * * 操作： *      index：文章列表 *      add：新建 *      edit：编辑 * * 消息处理： *      post：新建/编辑 *      trash：垃圾箱 *      upload：文件上传 * * @author xiaomo<i@nixiaomo.com> */namespace Admin\Controller;class PostController extends CommonController{    /**     * 文章列表     */    public function index()    {        $Post = M('Posts');        // 获取各分类数量        $where_count['user_id'] = session('user_id');        $counts = $Post            ->field('status,count(status) as total')            ->group('status')            ->where($where_count)            ->select();        $sum = 0;   // 记录总量        $trashFlag = false; // 回收站不计入总量        foreach ($counts as $count) {            if ($count['status'] == 3) {                $trashFlag = true;                continue;            }            $sum += intval($count['total']);        }        $this->assign("sum", $sum);        if ($sum > 0 || $trashFlag) {            $this->assign("counts", $counts);            // 判断状态            $status = I('get.post_status');            if (empty($status)) {                $where['status'] = array('neq', 3);            } else {                switch ($status) {                    case "publish": // 已发布                        $where['status'] = 1;                        break;                    case "draft":   // 草稿                        $where['status'] = 2;                        break;                    case "trash":   // 回收站                        $where['status'] = 3;                        break;                }            }            // 获取日志列表            $Post = $Post->alias('p');            $where['p.user_id'] = session('user_id');            $page = init_page($Post, $where, 10); // 分页            $data = $Post                ->field('id,title,edit_date,status,count(c.post_id) as total')                ->join('LEFT JOIN __COMMENTS__ c ON p.id = c.post_id')                ->group('p.id')                ->order('edit_date desc, id asc')                ->where($where)                ->limit($page->firstRow . ',' . $page->listRows)                ->select();            $this->assign("logs", $data);            $this->assign('page', bootstrap_page_style($page->show()));   // 赋值分页输出        }        $this->display('index');    }    /**     * 新建     */    public function add()    {        $this->display('add');    }    /**     * 消息处理——新建/编辑     */    public function post()    {        $id = I('post.p/d');        $data['title'] = I('post.title/s');        $data['content'] = I('post.content/s', '', '');        $data['status'] = I('post.status/d');        // 验证数据        $Post = D('Posts');        $ajaxData['status'] = 0;        if (!$Post->create($data)) {            $ajaxData['msg'] = $Post->getError();   // 返回错误状态            $this->ajaxReturn($ajaxData, 'JSON');        }        // 新增或编辑        $userId = session('user_id');//        $data['edit_date'] = date("Y-m-d H:i:s"); // 默认CURRENT_TIMESTAMP，且根据当前时间戳自动更新        if ($id == 0) { // 新增            $data['user_id'] = $userId;            $data['post_date'] = date("Y-m-d H:i:s");            $result = $Post->add($data);            $insertId = $Post->getLastInsID(); // 获取insert id        } else {    // 编辑            $where['id'] = $id;            $where['user_id'] = $userId;            $result = $Post->where($where)->save($data);            $insertId = $id;            // 删除旧的tag_id            M('post_tag')->where(array('post_id' => $insertId))->delete();        }        // 整理tags数组        $temps = I('post.tags');        $tags = [];        $mTags = M('tags');        foreach ($temps as $temp) {            $tag['post_id'] = $insertId;            if (is_numeric($temp)) {                $tag['tag_id'] = $temp;            } else {    // 如果是中文且数据库中不存在，则新建并获取自增id                $tag_id = $mTags->field('id')->where(array('name' => $temp))->find();                if (empty($tag_id))                    $tag['tag_id'] = $mTags->add(array('name' => $temp));                else {                    $tag['tag_id'] = $tag_id['id'];                }            }            $tags[] = $tag;        }        // 插入        if (!empty($tags)) {            M('post_tag')->addAll($tags);        }        // 响应        if (($id == 0 && $result) || ($id > 0 && $result !== false)) {            $ajaxData['status'] = 1;            $ajaxData['msg'] = '保存成功！';            $ajaxData['url'] = U('Post/edit') . '?id=' . $insertId;        } else {            $ajaxData['msg'] = '操作失败！';        }        $this->ajaxReturn($ajaxData, 'JSON');    }    /**     * 消息处理——垃圾箱     */    public function trash()    {        $action = I('get.action');        $id = I('get.p/d');        $userId = session('user_id');        if (!empty($action) && !empty($id)) {            $Post = M("Posts");            if ($action == "trash" || $action == "untrash") {   // 移入/移出垃圾箱                $where['id'] = $id;                $where['user_id'] = $userId;                $action == "trash" ? $data['status'] = 3 : $data['status'] = 2;                $Post->where($where)->save($data);            } else if ($action == "delete") {                // 删除评论                $sql = "DELETE p, c                           FROM __POSTS__ p                           LEFT JOIN __COMMENTS__ c ON p.`id` = c.`post_id`                         WHERE p.`id` = $id AND p.`user_id` = '$userId'";                $result = $Post->execute($sql);                // 删除标签                if ($result) {                    M('post_tag')->where(array('post_id' => $id))->delete();                }            }        }        $this->redirect('Post/index');    }    /**     * 编辑     */    public function edit()    {        $id = I('get.id/d');        if (empty($id))            $this->redirect("Index/index");        else {            // 获取文章内容            $where['id'] = array('eq', $id);            $where['user_id'] = array('eq', session('user_id'));            $where['status'] = array('neq', 3);            $msg = M('Posts')->field('user_id,post_date', true)->where($where)->find();            if (empty($msg)) {                $this->error("日志不存在或已被删除！");            }            $this->assign('post', $msg);            // 获取当前文章的标签            $tags = M('post_tag')->alias('pt')                ->field('tag_id as id,name')                ->join('LEFT JOIN __TAGS__ t ON pt.tag_id = t.id')                ->order('pt.id ASC')                ->where(array('post_id' => $id))                ->select();            $this->assign('tags', $tags);            $this->display('add');        }    }    /**     * 消息处理——文件上传     */    public function upload()    {        $upload = new \Think\Upload();  // 实例化上传类        $upload->maxSize = 31457280;    // 设置附件上传大小，30M//        $upload->exts = array('xls'); // 设置附件上传类型        $upload->autoSub = false;  // 自动使用子目录保存上传文件 默认为true        $upload->subName = array('date', 'Ymd');   // 文件命名方式以日期时间戳命名        $upload->rootPath = './Public/'; // 设置附件上传根目录        $upload->savePath = 'files/'; // 设置附件上传（子）目录        // 上传文件        $info = $upload->upload();        $ajaxData['status'] = 0;        if (!$info) {   // 上传错误提示错误信息            $ajaxData['msg'] = $upload->getError();            $this->ajaxReturn($ajaxData, 'JSON');        }        // 上传成功 获取上传文件信息        $filename = ''; // 保存完整路径的文件名        foreach ($info as $file) {            $filename = $file['savename'];        }        $ajaxData['status'] = 1;        $ajaxData['msg'] = $filename;        $this->ajaxReturn($ajaxData, 'JSON');    }}