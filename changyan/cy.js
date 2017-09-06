// 若干配置
var changyan_bind_box_params = {
	title:'欢迎您登录中国学前教育网！',
	desc:'为了今后方便使用，请绑定一个中国学前教育网网站账号：',
	login_type:'addr',
	login_action:'http://www1.preschool.net.cn/wp-login.php?action=login',
	register_type:'addr',
	register_action:'http://www1.preschool.net.cn/wp-login.php?action=register'
};

// 加载jquery
if (typeof $ == 'undefined'){
	var s = document.createElement('script');
	s.setAttribute("src", "http://js.sohu.com/library/jquery-1.7.1.min.js");
	s.setAttribute("type", "text/javascript");
	document.body.appendChild(s);
}

// 设置cookie的函数
function cy_set_cookie(c_name, value, expiredays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie = c_name + "=" + escape(value) + ";path=/" +
		((expiredays == null) ? "" : ";expires=" + exdate.toGMTString())
}
// 读取cookie的函数
function cy_get_cookie(c_name) {
	if (document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=");
		if (c_start != -1) {
			c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";", c_start);
		if (c_end == -1)
			c_end = document.cookie.length;
			return unescape(document.cookie.substring(c_start, c_end));
		}
	}
	return ""
}
// 框体本身
function cy_get_box() {
	return $('<style type="text/css">\
    div.reset-g{margin:0;padding:0;border:0;font-size:100%;text-align:left;}\
    div.reset-g div,\
    div.reset-g p,\
    div.reset-g span,\
    div.reset-g strong,\
    div.reset-g a{\
        margin:0;\
        padding:0;\
        border:0;\
        font-size:100%;\
        text-align:left;\
        vertical-align:baseline;\
        background:none;\
        width:auto;\
        float:none;\
        overflow:visible;\
        text-indent:0;\
    }\
    .windows-define-dg .clear-g{zoom:1;}\
    .windows-define-dg .clear-g:after{content:".";display:block;visibility:hidden;height:0;clear:both;}\
    .windows-define-dg a{color:#44708e;text-decoration:none;}\
    .windows-define-dg a:hover{color:#44708e;text-decoration:underline;}\
    div.windows-define-dg{border:0;font-family:"\5B8B\4F53";font-size:12px;font-weight:normal;margin:0;overflow:visible;padding:0;text-align:left;}\
    div.user-bind-wrapper-dw{width:400px;height:308px;border-radius:3px;}\
    .user-bind-wrapper-dw .cont-title-dw{height:30px;line-height:18px;padding:14px 0 0;border-radius:3px 3px 0 0;}\
    .user-bind-wrapper-dw .cont-title-dw span.title-close-dw{float:right;width:18px;height:18px;margin:0 12px 0 0;overflow:hidden;}\
    .user-bind-wrapper-dw .cont-title-dw span.title-close-dw a{width:100%;height:100%;display:block;}\
    .user-bind-wrapper-dw .cont-title-dw strong.title-name-dw{display:inline-block;font-size:14px;font-weight:bold;padding:0 0 0 20px;}\
    .user-bind-wrapper-dw .cont-bind-dw{padding:20px 20px 0;}\
    .user-bind-wrapper-dw .cont-bind-dw p.bind-word-dw{line-height:18px;font-size:14px;padding:5px 0 10px;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw{padding:0 0 0 99px;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span a{display:block;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-btn-dw{padding:5px 0 0;display:block;width:160px;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-btn-dw a{width:160px;height:50px;line-height:50px;padding:5px 0 11px;font-size:14px;text-decoration:none;text-align:center;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-not-dw a{width:158px;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-after-dw{width:160px;display:block;line-height:16px;padding:18px 0 0;text-align:right;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-after-dw a{text-align:right;text-decoration:underline;}\
    div.user-bind-wrapper-dw{border:1px solid #ccd4d9;background-color:#fff;}\
    .user-bind-wrapper-dw .cont-title-bd{background-color:#fafafa;border-bottom:1px solid #ccd4d9;}\
    .user-bind-wrapper-dw .cont-title-dw span.title-close-dw a{\
        background-image:url("./changyan/b17.png");\
        cursor:pointer;\
        _background-image:none;\
        _filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src="b17.png",sizingMethod="crop");\
    }\
    .user-bind-wrapper-dw .cont-title-dw span.title-close-dw a:hover{\
        background-image:url("./changyan/b18.png");\
        _background-image:none;\
        _filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src="b18.png",sizingMethod="crop");\
    }\
    .user-bind-wrapper-dw .cont-bind-dw p.bind-word-bd{color:#333;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-being-dw a,\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-being-dw a:hover{color:#fff;background-color:#5788aa;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-being-dw a:hover{background-color:#5c98c1;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-not-dw a{border:1px solid #e5e9eb;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-not-dw a,\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-not-dw a:hover{color:#333;background-color:#fafafa;}\
    .user-bind-wrapper-dw .cont-bind-dw .bind-btn-wrap-dw span.wrap-not-dw a:hover{background-color:#fff;color:#333;}\
</style>\
<div class="reset-g windows-define-dg user-bind-wrapper-dw">\
    <div class="clear-g cont-title-dw cont-title-bd"><span class="title-close-dw"><a id="cy_bind_close_btn" href="#"></a></span><strong class="title-name-dw title-name-bd">' + changyan_bind_box_params.title + '</strong></div>\
    <div class="cont-bind-dw">\
        <p class="bind-word-dw bind-word-bd"><span>' + changyan_bind_box_params.desc + '</span></p>\
        <div class="bind-btn-wrap-dw">\
            <span class="wrap-btn-dw wrap-being-dw"><a id="cy_bind_login_btn" href="#" target="_blank">有账号，立即登录</a></span>\
            <span class="wrap-btn-dw wrap-being-dw"><a id="cy_bind_register_btn" href="#" target="_blank">没账号，立即注册</a></span>\
            <span class="wrap-after-dw"><a id="cy_bind_close_link" href="#" target="_blank">以后再说</a></span>\
        </div>\
    </div>\
</div>');
}


