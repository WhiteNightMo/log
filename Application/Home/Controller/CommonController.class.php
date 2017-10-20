<?php
/**
 * Home模块公共控制器
 *
 * @author xiaomo<i@nixiaomo.com>
 */
namespace Home\Controller;


class CommonController extends \Common\Controller\CommonController
{
    /**
     * 初始化函数
     */
    public function _initialize()
    {
        if (!$this->checkLogged()) {
            $this->initCookies();
        }
    }
}