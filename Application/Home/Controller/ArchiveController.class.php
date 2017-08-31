<?php
/**
 * 归档管理
 *
 * 操作：
 *      index：归档中心
 *      about：关于
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
     * 关于
     */
    public function about()
    {
        $this->initLogged(false);
        $this->display('Page/about');
    }
}