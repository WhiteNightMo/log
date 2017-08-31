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
 * @author xiaomo<xiaomo@etlinker.com>
 * @copyright Copyright(C)2016 Wuhu Yichuan Network Technology Corporation Ltd. All rights reserved.
 */

namespace Home\Controller;


class LinkController extends BaseController
{
    /**
     * 友链
     */
    public function index()
    {
        $this->initLogged(false);


        // 获取友链列表
        $links = M('Links')
            ->where(array('user_id' => $this->getUserId()))
            ->order('id ASC')
            ->select();

        $this->assign('links', $links);
        $this->display();
    }


    /**
     * 消息处理——新增/编辑
     */
    public function create()
    {
        $this->initLogged();

        $id = I('post.link_id/d');
        $title = I('post.title');
        $url = I('post.url');
        $intro = I('post.intro');
        $ajaxData['status'] = 0;
        if ((empty($id) && $id != 0) || empty($title) || empty($url)) {
            $ajaxData['msg'] = '参数有误';
            $this->ajaxReturn($ajaxData);
        }


        // 封装数据并验证
        $data['title'] = $title;
        $data['url'] = $url;
        $data['intro'] = $intro;
        $Link = D('Links');
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
        $this->initLogged();

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