<?php

require_once('./cy-global.php');

$cy_user_id  = $_GET['cy_user_id'];
$nickname    = urldecode($_GET['nickname']);
$img_url     = urldecode($_GET['img_url']);
$profile_url = urldecode($_GET['profile_url']);
$user_id     = $_GET['user_id'];
$sign        = urldecode($_GET['sign']);
$callback    = $_GET['callback'];


//判断签名是否一致
if(get_sign($user_id, $nickname, $img_url, $profile_url, $cy_user_id) != $sign){
    exit(jsonp($callback, json_encode(array(code=>'1', msg=>'sign error'))));
}

//如果对站点来说不是新用户
if($user_id){
    wp_set_auth_cookie($user_id, true, false);
    wp_set_current_user($user_id);
    echo jsonp($callback, json_encode(array('code'=>1, 'user_id'=>$user_id, 'reload_page'=>0)));
} else {
    //如果对站点来说是新用户，则引导用户在该站点注册
    $result = array($web_host .'changyan/cy.js');
    echo jsonp($callback, json_encode(array('user_id'=>'0', 'js_src'=>$result, 'reload_page'=>0)));
}
?>
