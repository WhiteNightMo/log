<?php
/**
 * 消息评论管理
 *
 * 操作：
 *      index：消息中心
 *
 * 消息处理：
 *      post：新增评论
 *      remove：删除评论
 *      batch：批量操作
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */

namespace Home\Controller;

class CommentController extends BaseController
{
    /**
     * 消息中心
     */
    public function index()
    {
        $this->initLogged();

        $Comment = M('comment')->alias('c');
        // 回复我或评论我的文章，且不是我自己做的
        $userWhere['comment_respond'] = session('user');
        $userWhere['p.user'] = session('user');
        $userWhere['_logic'] = "or";
        $where['_complex'] = $userWhere;    // 复合查询
        $where['p.status'] = 1;
        $where['c.user'] = array('neq', session('user'));

        // 获取评论信息
        $join = "RIGHT JOIN __POST__ p ON c.post_id = p.id";
        $page = init_page($Comment, $where, 10, $join); // 分页
        $comments = $Comment
            ->field("id,title,c.*")
            ->join($join)
            ->where($where)
            ->order("comment_pushed desc, comment_date desc, comment_id desc")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();

        $this->assign("comments", $comments);
        $this->assign('page', bootstrap_page_style($page->show()));   // 赋值分页输出

        $this->display('index');
    }


    /**
     * 消息处理——新增评论
     */
    public function post()
    {
        $this->checkLogged();

        $data['user'] = session('user');
        $data['comment_respond'] = I('post.respond');
        $data['comment_date'] = date('Y-m-d H:i:s');
        $data['post_id'] = I('post.post/d');
        $data['content'] = I('post.comment');
        $data['comment_parent'] = I('post.comment_id/d');

        // 响应
        $result = M('comment')->add($data);
        if ($result > 0) {
            $ajaxData['status'] = 1;
            $ajaxData['info'] = '操作成功';

        } else {
            $ajaxData['status'] = 0;
            $ajaxData['info'] = '操作失败！';
        }
        $this->ajaxReturn($ajaxData, 'JSON');
    }


    /**
     * 消息处理——删除评论
     */
    public function remove()
    {
        $this->checkLogged();

        $comment_id = I('request.comment_id/d');
        // 参数验证
        $ajaxData['status'] = 0;
        if (empty($comment_id)) {
            $ajaxData['info'] = '参数有误';
            $this->ajaxReturn($ajaxData, 'JSON');
        }

        $userWhere['comment_respond|user'] = session('user');
        $where['comment_id|comment_parent'] = $comment_id;
        $where['_logic'] = 'or';
        $result = M("comment")->where($where)->delete();

        // 响应
        if (empty(I('post.comment_id/d'))) {  // post，Index/detail
            if ($result > 0) {
                $ajaxData['status'] = 1;
                $ajaxData['info'] = '操作成功';

            } else {
                $ajaxData['status'] = 0;
                $ajaxData['info'] = '操作失败！';
            }
            $this->ajaxReturn($ajaxData, 'JSON');

        } else {    // get，Comment/index
            $this->redirect('Comment/index');
        }
    }


    /**
     * 消息处理——批量操作
     */
    public function batch()
    {
        $this->checkLogged();
        $marks = I('post.marks/d');
        $status = I('post.status/d');
        // 参数验证
        $ajaxData['status'] = 0;
        if (empty($marks) || empty($status)) {
            $ajaxData['info'] = '参数有误';
            $this->ajaxReturn($ajaxData, 'JSON');
        }


        if ($status == "read") {    // 标记为已读
            $sql = "UPDATE __COMMENT__ SET `comment_pushed` = CASE `comment_id` ";
            foreach (explode(",", $marks) as $id) {
                $sql .= sprintf("WHEN %d THEN %d ", $id, 1);
            }
            $sql .= "END WHERE `comment_id` IN ($marks)";

            $ajaxData['status'] = M()->execute($sql);
            $ajaxData['info'] = '已标记为已读';

        } else if ($status == "remove") {   // 批量删除
            $userWhere['comment_respond|user'] = session('user');
            $where['comment_id|comment_parent'] = array('in', $marks);
            $where['_logic'] = 'or';

            $ajaxData['status'] = M("comment")->where($where)->delete();
            $ajaxData['info'] = '删除成功';

        } else {
            $ajaxData['info'] = '参数有误';
        }

        $this->ajaxReturn($ajaxData, 'JSON');
    }
}