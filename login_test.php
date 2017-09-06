<?php

function wp_generate_password( $length = 12, $special_chars = true, $extra_special_chars = false ) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    if ( $special_chars )
        $chars .= '!@#$%^&*()';
    if ( $extra_special_chars )
        $chars .= '-_ []{}<>~`+=,.;:/?|';

    $password = '';
    for ( $i = 0; $i < $length; $i++ ) {
        $password .= substr($chars, wp_rand(0, strlen($chars) - 1), 1);
    }

    /**
     * Filters the randomly-generated password.
     *
     * @since 3.0.0
     *
     * @param string $password The generated password.
     */
    return $password;
}
if($_POST)
{
    $insert['user_login'] = $_POST['username'];
    $insert['user_email'] = $_POST['email'];
    $insert['user_pass']  = $_POST['pwd'];
    $password = wp_generate_password();
    //$user_id  = wp_create_user( $insert['user_login'], $insert['user_pass'], $insert['user_email'] );
    echo $password;

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>会员注册-中国学前教育网</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.bootcss.com/foundation/5.5.3/css/foundation.min.css">
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/foundation/5.5.3/js/foundation.min.js"></script>
    <script src="https://cdn.bootcss.com/foundation/5.5.3/js/vendor/modernizr.js"></script>
    <style type="text/css">
        .text-right
        {
            text-align: right;
        }
        .input-width
        {
            width:90%;
        }
        .margin
        {
            margin-top:20px;
        }
        .color
        {
            background: #924898;
            color:#FFF;
        }

    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="small-12 columns text-center">
            <a href="http://www.preschool.net.cn">
                <img width="180" src="/wxscan/images/logo.png" alt="中国学前教育网">
            </a>
        </div>
    </div>

    <form method="post" action="">
        <div class="row margin">
            <div class="small-4 columns">
                <label class="inline text-right" for="username">用户名：</label>
            </div>
            <div class="small-7 columns">
                <input class="small-8 columns" required="required" name="username" type="text" placeholder="用户名" id="username">
            </div>
            <div class="small-1 columns">

            </div>
        </div>
        <div class="row">
            <div class="small-4 columns">
                <label class="inline text-right" for="email">邮箱：</label>
            </div>
            <div class="small-7 columns">
                <input name="email" type="text" required="required" placeholder="电子邮箱" id="email">
            </div>
            <div class="small-1 columns">

            </div>
        </div>
        <div class="row">
            <div class="small-4 columns">
                <label class="inline text-right" for="password">密码：</label>
            </div>
            <div class="small-7 columns">
                <input name="pwd" type="password" required="required" placeholder="密码" id="password">
            </div>
            <div class="small-1 columns">

            </div>
        </div>
        <div class="row text-center margin">
            <input type="submit" class="button color" value="提交注册">
        </div>

    </form>

</div>
<script>
    $(document).ready(function() {
        $(document).foundation();
    })
</script>
</body>
</html>
