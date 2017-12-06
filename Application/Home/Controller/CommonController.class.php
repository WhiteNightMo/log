<?php
/**
 * Home模块公共控制器
 *
 * @author xiaomo<i@nixiaomo.com>
 */
namespace Home\Controller;


use Common\Controller\BaseController;

class CommonController extends BaseController
{
    /**
     * 初始化函数
     */
    public function _initialize()
    {
        parent::_initialize();

        if (!$this->checkLogged()) {
            $this->initCookies();
        }
    }
}