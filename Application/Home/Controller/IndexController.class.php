<?php/** * 首页、详情页 * * 操作： *      index：首页，文章列表 *      detail：日志详情 * * 私有方法： *      _formatComments：格式化评论数据 *      _binarySearch：二分查找获取一维数组的index * * @author xiaomo<xiaomo@nixiaomo.com> */namespace Home\Controller;class IndexController extends BaseController{    /**     * 首页，文章列表     */    public function index()    {        // 从cookie中读取用户名        $login = cookie('log_login');        if ($login) session("user", explode('|', $login)[0]);        // 查看指定作者的日志        $author = I('get.author');        if (!empty($author)) {            $where['log_post.user'] = $author;            $this->assign("author", "作者：" . $author);            $archive = I('get.archive');            if (!empty($archive)) {  // 指定日期                $archive = $archive . '01';                $format = date('Y-m', strtotime($archive));                $where['log_post.post_date'] = array('like', $format . '%');                $this->assign("author", $archive . "—作者：" . $author);            }        }        // 查询所有日志以及评论量        $where['status'] = 1;        $m = M('post');        $page = init_page($m, $where, 10); // 分页        /**         * 老大的技术支援         * $sql = "SELECT p.*,COUNT(c.post_id) AS total FROM log_post AS p         * LEFT JOIN log_comment AS c ON p.id = c.post_id WHERE p.status = 1         * GROUP BY p.id ORDER BY p.post_date DESC, p.id ASC";         * $data = M()->query($sql);         */        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性        $data = $m->field('id,title,log_post.user,post_date,log_post.content,count(log_comment.post_id) as total')            ->join('LEFT JOIN log_comment ON log_post.id = log_comment.post_id')            ->group('log_post.id')            ->order('post_date desc, id asc')            ->where($where)            ->limit($page->firstRow . ',' . $page->listRows)            ->select();        if (empty($data)) $this->assign("author", "有点尴尬唉！没有捕捉到内容。");        $this->assign("logs", $data);        $this->assign('page', $page->show());   // 赋值分页输出        $this->display('index');    }    /**     * 日志详情     */    public function detail()    {        $post_id = I('get.p/d');        if (empty($post_id)) {            $this->assign('tips', '有点尴尬唉！没有捕捉到内容。');        } else {            // 消息推送模块进入            $comment_id = I('get.comment/d');            if (!empty($comment_id) && session('user')) {                $userWhere['comment_respond'] = session('user');                $userWhere['user'] = session('user');                $userWhere['_logic'] = 'or';                $where['_complex'] = $userWhere;                $where['comment_id'] = intval($_GET['comment']);                M('comment')->where($where)->save(array("comment_pushed" => 1));            }            // 根据id获取指定日志            $where = array();            $where['id'] = $post_id;            $where['status'] = 1;            $log = M('post')->field('edit_date,status', true)                ->order('post_date desc, id desc')->where($where)->find();            if (empty($log)) $this->assign("tips", "有点尴尬唉！没有捕捉到内容。");            else {                session('redirect_to', U('Index/detail') . '?p=' . $post_id);                if (session('user') && session('user') == $log['user'])                    $this->assign('identity', "author");    // 访问者为日志发布者                $this->assign('log', $log); // 日志内容                // 根据post_id获取所有评论                $where = array();                $where['post_id'] = $post_id;                $comment_data = M('comment')->field('post_id', true)                    ->order('comment_date asc, comment_id asc')->where($where)->select();                if (!empty($comment_data)) {                    $this->assign('comment_title', '《' . $log['title'] . '》有' . count($comment_data) . '条评论');                    // 格式化评论数据                    $newComments = $this->_formatComments($comment_data);                    $this->assign('comment_data', $newComments);                }            }        }        $this->display('detail');    }    /**     * 格式化评论数据     *     * @param $comment_data     * @return array     */    private function _formatComments($comment_data)    {        $newComments = array();        foreach ($comment_data as $comment) {            $parentId = intval($comment['comment_parent']);            if ($parentId == 0) {   // 一级评论                $newComments[] = $comment;            } else {    // 二级评论                $index = $this->_binarySearch($newComments, $parentId);  // 二分查找获取index                if ($index < 0) continue;                $newComments[$index]["children_list"][] = $comment;            }        }        return $newComments;    }    /**     * 二分查找获取一维数组的index     *     * @param array $arr     * @param int $target     * @return float|int     */    private function _binarySearch($arr, $target)    {        $low = 0;        $high = count($arr) - 1;        while ($low <= $high) {            $mid = floor(($low + $high) / 2);            // 找到元素            if (intval($arr[$mid]["comment_id"]) == $target) return $mid;            // 中元素比目标大,查找左部            if (intval($arr[$mid]["comment_id"]) > $target) $high = $mid - 1;            // 中元素比目标小,查找右部            if (intval($arr[$mid]["comment_id"]) < $target) $low = $mid + 1;        }        // 查找失败        return -1;    }}