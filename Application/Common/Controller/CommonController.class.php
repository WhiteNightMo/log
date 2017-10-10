<?php
/**
 * 公共控制器
 *
 * 方法：
 *      initLogin：初始化登录
 *      checkLogged：检查登录
 *      initCookies：获取cookies中的内容
 *      setCookies：设置cookies
 *      getUserId：获取user_id
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */

namespace Common\Controller;


use Think\Controller;

class CommonController extends Controller
{
    /**
     * 初始化函数
     */
    public function _initialize()
    {
    }


    /**
     * 初始化登录
     */
    public function initLogin()
    {
        if (!$this->checkLogged()) {    // 检查登录
            if ($this->initCookies()) {  // 从cookie中读取用户信息

            } else { // 跳转登录
                $this->redirect('User/login');
            }
        }
    }


    /**
     * 检查登录
     *
     * @return bool
     */
    private function checkLogged()
    {
        if (session('user') && session('user_id') && session('nickname')) {
            return true;
        }
        return false;
    }


    /**
     * 获取cookies中的内容
     */
    public function initCookies()
    {
        if (cookie('login')) {
            $cookies = explode('|', cookie('login'));
            session('user', $cookies[0]);
            session('user_id', $cookies[1]);
            session('nickname', $cookies[2]);
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
        session('user', $cookies[0]);
        session('user_id', $cookies[1]);
        session('nickname', $cookies[2]);
    }


    /**
     * 获取user_id
     *
     * @return mixed
     */
    protected function getUserId()
    {
        $userId = session('user_id');
        if (empty($userId)) {
            $userId = C('DEFAULT_USER_ID');
        }
        return $userId;
    }
}