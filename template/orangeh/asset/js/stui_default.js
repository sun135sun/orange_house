/*!
 * Stui v3.0 Copyright 2016-2018 http://v.shoutu.cn
 * Email 726662013@qq.com,admin@shoutu.cn
 */
var stui = {
	'browser': {//浏览器
		url: document.URL,
		domain: document.domain,
		title: document.title,
		language: (navigator.browserLanguage || navigator.language).toLowerCase(),
		canvas: function() {
			return !!document.createElement("canvas").getContext
		}(),
		useragent: function() {
			var a = navigator.userAgent;
			return {
				mobile: !! a.match(/AppleWebKit.*Mobile.*/),
				ios: !! a.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
				android: -1 < a.indexOf("Android") || -1 < a.indexOf("Linux"),
				iPhone: -1 < a.indexOf("iPhone") || -1 < a.indexOf("Mac"),
				iPad: -1 < a.indexOf("iPad"),
				trident: -1 < a.indexOf("Trident"),
				presto: -1 < a.indexOf("Presto"),
				webKit: -1 < a.indexOf("AppleWebKit"),
				gecko: -1 < a.indexOf("Gecko") && -1 == a.indexOf("KHTML"),
				weixin: -1 < a.indexOf("MicroMessenger")
			}
		}()
	},
	'images': {//图片处理
		'lazyload': function() {
			$(".lazyload").lazyload({
				effect: "fadeIn",
				threshold: 200,
				failurelimit: 15,
				skip_invisible: false
			})
		},
		'qrcode': function() {
			$("img.qrcode").attr("src", "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" + encodeURIComponent(stui.browser.url) + "")
		}
	},
	'common': {//公共基础
		'tab': function() {
			$(".tab li").on('click',function(){
			    $(".tab li.active").removeClass('active')
			    $(this).addClass('active')
			    var index = $(this).index()
				$(".tab-content .item").eq(index).addClass('active').siblings().removeClass('active');
	  		})
		  	$(".down-tab li").on('click',function(){
			    $(".down-tab li.active").removeClass('active')
			    $(this).addClass('active')
			    var index = $(this).index()
			    $(this).parent().parent().find("h3").html($(".down-tab li.active").html())
				$(".down-content .down-item").eq(index).addClass('active').siblings().removeClass('active');
				$(".down-tab").hide()			
	  		})
		  	$(".play-tab li").on('click',function(){
			    $(".play-tab li.active").removeClass('active')
			    $(this).addClass('active')
			    var index = $(this).index()
			    $(this).parent().parent().find("h3").html($(".play-tab li.active").html())
				$(".play-content .play-item").eq(index).addClass('active').siblings().removeClass('active');
				$(".play-tab").hide()			
	  		})
		  	$(".play-switch").on('click',function(){
		  		$(".play-tab").toggle()
		  	})
		  	$(".down-switch").on('click',function(){
		  		$(".down-tab").toggle()
		  	})
		},	
		'history': function() {
			if($.cookie("recente")){
			    var json=eval("("+$.cookie("recente")+")");
			    var list="";
			    for(i=0;i<json.length;i++){
			        list = list + "<li class='top-line'><a href='"+json[i].vod_url+"' title='"+json[i].vod_name+"'><span class='pull-right text-red'>"+json[i].vod_part+"</span>"+json[i].vod_name+"</a></li>";
			    }
			    $("#stui_history").append(list);
			}
			else
	            $("#stui_history").append("<p style='padding: 80px 0; text-align: center'>您还没有看过影片哦</p>");
		   
		    $(".historyclean").on("click",function(){
		    	$.cookie("recente",null,{expires:-1,path: '/'});
		    })		    
		},
		'collapse': function() {
			$("a.detail-more").on("click",function(){
				$(this).parent().find(".detail-sketch").addClass("hide");
				$(this).parent().find(".detail-content").css("display","");
				$(this).remove();
			})
		},
		'more': function() {
			$(".menu-switch").on('click',function(){
		  		var display = PlaySide.css('display');
		  		if(display == 'block'){
		  			PlaySide.hide(); 
		  			PlayLeft.css("width","100%");
		  			$(this).find("span").html("打开菜单")
				}else{
					PlaySide.show();  
					PlayLeft.css("width","75%");
					$(this).find("span").html("关闭菜单")
				}
			})
		  	
		  	$(".open-desc").on('click',function(){
		  		$(".data-more").slideToggle("slow")
		  	})
		  	
		  	var date = new Date;
			var h = date.getHours();  //时
			var minute = date.getMinutes()  //分
			if(h<10){
				h = "0"+h;
			}
			if(minute<10){
				minute = "0"+minute;
			}
			$(".date").html('<span>'+h+":"+minute+"</span>");
		}
	}	
};
$(document).ready(function() {	
	stui.images.lazyload();
	stui.images.qrcode();
	stui.common.tab();
	stui.common.history();
	stui.common.collapse();
	stui.common.more();
});



jQuery.cookie = function(name, value, options) {
if(typeof value != 'undefined') {
options = options || {};
if(value === null) {
value = '';
options.expires = -1;
}
var expires = '';
if(options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
var date;
if(typeof options.expires == 'number') {
date = new Date();
var totalTime = 24 * 3600;
var hour = date.getHours();
var minutes = date.getMinutes();
var seconds = date.getSeconds();
var pastTime = 3600 * hour + 60 * minutes + seconds;
var leftTime = totalTime - pastTime;
date.setTime(date.getTime() + (options.expires * leftTime * 1000));
} else {
date = options.expires;
}
expires = '; expires=' + date.toUTCString();
}
var path = options.path ? '; path=' + (options.path) : '';
var domain = options.domain ? '; domain=' + (options.domain) : '';
var secure = options.secure ? '; secure' : '';
document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
} else {
var cookieValue = null;
if(document.cookie && document.cookie != '') {
var cookies = document.cookie.split(';');
for(var i = 0; i < cookies.length; i++) {
var cookie = jQuery.trim(cookies[i]);
if(cookie.substring(0, name.length + 1) == (name + '=')) {
cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
break;
}
}
}
return cookieValue;
}
};
$(function() {
var COOKIE_NAME = "erdangjiade";
if($.cookie(COOKIE_NAME)) {
$(".layer").hide();
$("#globalAd").hide();
} else {
var erdangjiadeH = $(window).height();
var erdangjiadeW = $(window).width();
$(".layer").show();
$("#globalAd").css({
'top': erdangjiadeH / 2 - $("#globalAd").height() / 2,
'left': erdangjiadeW / 2 - $("#globalAd").width() / 2
});
$("#globalAd").slideDown(300, function() {
setTimeout("closeGloableAd()", '300000');
});
$.cookie(COOKIE_NAME, "erdangjiade", {
path: '/',
expires: 1
});
}
});


function closeGlobalAd() {
$('#globalAd').hide();
$('.layer').hide();


}


function redirectUrlToActive() {
$('#globalAd').hide();
$('.layer').hide();
}
