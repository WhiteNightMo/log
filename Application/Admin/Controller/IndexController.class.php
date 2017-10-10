<?php
/**
 * 首页控制器
 */
namespace Admin\Controller;


class IndexController extends CommonController
{
    public function index()
    {
        $this->display('Page/index');
    }
}