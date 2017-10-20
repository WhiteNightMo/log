<?php
/**
 * 友链管理
 *
 * 操作：
 *      index：友链
 *
 * 消息处理：
 *      create：新增/编辑
 *      delete：删除
 *
 * @author xiaomo<i@nixiaomo.com>
 */

namespace Admin\Controller;


class LinkController extends CommonController
{
    /**
     * 友链
     */
    public function index()
    {
        // 获取友链列表
        $links = M('Links')
            ->field('created_at', true)
            ->where(array('user_id' => session('user_id')))
            ->order('id ASC')
            ->select();

        $this->assign('links', $links);
        $this->display('Page/links');
    }


    /**
     * 消息处理——新增/编辑
     */
    public function create()
    {
        $data = I('post./a');
        $id = $data['link_id'];
        unset($data['link_id']);

        // 验证
        $Link = D('Links');
        $ajaxData['status'] = 0;
        if (!$Link->create($data)) {
            $ajaxData['msg'] = $Link->getError();   // 返回错误状态
            $this->ajaxReturn($ajaxData, 'JSON');
        }


        // 新增
        $status = true;
        $userId = session('user_id');
        if ($id == 0) {
            $data['user_id'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $result = $Link->add($data);
            if (!$result) {
                $status = false;
            } else {
                $ajaxData['msg'] = '新增成功';
            }
        } else {    // 编辑
            $where['id'] = $id;
            $where['user_id'] = $userId;
            $result = $Link->where($where)->save($data);
            if ($result === false) {
                $status = false;
            } else {
                $ajaxData['msg'] = '更新成功';
            }
        }


        // 响应数据
        if (!$status) {
            $ajaxData['msg'] = $result;
        } else {
            $ajaxData['status'] = 1;
        }
        $this->ajaxReturn($ajaxData, 'JSON');
    }


    /**
     * 消息处理——删除
     */
    public function delete()
    {
        $id = I('post.link_id/d');
        $ajaxData['status'] = 0;
        if (empty($id)) {
            $ajaxData['msg'] = '参数有误';
            $this->ajaxReturn($ajaxData);
        }


        // 删除数据
        $result = M('Links')
            ->where(array('id' => $id, 'user_id' => session('user_id')))
            ->delete();
        if (!$result) {
            $ajaxData['msg'] = '删除失败';
        } else {
            $ajaxData['status'] = 1;
            $ajaxData['msg'] = '删除成功';
        }
        $this->ajaxReturn($ajaxData);
    }
}