<?php
/**
 * 归档管理
 *
 * 操作：
 *      index：归档中心
 *
 * @author xiaomo<xiaomo@etlinker.com>
 * @copyright Copyright(C)2016 Wuhu Yichuan Network Technology Corporation Ltd. All rights reserved.
 */

namespace Home\Controller;


class ArchiveController extends BaseController
{
    /**
     * 归档中心
     */
    public function index()
    {
        $this->initLogged(false);


        // 获取文章列表
        $data = M('post')
            ->field('id,title,post_date')
            ->where(array('status' => 1))
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
        $this->display();
    }
}