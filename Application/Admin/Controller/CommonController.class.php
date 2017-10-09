<?php
/**
 * 公共控制器
 *
 * 操作：
 *      _initialize：初始化函数
 *      initLogin：初始化登录
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */

namespace Admin\Controller;


use Think\Controller;

class CommonController extends Controller
{
    /**
     * 初始化函数
     */
    public function _initialize()
    {
        $this->initLogin();
    }


    /**
     * 初始化登录
     *
     * @param bool $isLogin
     */
    public function initLogin($isLogin = false)
    {
        if (!$this->checkLogged()) {
            if ($this->initCookies()) {  // 从cookie中读取用户名

            } else if (!$isLogin) { // 跳转登录
                $this->redirect('User/login');
            } else {    // 显示登录
                $this->display('User/login');
            }
        } else if ($isLogin) { // 登录状态则跳转首页
            $this->redirect('Index/index');
        }
    }


    /**
     * 检查登录
     *
     * @return bool
     */
    private function checkLogged()
    {
        if ($this->getUser() && $this->getUserId() && $this->getNickname()) {
            return true;
        }
        return false;
    }


    /**
     * 获取cookies中的内容
     */
    protected function initCookies()
    {
        if (cookie('login')) {
            $cookies = explode('|', cookie('login'));
            $this->setUser($cookies[0]);
            $this->setUserId($cookies[1]);
            $this->setNickname($cookies[2]);
            return true;
        }
        return false;
    }

    /**
     * 设置cookies
     *
     * @param array $cookies
     */
    protected function setCookies($cookies)
    {
        cookie('login', implode('|', $cookies));
    }

    /**
     * 获取user
     */
    protected function getUser()
    {
        return session('user');
    }

    /**
     * 设置user
     *
     * @param $user
     */
    protected function setUser($user)
    {
        session('user', $user);
    }

    /**
     * 获取user_id
     */
    protected function getUserId()
    {
        return session('user_id');
    }

    /**
     * 设置user_id
     *
     * @param $user_id
     */
    protected function setUserId($user_id)
    {
        session('user_id', $user_id);
    }

    /**
     * 获取nickname
     */
    protected function getNickname()
    {
        return session('nickname');
    }

    /**
     * 设置nickname
     *
     * @param $nickname
     */
    protected function setNickname($nickname)
    {
        session('nickname', $nickname);
    }
}