var cy_login_box = function() {
	// 如果用户曾经取消绑定了，则直接return，不产生任何提示信息
	if(cy_get_cookie("cy_isv_bind_cancel") == "1") {
		return;
	}
	// 画框
	// 背景mask
	var mask = $("<div id='changyan-bind-mask' style='width:100%;position:absolute;top:0;left:0;background-color:#000;filter:alpha(opacity=30);-moz-opacity:0.3;opacity:0.3;z-index:999';></div>");
	mask.css("height", $(document).height());
	mask.appendTo(document.body);
	var dialog = cy_get_box();
	dialog.css("z-index", 1000);
	dialog.appendTo(document.body);

	// 框体定位
	var kh = parseInt(window.document.documentElement.clientHeight);
	var kw = parseInt(window.document.documentElement.clientWidth);
	var aht = (kh - 300) / 2;
	var awt = (kw - 400) / 2;
	dialog.css("position", "absolute");
	dialog.css("top", aht + parseInt(document.documentElement.scrollTop) - 20);
	dialog.css("left", awt);

	$('#cy_bind_close_btn, #cy_bind_close_link').bind("click", function() {
		// 关闭按钮行为。写入用户已经放弃绑定的cookie
		$("#changyan-bind-mask").remove();
		dialog.remove();
		cy_set_cookie("cy_isv_bind_cancel", "1", 7);
		return false;
	});
	$('#cy_bind_login_btn').bind("click", function() {

        $("#changyan-bind-mask").remove();
        dialog.remove();
        cy_set_cookie("cy_isv_bind_cancel", "1", 7);


		// 登陆按钮的行为。按照配置进行操作
		if(changyan_bind_box_params.login_type == "js") {
			$("#changyan-bind-mask").remove();
			dialog.remove();
			eval(changyan_bind_box_params.login_action);
			return false;
		} else {
			this.href = changyan_bind_box_params.login_action;
			return true;
		}
	});
	$('#cy_bind_register_btn').bind("click", function() {

        $("#changyan-bind-mask").remove();
        dialog.remove();
        cy_set_cookie("cy_isv_bind_cancel", "1", 7);

		// 注册按钮行为。按照配置进行操作
		if(changyan_bind_box_params.register_type == "js") {
			$("#changyan-bind-mask").remove();
			dialog.remove();
			eval(changyan_bind_box_params.register_action);
			return false;
		} else {
			this.href = changyan_bind_box_params.register_action;
			return true;
		}
	});
}

// 执行！
setTimeout('cy_login_box()', 200);
