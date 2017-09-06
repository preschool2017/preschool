<?php
require_once('./cy-global.php');

$callback = $_GET['callback'];
unset($_COOKIE['cy_uid']);
wp_logout();
echo jsonp($callback, json_encode(array('reload_page'=>1)));
?>
