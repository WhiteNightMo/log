<?php
/**
 * 标签管理
 *
 * 操作：
 *      index：标签
 *
 * 消息处理：
 *      api：select2获取标签
 *
 * @author xiaomo<xiaomo@etlinker.com>
 * @copyright Copyright(C)2016 Wuhu Yichuan Network Technology Corporation Ltd. All rights reserved.
 */

namespace Home\Controller;


class TagsController extends BaseController
{
    /**
     * 标签
     */
    public function index()
    {
        $this->initLogged(false);


        // 获取标签
        $tags = M('Tags')->alias('t')
            ->field('name,COUNT(tag_id) AS count')
            ->join('LEFT JOIN __POST_TAG__ pt ON t.id = pt.tag_id')
            ->group('tag_id')
            ->order('t.id DESC')
            ->select();

        $this->assign('tags', $tags);
        $this->display('Page/tags');
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