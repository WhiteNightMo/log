<?php
/**
 * User模型
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */

namespace Home\Model;


use Think\Model;

class UserModel extends Model
{
    protected $_validate = array(
        array('user', '/^\w{5,16}$/', '用户名请填写5-16位由数字、字母或者下划线组成的字符！', 2, 'regex'),
    );
}