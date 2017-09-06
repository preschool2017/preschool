<?php 
/*
Template Name: 个人中心(模板 - 个人资料)
*/
get_header();
?>

<head>
    <title>讲师评价</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/grzx/css/components.css">
</head>
<style>
   /*.tx-img img{
          width:120px;
          margin-top: 4%; 
          height:120px;
          border-radius:100%;
   }*/
    @media(max-width: 2000px) { 
            .tx-img img{
          width:120px;
          margin-top: 2%; 
          height:120px;
          border-radius:100%;
        }
        .ll{
          margin-top: 20px;
        }
    }
    @media(max-width: 1550px) { 
            .tx-img img{
          width:120px;
          margin-top: 2.6%; 
          height:120px;
          border-radius:100%;
        }
        .ll{
          margin-top: 20px;
        }
    } 
    /* 设置了浏览器宽度不大于1200px时 abc 显示900px宽度 */ 
     
    @media(max-width: 801px) { 
           .tx-img img{
          width:120px;
          margin-top: 9.5%; 
          height:120px;
          border-radius:100%;
          
        }
        .ll{
          margin-top: 0px;
        }
    } 
    /* 设置了浏览器宽度不大于901px时 abc 显示200px宽度 */ 
     
    @media(max-width: 400px) { 
       .tx-img img{
          width:120px;
          margin-top: 9.5%; 
          height:120px;
          border-radius:100%;

        }
        .ll{
          margin-top: 0px;
        }
    } 
    .sss a:hover{border:0px;}
    .navigation>li.active>a, .navigation>li.active>a:focus, .navigation>li.active>a:hover{
        background: #a054a4;
    }
    .navigation li a:focus, .navigation li a:hover{background-color:#fa94f1;}
    .sss_1{border-top: 1px solid #cccccc;}
    .sss>li{border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;border-right:1px solid #cccccc;}
    .navigation li+li{margin-top:0px;}
    /*.btn-default:hover, .btn-default:focus, .btn-default.focus, .btn-default:active, .btn-default.active, .open>.dropdown-toggle.btn-default*/
    .s1>.btn-default{border-radius: 0px;}
    .s1>.btn-default:hover, .s1>.btn-default:focus, .s1>.btn-default.focus, .s1>.btn-default:active, .s1>.btn-default.active, .s1>.open>.dropdown-toggle.s1>.btn-default{
            color: #fff;
    background-color: #a054a4;
    border-color: #a054a4;
    }
</style>
<div class="page-single page-nav"  >
    <div>
        <div class="row" style="text-align: center; line-height: 100%;  height:200px;background: url(/grzx/images/bjimg.png);background-repeat:no-repeat ;background-size:100% 100%;background-attachment: fixed;">
               <!-- 这是个人中心 头像 -->
            <div class="tx-img">
                <!-- <?php  echo get_avatar( $userdata->user_email, 80 );?> -->
                <?php global $current_user; get_currentuserinfo(); echo get_avatar( $current_user->user_email,200); ?>
            </div>
            <div style="line-height: 28px; font-size: 24px; color: #fff;">
              <?php global $current_user;
                    get_currentuserinfo();
                    echo  $current_user->user_login . "\n";
                ?>
            </div>
        </div>
        <div class="container">
            <div class="page-container">
                    <div class="content-wrapper">
                        <div class="ll content panel panel-flat" style="border:0px;">
                            <div class="col-xs-12">
                                    <!-- 电脑端个人中心 -->
       
                                    <div class="page-dec">    
                                        <div class="row visible-md visible-lg" >
                                            <div class="tabbable">
                                                <div class="col-xs-3">
                                                    <div class="sidebar-content">
                                                        <div class="sidebar-category sidebar-category-visible">
                                                            <div class="category-content no-padding">
                                                                <ul class="sss navigation navigation-main navigation-accordion" id="sidebarContent" style="border:0px; font-size: 16px;">
                                                                    <li class="sss_1 active" ng-class="{true:'active'}[sidebarId == 'basic-pill1']" id="g_08_01">
                                                                        <a href="#basic-pill1" class="active" data-toggle="tab">我的资料</a>
                                                                    </li>
                                                                    <li ng-class="{true:'active'}[sidebarId == 'basic-pill2']" id="g_08_02">
                                                                        <a href="#basic-pill2" data-toggle="tab">发布文章</a>
                                                                    </li>
                                                                    <li ng-class="{true:'active'}[sidebarId == 'basic-pill3']" id="g_08_03">
                                                                        <a href="#basic-pill3" data-toggle="tab">文章列表</a>
                                                                    </li>
                                                                    <li ng-class="{true:'active'}[sidebarId == 'basic-pill4']" id="g_08_04">
                                                                        <a href="#basic-pill4" data-toggle="tab">问卷调查</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>         
                                                </div>
                                                <div class="tab-content col-md-9">
                                                    <div class="tab-pane active" id="basic-pill1">
                                                        <iframe width="100%" height="900px" src='http://wp.yoursclass.com/the-personal-data/'></iframe>        
                                                    </div>
                                                    <div class="tab-pane" id="basic-pill2">
                                                         <iframe width="100%" height="700px" src='http://wp.yoursclass.com/published-articles/'></iframe>
                                                    </div>
                                                    <div class="tab-pane" id="basic-pill3">
                                                        <iframe width="100%" height="880px" src='http://wp.yoursclass.com/仪表盘/'></iframe>
                                                    </div>
                                                    <div class="tab-pane" id="basic-pill4">
                                                        <iframe width="100%" height="900px" src='http://wp.yoursclass.com/investigate/'></iframe>
                                                    </div>
                                                </div>
                                            </div>                   
                                        </div>
                                    <!-- 电脑端个人中心结束 -->
                                    <!-- 手机端个人中心开始 -->
                                    <div class="row visible-xs visible-sm tabbable" style="padding: 0px;">
                                        <div class="col-xs-12 s1" style="padding: 0px;margin-bottom: 15px;"> 
                                            <a href="#basic-pill_1" data-toggle="tab" class="btn btn-default" >我的资料</a>
                                            <a href="#basic-pill_2" data-toggle="tab" class="btn btn-default" >发布文章</a>
                                            <a href="#basic-pill_3" data-toggle="tab" class="btn btn-default" >文章列表</a>
                                            <a href="#basic-pill_4" data-toggle="tab" class="btn btn-default" >问卷调查</a>
                                        </div>
                                        <div class="tab-content col-xs-12" style="padding:0px;">
                                            <div class="tab-pane active" id="basic-pill_1">
                                                <iframe width="100%" height="1190px" src='http://wp.yoursclass.com/the-personal-data/'></iframe>  
                                            </div>
                                            <div class="tab-pane" id="basic-pill_2">
                                                <iframe width="100%" height="700px" src='http://wp.yoursclass.com/published-articles/'></iframe>
                                            </div>
                                            <div class="tab-pane" id="basic-pill_3">
                                                <iframe width="100%" height="880px" src='http://wp.yoursclass.com/仪表盘/'></iframe>
                                            </div>
                                            <div class="tab-pane" id="basic-pill_4">
                                                <iframe width="100%" height="880px" src='http://wp.yoursclass.com/investigate/'></iframe>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <!-- 手机端个人中心结束 -->
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/grzx/js/core/jquery.min.js"></script>
<script type="text/javascript" src="/grzx/js/core/bootstrap.min.js"></script>
<?php get_footer(); ?>
