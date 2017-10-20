<?php
/**
 * page
 *
 * 操作：
 *      archives：归档中心
 *      tags：标签
 *      links：友链
 *      about：关于
 *
 * @author xiaomo<xiaomo@etlinker.com>
 * @copyright Copyright(C)2016 Wuhu Yichuan Network Technology Corporation Ltd. All rights reserved.
 */
namespace Home\Controller;


class PageController extends CommonController
{
    /**
     * 归档中心
     */
    public function archives()
    {
        // 获取文章列表
        $data = M('Posts')
            ->field('id,title,post_date')
            ->where(array('status' => 1, 'user_id' => $this->getUserId()))
            ->order('post_date DESC')
            ->select();

        // 整理数据
        $logs = array();
        foreach ($data as $item) {
            $time = strtotime($item['post_date']);
            $year = date('Y', $time);
            $month = date('m', $time);
            $logs[$year][$month][] = $item;
        }

        $this->assign('logs', $logs);
        $this->display('Page/archives');
    }


    /**
     * 标签
     */
    public function tags()
    {
        // 获取标签
        $tags = M('Tags')->alias('t')
            ->field('name,COUNT(tag_id) AS count')
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
     * 友链
     */
    public function links()
    {
        // 获取友链列表
        $links = M('Links')
            ->field('created_at,updated_at', true)
            ->where(array('user_id' => $this->getUserId()))
            ->order('id ASC')
            ->select();

        $this->assign('links', $links);
        $this->display('Page/links');
    }


    /**
     * 关于
     */
    public function about()
    {
        $this->display('Page/about');
    }
}