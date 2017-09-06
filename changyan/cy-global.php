<?php
require_once('../wp-load.php');

$cy_secret = 'a0c5eeac8ad85d588c65292fb8fce49e';
$web_host = 'http://www.preschool.net.cn/';

// 将json转换为jsonp的方法
function jsonp($callback, $json) {
	if($callback) {
		return $callback.'('.$json.')';
	} else {
		return $json;
	}
}

// host和uri链接成url的方法
function concat_url($host, $uri) {
	if($host[strlen($host) - 1] == '/') {
		return $host.$uri;
	} else {
		return $host.'/'.$uri;
	}
}

// 获取sign。
// 在get-user-info接口中需要用4个参数生成sign，供畅言服务器验证。
// 在login接口中用5个参数验证畅言服务器传过来的sign
// 所有的参数都为原始值，即urldecode(_GET('paramName'))
function get_sign($user_id, $nickname, $img_url, $profile_url, $cy_uid = -1) {
	global $cy_secret;
	$str = "img_url=${img_url}&nickname=${nickname}&profile_url=${profile_url}&user_id=${user_id}";
	if($cy_uid > 0)
		$str = "cy_user_id=${cy_uid}&".$str;

	return base64_encode(hash_hmac("sha1", $str, $cy_secret, true));
}

// 获取用户头像
function get_avatar_src($user_mail) {
    $img = get_avatar($user_mail, '48');
    if(preg_match_all('/src=\'(.*)\'/iU', $img, $matches)) {
       return $matches[1][0];
    }
	return '';
}

