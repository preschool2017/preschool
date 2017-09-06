<?php
require_once('cy-global.php');

$callback = $_GET['callback']; 

global $current_user;
get_currentuserinfo();
$uid = $current_user->ID;

if($uid == 0) {
    // 如果用户未登陆，返回未登陆的信息
    $json = json_encode(array('is_login' => 0));
    echo jsonp($callback, $json);
} else {
    // 如果用户登陆，则返回登陆的信息
    $result = array('is_login'=>1, 'user'=>array());
    // 用户id
    $result['user']['user_id'] = $current_user->ID;
    // 用户昵称
    $result['user']['nickname'] = $current_user->user_nicename;
    // 用户头像，如果没有可以返回""
    $result['user']['img_url'] = get_avatar_src($current_user->user_email);
    // 用户主页地址。如果没有可以返回""
    $result['user']['profile_url'] = "";
     // 获取指纹供畅言验证
     $result['user']['sign'] = get_sign($result['user']['user_id'], $result['user']['nickname'], $result['user']['img_url'], $result['user']['profile_url']);
    // 输出
     echo jsonp($callback, json_encode($result));
}
