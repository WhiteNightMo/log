<?php
/**
 * base控制器
 *
 * 操作：
 *      checkLogged：检查是否登录
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */

namespace Home\Controller;


use Think\Controller;

class BaseController extends Controller
{


    public function index()
    {
    }


    /**
     * 检查是否登录
     */
    protected function checkLogged()
    {
        if (!session('user')) $this->redirect('User/login');
    }
}