<?php
/**
 * Created by PhpStorm.
 * User: xiaomo
 * Date: 2016/8/30
 * Time: 10:31
 */

namespace Home\Controller;


use Think\Controller;

class CommentController extends Controller
{
    public function index()
    {
        if (!session('user')) exit('{"err": 0, "message": "请先登录再进行后续操作"}');

        $m = M('comment');
        // 回复我或评论我的文章，且不是我自己做的
        $userWhere['comment_respond'] = session('user');
        $userWhere['log_post.user'] = session('user');
        $userWhere['_logic'] = "or";
        $where['_complex'] = $userWhere;    // 复合查询
        $where['log_post.status'] = 1;
        $where['log_comment.user'] = array('neq', session('user'));

        // 获取评论信息
        $join = "RIGHT JOIN log_post ON log_comment.post_id = log_post.id";
        $page = getPage($m, $where, 10, $join); // 分页
        $comments = $m->field("id,title,log_comment.*")->join($join)->where($where)
            ->order("comment_pushed desc, comment_date desc, comment_id desc")
            ->limit($page->firstRow . ',' . $page->listRows)->select();

        $this->assign("comments", $comments);
        $this->assign('page', $page->show());   // 赋值分页输出

        $this->display('index');
    }

    /*
     * 新增评论
     */
    public function post()
    {
        if (!session('user')) exit('{"err": 0, "message": "请先登录再进行后续操作"}');

        $data['user'] = session('user');
        $data['comment_respond'] = $_POST['respond'];
        $data['comment_date'] = date('Y-m-d H:i:s');
        $data['post_id'] = intval($_POST['post']);
        $data['content'] = $_POST['comment'];
        $data['comment_parent'] = intval($_POST['comment_id']);

        $m = M('comment');
        $result = $m->add($data);
//        $insertId = $m->getLastInsID(); // 获取insert id

        // 响应
        if ($result > 0) echo '{"err": 1, "message": "操作成功"}';
        else echo '{"err": 0, "message": "操作失败！"}';
    }


    /*
     * 删除评论
     */
    public function remove()
    {
        if (!session('user')) exit('{"err": 0, "message": "请先登录再进行后续操作"}');
        if (!isset($_REQUEST['comment_id']) || intval($_REQUEST['comment_id']) == 0) exit('{"err": 0, "message": "参数有误"}');

        $comment_id = intval($_REQUEST['comment_id']);
        $userWhere['comment_respond|user'] = session('user');
        $where['comment_id|comment_parent'] = $comment_id;
        $where['_logic'] = 'or';
        $result = M("comment")->where($where)->delete();

        // 响应
        if (isset($_POST['comment_id'])) {  // post，Index/detail
            if ($result > 0) echo '{"err": 1, "message": "操作成功"}';
            else echo '{"err": 0, "message": "操作失败！"}';

        } else {    // get，Comment/index
            $this->redirect('Comment/index');
        }
    }

    /*
     * 批量操作
     */
    public function batch()
    {
        if (!session('user')) exit('{"err": 0, "message": "请先登录再进行后续操作"}');
        if (!isset($_POST['marks']) || !isset($_POST['status'])) exit("参数有误");

        $marks = $_POST['marks'];
        $status = $_POST['status'];
        if ($status == "read") {    // 标记为已读
            $sql = "UPDATE log_comment SET comment_pushed = CASE comment_id ";
            foreach (explode(",", $marks) as $id) {
                $sql .= sprintf("WHEN %d THEN %d ", $id, 1);
            }
            $sql .= "END WHERE comment_id IN ($marks)";
            echo M("comment")->execute($sql);

        } else if ($status == "remove") {   // 批量删除
            $userWhere['comment_respond|user'] = session('user');
            $where['comment_id|comment_parent'] = array('in', $marks);
            $where['_logic'] = 'or';
            echo M("comment")->where($where)->delete();

        } else exit("参数有误");
    }
}