<?php
/**
 * 首页控制器
 *
 * @author xiaomo<i@nixiaomo.com>
 */
namespace Admin\Controller;


class IndexController extends CommonController
{
    public function index()
    {
        $this->display('Page/index');
    }
}