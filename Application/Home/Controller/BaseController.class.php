<?php
/**
 * base控制器
 *
 * 操作：
 *      checkLogged：检查是否登录
 *      initLogged：初始化登录
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */

namespace Home\Controller;


use Think\Controller;

class BaseController extends Controller
{
    /**
     * 检查是否登录
     */
    protected function checkLogged()
    {
        if (!session('user')) {
            $this->redirect('User/login');
        }
    }


    /**
     * 初始化登录
     *
     * session中存在则跳过
     * cookie中存在则获取
     * 需要登录则跳转
     *
     * @param bool $jump 是否跳转
     */
    protected function initLogged($jump = true)
    {
        if (!session('user')) {
            if (cookie('login')) {  // 从cookie中读取用户名
                $loginData = explode('|', cookie('login'));
                session("user", $loginData[0]);
                session("user_id", $loginData[1]);
                session("nickname", $loginData[2]);
            } else if ($jump) { // 跳转登录
                $this->redirect('User/login');
            }
        }
    }
}