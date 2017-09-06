<?php
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */


define('WP_CACHE', false); //启动Hyper Cache缓存，提高wp性能

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
//define( 'WPCACHEHOME', '/home/wwwroot/default/wordpress/www.preschool.net.cn/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'preschool');

/** MySQL数据库用户名 */
define('DB_USER', 'root');

/** MySQL数据库密码 */
define('DB_PASSWORD', 'root');

/** MySQL主机 */
define('DB_HOST', 'localhost');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8mb4');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/
 * WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'w{ oK.$$Z{9A}F4QCqr$P4~W~@4B>(y1K(K<xGPWm.q>{*%jd3g?R/3#oZtu6?2.');
define('SECURE_AUTH_KEY',  'eM47HmFV_6./DJe_%eO|5u}2-:o9|!cd#f}<%1|PcU3Mjrj)hRD(MK2rOhB=sGX7');
define('LOGGED_IN_KEY',    'iey[(:t[m[=gu1[R1PjxVSB9PzmHeH*/zQ{b7kG6zNF[6mK9pk/;dVbF~L5-3zC,');
define('NONCE_KEY',        '&egH-Sj%B[Mh&O5>s/aG|<6~u^JxIc{32[?wur@K2/.YuDnx3{9k0-isAuX1-vz}');
define('AUTH_SALT',        'd7#H79>&qPn`be^n^LgI{U-TU)!=t(JSih<Rv!I,?$0W%:-rUO^+~??M>u&8>WS&');
define('SECURE_AUTH_SALT', '_U%tqyil108v(W~v4ZK|`JRJP!EntZaysm{myNdQ-]o1`JKen@[,7t:^ucWK[6:q');
define('LOGGED_IN_SALT',   '*XA1xOq6cFd:r=D 55Z&Aqbx~c95H`+6nK26!~b^N0J2[j!;zr*@;YN80BxYuAx@');
define('NONCE_SALT',       ') M|:Mxd#)2$o)qv%H0`naVX7?][|@?NDX-Q,a-X|roVPs)LJv~jS>Sl;Zti%^vo');

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'wp_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问Codex。
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

//开启sql调试
define('SAVEQUERIES', true);

/**
 * zh_CN本地化设置：启用ICP备案号显示
 *
 * 可在设置→常规中修改。
 * 如需禁用，请移除或注释掉本行。
 */
define('WP_ZH_CN_ICP_NUM', true);
define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', $_SERVER['SERVER_NAME']);
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
//define('ENABLE_CACHE', true);
/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置WordPress变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
