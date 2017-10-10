<?php
/**
 * Post模型
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */

namespace Admin\Model;


use Think\Model;

class PostModel extends Model
{
    protected $_validate = array(
        array('title', '1,64', '标题请保持在64个字符以内！', 0, 'length'),
    );
}