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
        $userId = session('user_id');
        // 自己文章的一级评论
        $cWhere['p.user_id'] = $userId; // 自己发布的文章
        $cWhere['c.comment_parent'] = 0;  // 一级评论
        // 我的一级评论的二级回复
        $parentWhere['_complex'] = $cWhere;
        $parentWhere['c_parent.user_id'] = array('eq', $userId);    // 父评论者是自己
        $parentWhere['_logic'] = "or";
        $where['_complex'] = $parentWhere;    // 复合查询
        $where['p.status'] = 1;
        $where['c.user_id'] = array('neq', $userId);

        // 获取评论信息
        $join = "LEFT JOIN __COMMENT__ c_parent ON c.comment_parent = c_parent.comment_id";
        $join .= " RIGHT JOIN __POST__ p ON c.post_id = p.id";
        $page = init_page($Comment, $where, 10, $join); // 分页
        $comments = $Comment
            ->field("id,title,c.comment_id,c.comment_author,c.comment_date,c.content,c.comment_parent,c.comment_pushed")
            ->join($join)
            ->where($where)
            ->order("c.comment_pushed desc, c.comment_date desc, c.comment_id desc")
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
        $postId = I('post.post/d');
        $parentId = I('post.comment_id/d');
        $parentAuthor = I('post.comment_author');
        $comment = I('post.comment');
        // 参数验证
        $ajaxData['status'] = 0;
        if (empty($postId) || (empty($parentId) && $parentId != 0) || empty($comment)) {
            $ajaxData['info'] = '参数有误';
            $this->ajaxReturn($ajaxData, 'JSON');
        }


        // 如果已登录，则根据user_id获取nickname、email、url
        $userId = session('user_id');
        if (!empty($userId)) {
            $userData = M('user')->field('nickname,email,url')->where(array('id' => $userId))->find();
            $data['user_id'] = $userId;
            $data['comment_author'] = $userData['nickname'];
            $data['comment_author_email'] = $userData['email'];
            $data['comment_author_url'] = $userData['url'];

        } else {
            // 未登录则获取前端传递的数据
            $nickname = I('post.nickname');
            $email = I('post.email');
            $url = I('post.url');
            if (empty($nickname) || empty($email) || empty($url)) {
                $ajaxData['info'] = '参数有误';
                $this->ajaxReturn($ajaxData, 'JSON');
            }

            $data['user_id'] = 0;
            $data['comment_author'] = $nickname;
            $data['comment_author_email'] = $email;
            $data['comment_author_url'] = $url;
        }


        // 封装数据
        $data['post_id'] = $postId;
        $data['comment_date'] = date('Y-m-d H:i:s');
        $data['content'] = $comment;
        $data['comment_parent'] = $parentId;
        $data['comment_parent_author'] = $parentAuthor;


        // 验证数据
        $Comment = D('comment');
        if (!$Comment->create($data)) {
            $ajaxData['info'] = $Comment->getError();   // 返回错误状态
            $this->ajaxReturn($ajaxData, 'JSON');
        }


        // 响应
        $result = $Comment->add($data);
        if (!$result) {
            $ajaxData['info'] = $result;
        } else {
            $ajaxData['status'] = 1;
            $ajaxData['info'] = '评论成功！';
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

        // 连带删除子评论
        $where['comment_id|comment_parent'] = $comment_id;
        $result = M("comment")->where($where)->delete();

        // 响应
        if (!empty(I('post.comment_id/d'))) {  // post，Index/detail
            if (!$result) {
                $ajaxData['info'] = $result;
            } else {
                $ajaxData['status'] = 1;
                $ajaxData['info'] = '删除成功！';
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
        $status = I('post.status');
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