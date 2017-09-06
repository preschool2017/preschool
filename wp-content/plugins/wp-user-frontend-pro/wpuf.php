<?php
/*
Plugin Name: WP User Frontend Pro用户前端专业版
Plugin URI: http://www.thefox.cn/wp-user-frontend-pro.shtml
Description: 在前端创建, 编辑, 删除, 管理你的文章, 页面. 前端注册表单和个人资料编辑...[唯一更新授权平台www.thefox.cn]
Author: 黛云轩
Version: 3.7.2
Author URI: http://www.thefox.cn/
*/

define( 'WPUF_VERSION', '3.7.2' );
define( 'WPUF_FILE', __FILE__ );
define( 'WPUF_ROOT', dirname( __FILE__ ) );
define( 'WPUF_ROOT_URI', plugins_url( '', __FILE__ ) );
define( 'WPUF_ASSET_URI', WPUF_ROOT_URI . '/assets' );
require_once( plugin_dir_path( __FILE__ ) . 'wpuf-class.php');
?>
