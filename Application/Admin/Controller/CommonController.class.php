<?php
/**
 * 公共控制器
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */
namespace Admin\Controller;


class CommonController extends \Common\Controller\CommonController
{
    /**
     * 初始化函数
     */
    public function _initialize()
    {
        $this->initLogin();
    }
}