<?php
/**
 * Created by PhpStorm.
 * User: xiaomo
 * Date: 2016/9/8
 * Time: 10:09
 */


/**
 * 读取数据缓存
 * @param string $user 用户名
 * @param string $type 类型
 * @return bool|mixed|string
 */
function get_data_cache($user, $type)
{
    $cache_dir = "./Public/cache/";
    $archive_file = "{$type}_{$user}.txt";

    $file = $cache_dir . $archive_file;
    $msg = file_get_contents($file);
    if (empty($msg)) {  // 为空则从数据库读取
        $method = "set_{$type}_cache";
        $msg = $method($user);
        return $msg;
    }
    $result = unserialize($msg);
    return $result;
}


/**
 * 设置数据缓存
 * @param string $filename 文件名
 * @param $data 存储的数据
 * @return mixed
 */
function set_data_cache($filename, $data)
{
    $cache_dir = "./Public/cache/";
    $msg = serialize($data);
    $file = $cache_dir . $filename;
    file_put_contents($file, $msg, LOCK_EX);
    return $data;
}


/**
 * 设置归档缓存
 * @param string $user
 * @return mixed
 */
function set_archive_cache($user)
{
    $archive_file = "archive_$user.txt";

    // 201608,2
    $data = M("post")->query("SELECT date_format(`post_date`, '%Y%m') AS month, count(*) AS count FROM `log_post`
 WHERE `user` = '$user' AND `status` = 1
 GROUP BY date_format(`post_date`, '%Y%m')
 ORDER BY date_format(`post_date`, '%Y%m') DESC");

    return set_data_cache($archive_file, $data);
}