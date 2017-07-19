<?php
/**
 * Link模型
 *
 * @author xiaomo<xiaomo@nixiaomo.com>
 */

namespace Home\Model;


use Think\Model;

class LinkModel extends Model
{
    protected $_validate = array(
        array('title', '1,32', '友链名请保持在32个字符以内！', 0, 'length'),
        array('introduction', '1,255', '友链简介请保持在255个字符以内！', 2, 'length'),
        array('url', '/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/', 'URL格式有误！', 0, 'regex'),
    );
}