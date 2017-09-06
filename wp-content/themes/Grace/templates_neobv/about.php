<?php 
/*
Template Name: 关于我们
*/
get_header();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>关于我们</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="/wp-content/themes/Grace/g-procurement/css/swiper.min.css">
    <!-- <link rel="stylesheet" href="/wp-content/themes/Grace/g-procurement/css/bootstrap.min.css"> -->

    <!-- Demo styles -->
    <style>
    html, body {
        position: relative;
        height: 100%;
    }
    body {
        background: #eee;
        font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
        font-size: 14px;
        color:#000;
        margin: 0;
        padding: 0;
    }
    .swiper-container {
        width: 100%;
        height: 100%;
        margin-left: auto;
        margin-right: auto;
    }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;

        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }
    </style>
</head>
<body>
    <!-- Swiper -->
    <div class="swiper-container visible-md visible-lg">
        <div class="swiper-wrapper">
            <div class="swiper-slide" style="background: url(/wp-content/themes/Grace/g-procurement/images/big_1.jpg) no-repeat;background-size:100% 100%;"></div>
            <div class="swiper-slide" style="background: #f9f8fd;"><img src="/wp-content/themes/Grace/g-procurement/images/big_2.jpg"  width="100%" height="auto" style="margin-top: 89px;" alt=""></div>
            <div class="swiper-slide" style="background: #dae4df;"><img src="/wp-content/themes/Grace/g-procurement/images/big_3.jpg"  width="100%" height="auto" style="margin-top: 89px;" alt=""></div>
            <div class="swiper-slide" style="background: #f3f3f3;"><img src="/wp-content/themes/Grace/g-procurement/images/big_4.png"  width="100%" height="auto" style="margin-top: 89px;" alt=""></div>
            <div class="swiper-slide" style="background: #e9e9e9;"><img src="/wp-content/themes/Grace/g-procurement/images/big_5.jpg"  width="100%" height="auto" style="margin-top: 89px;" alt=""></div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
    

    <!-- Swiper JS -->
    <script src="/wp-content/themes/Grace/g-procurement/js/core/swiper.min.js"></script>
    <script type="text/javascript" src="/wp-content/themes/Grace/g-procurement/js/core/bootstrap.min.js"></script>
    <script type="text/javascript" src="/wp-content/themes/Grace/g-procurement/js/core/jquery.min.js"></script>
    <!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        direction: 'vertical',
        slidesPerView: 1,
        paginationClickable: true,
        spaceBetween: 30,
        mousewheelControl: true
    });
    </script>
    <div class="visible-xs visible-sm">
            <div><img src="/wp-content/themes/Grace/g-procurement/images/bigTwo.png" width="100%" height="auto"></div>
    </div>
</body>
</html>
<?php get_footer(); ?>