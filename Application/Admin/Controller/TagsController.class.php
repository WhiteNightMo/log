<?php
/**
 * 标签管理
 *
 * 操作：
 *      index：标签
 *      delete：删除
 *
 * 消息处理：
 *      api：select2获取标签
 *
 * @author xiaomo<xiaomo@etlinker.com>
 * @copyright Copyright(C)2016 Wuhu Yichuan Network Technology Corporation Ltd. All rights reserved.
 */

namespace Admin\Controller;


class TagsController extends CommentController
{
    /**
     * 标签
     */
    public function index()
    {
        // 获取标签
        $tags = M('Tags')->alias('t')
            ->field('t.id,t.name,t.created_at,COUNT(tag_id) AS count')
            ->join('LEFT JOIN __POST_TAG__ pt ON t.id = pt.tag_id')
            ->join('LEFT JOIN __POSTS__ p ON pt.post_id = p.id')
            ->where(array('p.status' => 1))
            ->group('tag_id')
            ->order('t.id DESC')
            ->select();

        $this->assign('tags', $tags);
        $this->display('Page/tags');
    }


    /**
     * 消息处理——删除
     */
    public function delete()
    {
        $id = I('post.id/d');
        $ajaxData['status'] = 0;
        if (empty($id)) {
            $ajaxData['msg'] = '参数有误';
            $this->ajaxReturn($ajaxData);
        }
        if (session('user_id') != C('DEFAULT_USER_ID')) {
            $ajaxData['msg'] = '只有超级管理员才拥有标签删除权限';
            $this->ajaxReturn($ajaxData);
        }


        // 删除数据
        $sql = "DELETE t.*, pt.*
                FROM
                    __TAGS__ t
                LEFT JOIN __POST_TAG__ pt ON t.`id` = pt.`tag_id`
                WHERE
                    t.`id` = {$id}";
        $result = M()->execute($sql);
        if (!$result) {
            $ajaxData['msg'] = '删除失败';
        } else {
            $ajaxData['status'] = 1;
            $ajaxData['msg'] = '删除成功';
        }
        $this->ajaxReturn($ajaxData);
    }


    /**
     * 消息处理——select2获取标签
     */
    public function api()
    {
        $q = I('get.q');
        $where['name'] = array('like', '%' . $q . '%');
        $tags = M('tags')->where($where)->select();
        $this->ajaxReturn($tags, 'JSON');
    }
}