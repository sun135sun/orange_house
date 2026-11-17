<?php if (!defined('THINK_PATH')) exit(); /*a:13:{s:38:"template/18av999/html/index/index.html";i:1754572206;s:63:"/www/wwwroot/18av.web/template/18av999/html/public/include.html";i:1755004786;s:62:"/www/wwwroot/18av.web/template/18av999/html/public/header.html";i:1755065590;s:68:"/www/wwwroot/18av.web/template/18av999/html/module/wn_block_app.html";i:1754529230;s:84:"/www/wwwroot/18av.web/template/18av999/html/module/wn_block_webmaster_recommend.html";i:1754529271;s:71:"/www/wwwroot/18av.web/template/18av999/html/module/wn_block_banner.html";i:1754529237;s:73:"/www/wwwroot/18av.web/template/18av999/html/module/wn_block_top_link.html";i:1754935475;s:73:"/www/wwwroot/18av.web/template/18av999/html/module/wn_block_floating.html";i:1754529256;s:63:"/www/wwwroot/18av.web/template/18av999/html/module/vodlist.html";i:1754567104;s:62:"/www/wwwroot/18av.web/template/18av999/html/public/footer.html";i:1754994163;s:76:"/www/wwwroot/18av.web/template/18av999/html/module/wn_block_bottom_link.html";i:1754935500;s:64:"/www/wwwroot/18av.web/template/18av999/html/module/link_php.html";i:1754988730;s:63:"/www/wwwroot/18av.web/template/18av999/html/module/link_js.html";i:1755003371;}*/ ?>
<!DOCTYPE html>
<html>

    <head>
        <?php 
$wn_debug = false;
$version = $wn_debug ? time() : parse_ini_file(ltrim(MAC_PATH_TEMPLATE .'info.ini', '/'))['version'];
$wn_link_upgrade = true; $wn_floating = true; $wn_ga_status = false;
require MAC_ROOT_TEMPLATE . 'php/function.php';
 ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" />
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta name="referrer" content="always">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="shortcut icon" href="/favicon.ico" type="images/x-icon">
<link rel="stylesheet" href="<?php echo $maccms['path_tpl']; ?>asset/font/iconfont.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $maccms['path_tpl']; ?>asset/css/stui_default.css?v=" type="text/css" />
<script type="text/javascript" src="<?php echo $maccms['path_tpl']; ?>asset/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $maccms['path_tpl']; ?>asset/js/stui_default.js "></script>
<script type="text/javascript" src="<?php echo $maccms['path_tpl']; ?>asset/js/jquery.cookie.min.js "></script>
<script type="text/javascript" src="<?php echo $maccms['path_tpl']; ?>asset/js/jquery.lazyload.min.js "></script>
<script type="text/javascript" src="<?php echo $maccms['path_tpl']; ?>asset/js/home.js"></script>
<script>var maccms = {"path": "", "path_tpl": "<?php echo $maccms['path_tpl']; ?>", "mid": "<?php echo $maccms['mid']; ?>", "aid": "<?php echo $maccms['aid']; ?>", "url": "<?php echo $maccms['site_url']; ?>", "wapurl": "<?php echo $maccms['site_wapurl']; ?>", "mob_status": "<?php echo $maccms['mob_status']; ?>"};</script>
<?php echo html_entity_decode($maccms['site_tj']); ?>


<style>
    .txtguanggao {
        width: 100%;
        overflow: hidden;
        display: block;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .05);
    }

    .txtguanggao a {
        width: 9.8%;
        float: left;
        border-radius: 3px;
        line-height: 30px;
        height: 30px;
        text-align: center;
        font-size: 16px;
        color: #000;
        display: inline-block;
        background-color: #fdf751;
        margin: 1px;
        transition-duration: .3s;
    }

    .txtguanggao a:hover {
        background: #ff0000;
        color: #FFF
    }

    @media screen and (max-width: 1000px) {
        .txtguanggao a {
            width: 24.4%;
            float: left;
            border-radius: 3px;
            line-height: 30px;
            height: 30px;
            text-align: center;
            font-size: 13px;
            color: #000;
            display: inline-block;
            background-color: #fdf751;
            margin: 1px;
            transition-duration: .3s;
        }
    }
</style>


<style>
    .grid-container {
        display: grid;
        grid-template-columns: auto auto auto auto auto auto auto auto;
        padding: 2px;
    }

    .grid-item {
        background-color: #333;
        padding: 6px;
        font-size: 16px;
        text-align: center;
        margin: 2px;
        border-radius: 15px;

    }

    .grid-item a {
        text-decoration: none;
        color: #f7d0bf;
    }

    @media only screen and (max-width: 1400px) {
        .grid-item {
            font-size: 16px;
        }

        .grid-container {
            grid-template-columns: auto auto auto auto auto auto;
        }

    }


    @media only screen and (max-width: 500px) {
        .grid-item {
            font-size: 14px;
        }

        .grid-container {
            grid-template-columns: auto auto auto auto;
        }

    }
</style>

<style>
    .grid-container2 {
        display: grid;
        grid-template-columns: auto auto auto auto auto auto auto auto auto auto auto auto auto auto auto auto;
        padding: 2px;
    }

    .grid-item2 {
        background-color: #333;
        font-size: 14px;
        text-align: center;
        margin: 1px;
        border-radius: 10px;

    }

    .grid-item2 a {
        text-decoration: none;
        color: #f7d0bf;
        display:inline-block;
        width:100%;
        height:100%;
        padding:5px;
    }

    @media only screen and (max-width: 1400px) {
        .grid-item2 {
            font-size: 16px;
        }

        .grid-container2 {
            grid-template-columns: auto auto auto auto auto auto auto auto auto auto auto auto;
        }

    }


    @media only screen and (max-width: 500px) {
        .grid-item2 {
            font-size: 13px;
        }

        .grid-container2 {
            grid-template-columns: auto auto auto auto auto auto auto auto;
        }

    }
</style>

<link rel="stylesheet" href="/static/css/custom_ads_blocks.css?v=" type="text/css">
<style>
    :root {
        --wn-block-container-width: 1280px;
        --wn-banner-container-max-width: 100%;

        --wn-block-title-color: white;
        --wn-block-title-text-align: left;
        --wn-block-app-margin: 30px auto 10px auto;
        --wn-block-link-item-padding: 4px;
        --wn-block-app-text-color:white;

    }

    .wn_block_title {
        padding: 0 5px;
    }
</style>

<style>
    

    @media (max-width: 768px) {
        .stui-header__menu {
            display: flex;
            flex-wrap: wrap;
            /* 允许换行 */
            justify-content: space-between;
            /* 让四个菜单均分整行 */
            list-style: none;
            padding: 0;
            margin: 10px auto;
            max-width: 100%;
        }

        .stui-header__menu li {
            width: 33.333%;
            /* 每个菜单项固定 25% 宽度，确保一行 4 个 */
            text-align: center;
            margin: 1px 0;
            white-space: nowrap;
            /* 文字不换行 */
        }

        .stui-header__menu a {
            display: block;
            text-decoration: none;
            color: #ffffff !important;
            /* 只在手机端强制白色 */
            font-size: 16px;
            /* 适当调整字号 */
            padding: 8px 5px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .stui-header__menu li.active a {
            background: #ff6600;
            /* 高亮当前选中 */
            color: #fff;
        }
    }
</style>

<style>
    .pic-text-top{
        position: absolute;
        top: 1px;
        padding: 1px 5px;
        font-size: 12px;
        overflow: hidden;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        white-space: nowrap;
        color: #FFFFFF;
        right: 1px;
        background: #0000006e;
        border-radius: 3px;
    }
    
    @media (max-width: 1024px) {
        .stui-screen .item ul {
            white-space: wrap;
        }
        
        .stui-screen .item ul li a {
            padding-left: 0;
            margin-right: 5px;
        }
    }
</style>


<!-- custom css -->
<style>
    body:after{ 
        background: -webkit-linear-gradient(top, #000000, #ef919e);
    }
</style>

        <title><?php echo unicode_encode($maccms['site_name']); ?></title>
        <meta name="keywords" content="<?php echo unicode_encode($maccms['site_keywords']); ?>" />
        <meta name="description" content="<?php echo unicode_encode($maccms['site_description']); ?>" />
    </head>

    <body>

        <div class="container">
            <div class="row">
                <div class="stui-pannel">
                    <div class="stui-pannel__hd">
                        
<header class="stui-header clearfix">
    <div class="container">
        <div class="row">
            <ul class="stui_header__user">
                <li>
                    <a href="javascript:;"><i class="">搜索</i></a>
                    <div class="dropdown search">
                        <div class="item">
                            <form id="search" name="search" method="GET" action="<?php echo mac_url('vod/search'); ?>" onSubmit="return qrsearch();">
                                <input type="text" id="wd" name="wd" class="form-control" value="<?php echo $param['wd']; ?>" placeholder="请输入关键词..." />
                                <button class="submit" id="searchbutton" type="submit"><i class="icon iconfont icon-search"></i></button>
                            </form>
                        </div>
                        <!-- 搜索框热搜词 -->
                    </div>
                </li>

                <li>
                    <a href="javascript:;"><i class="iconfont icon-clock"></i></a>
                    <div class="dropdown history">
                        <div class="head">
                            <a class="historyclean pull-right" href="">清空</a>
                            <h5>播放记录</h5>
                        </div>
                        <ul class="clearfix" id="stui_history">
                        </ul>
                    </div>
                </li>

            </ul>
            <div class="stui-header__logo">
                <a class="logo" href="<?php echo mac_url('/'); ?>" style="background-image:url('<?php echo mac_url_img($maccms['site_logo']); ?>')"></a>
            </div>
            <ul class="stui-header__menu">
                <li><a href="<?php echo mac_url('/'); ?>">首页</a></li>
                <?php $__TAG__ = '{"num":"5","ids":"200,300,400","by":"sort","order":"asc","id":"vo","key":"key"}';$__LIST__ = model("Type")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?>
                <li><a href="<?php echo mac_url_type($vo); ?>"><?php echo unicode_encode($vo['type_name']); ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                <li><a href="<?php echo mac_url('topic/index'); ?>">TOP300</a></li>
            </ul>
        </div>
    </div>
</header>

<script type="text/javascript">
    $(".stui_header__user li a").on("click", function (e) {
        e.stopPropagation(); let dropdown = $(this).parent().find(".dropdown"); $(".dropdown").not(dropdown).hide(); dropdown.toggle();
    });
    $("body").on("click", function (e) {
        let dropdown = $(".dropdown"); dropdown.is(":visible") && !dropdown.is(e.target) && dropdown.has(e.target).length === 0 ? dropdown.hide() : "";
    });
    function qrsearch() {if (window.document.search.wd.value == "") {alert("请输入关键词！"); return false;} else {return true;} }
</script>


<div>
    <?php if(($wn_link_upgrade)): ?>
<div class="wn_block_app">
    <h4 class="wn_block_title"><?php echo lang('APP推荐'); ?></h4>
    <div class="wn_block_app_wrapper">
        <?php $__TAG__ = '{"type":"pic","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(( $vo['link_sort'] >= 3001 && $vo['link_sort'] <= 4000)): ?>
                <a class="wn_block_app_item" href="<?php echo unicode_encode($vo['link_url']); ?>" target="_blank" rel="<?php echo $vo['link_rel']; ?>">
                    <img class="wn_block_app_item_image" src="<?php echo mac_url_img($vo['link_logo']); ?>">
                    <div class="wn_block_app_item_name" style="color:<?php echo $vo['link_name_color']; ?>"><?php echo unicode_encode($vo['link_name']); ?></div>
                </a>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<?php endif; if(($wn_link_upgrade)): ?>
<div class="wn_block_link wn_block_webmaster_recommend">
    <h4 class="wn_block_title"><?php echo lang('站长推荐'); ?></h4>
    <div class="wn_block_link_wrapper">
        <?php $__TAG__ = '{"type":"font","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(( $vo['link_sort'] >= 0 && $vo['link_sort'] <= 1000)): ?>
                <a class="wn_block_link_item" target="_blank" rel="<?php echo $vo['link_rel']; ?>" href="<?php echo unicode_encode($vo['link_url']); ?>" style="background-color:<?php echo $vo['link_background_color']; ?>">
                    <span class="wn_block_link_item_name" style="background-color:<?php echo $vo['link_background_color']; ?>;color:<?php echo $vo['link_name_color']; ?>;"><?php echo unicode_encode($vo['link_name']); ?></span>
                </a>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<?php endif; if(($wn_link_upgrade)): ?>
<div class="wn_block_banner">
    <div class="wn_block_banner_wrapper">
        <?php $__TAG__ = '{"type":"pic","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(( $vo['link_sort'] >= 4001 && $vo['link_sort'] <= 5000)): ?>
                <div class="wn_block_banner_item" style="height:<?php echo $vo['link_logo_height']; ?>px">
                    <a target="_blank" rel="nofollow" href="<?php echo unicode_encode($vo['link_url']); ?>" style="height:<?php echo $vo['link_logo_height']; ?>px" rel="<?php echo $vo['link_rel']; ?>">
                        <img src="<?php echo mac_url_img($vo['link_logo']); ?>">
                    </a>
                </div>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<?php endif; ?>
    
    <!--热搜开始-->
    <h3 class="title">
        <!--<img src="<?php echo $maccms['path_tpl']; ?>/icon/icon_12.png"/>-->
        <i class="iconfont icon-hot">热搜推荐</i>
        <a class="pull-right"><span style="font-size: 14px">永久网址：<span style="color: #FF9900"><?php echo $maccms['site_url']; ?></span></span> </a>
    </h3>
    
    <div class="grid-container2">
        <?php $_689daf910d0e3=explode(',',$maccms['search_hot']); if(is_array($_689daf910d0e3) || $_689daf910d0e3 instanceof \think\Collection || $_689daf910d0e3 instanceof \think\Paginator): if( count($_689daf910d0e3)==0 ) : echo "" ;else: foreach($_689daf910d0e3 as $key=>$vo): ?>
        <div class="grid-item2" style="background: #;"><a href="<?php echo mac_url('vod/search',['wd'=>$vo]); ?>" title="<?php echo unicode_encode($vo); ?>"><?php echo unicode_encode($vo); ?></a></div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <!--热搜结束-->

    <?php if(($wn_link_upgrade)): ?>
<div class="wn_block_link wn_block_top_link">
    <h4 class="wn_block_title"><?php echo lang('友链推荐'); ?></h4>
    <div class="wn_block_link_wrapper">
        <?php $__TAG__ = '{"type":"font","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(( $vo['link_sort'] >= 0 && $vo['link_sort'] <= 1000)): ?>
                <a class="wn_block_link_item" target="_blank"  rel="<?php echo $vo['link_rel']; ?>" href="<?php echo unicode_encode($vo['link_url']); ?>" style="background-color:<?php echo $vo['link_background_color']; ?>">
                    <span class="wn_block_link_item_name" style="background-color:<?php echo $vo['link_background_color']; ?>;color:<?php echo $vo['link_name_color']; ?>;"><?php echo unicode_encode($vo['link_name']); ?></span>
                </a>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<?php endif; if(($wn_floating)): $__TAG__ = '{"type":"pic","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(($vo['link_sort'] == 9001)): ?>
        <div class="wn_floating wn_floating_top_wrapper">
            <div class="wn_floating_image_wrapper">
                <a href="<?php echo $vo['link_url']; ?>" target="_blank" rel="<?php echo $vo['link_rel']; ?>">
                    <img src="<?php echo mac_url_img($vo['link_logo']); ?>">
                </a>
                <div class="wn_floating_close_button wn_floating_close_button_top"><span>x</span></div>
            </div>
            <div class="wn_floating_close_text wn_floating_close_text_top"><?php echo lang('关闭广告'); ?></div>
        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; $__TAG__ = '{"type":"pic","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(($vo['link_sort'] == 9002)): ?>
        <div class="wn_floating wn_floating_bottom_wrapper">
            <div class="wn_floating_close_text wn_floating_close_text_bottom"><?php echo lang('关闭广告'); ?></div>
            <div class="wn_floating_image_wrapper">
                <a href="<?php echo $vo['link_url']; ?>" target="_blank" rel="<?php echo $vo['link_rel']; ?>">
                    <img src="<?php echo mac_url_img($vo['link_logo']); ?>">
                </a>
                <div class="wn_floating_close_button wn_floating_close_button_bottom"><span>x</span></div>
            </div>
        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; $__TAG__ = '{"type":"pic","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(($vo['link_sort'] == 9003)): ?>
        <div class="wn_floating wn_floating_left_wrapper">
            <div class="wn_floating_image_wrapper">
                <a href="<?php echo $vo['link_url']; ?>" target="_blank" rel="<?php echo $vo['link_rel']; ?>">
                    <img src="<?php echo mac_url_img($vo['link_logo']); ?>">
                </a>
                <div class="wn_floating_close_button wn_floating_close_button_left"><span>x</span></div>
            </div>
            <div class="wn_floating_close_text wn_floating_close_text_left"><?php echo lang('关闭广告'); ?></div>
        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; $__TAG__ = '{"type":"pic","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(($vo['link_sort'] == 9004)): ?>
        <div class="wn_floating wn_floating_right_wrapper">
            <div class="wn_floating_image_wrapper">
                <a href="<?php echo $vo['link_url']; ?>" target="_blank" rel="<?php echo $vo['link_rel']; ?>">
                    <img src="<?php echo mac_url_img($vo['link_logo']); ?>">
                </a>
                <div class="wn_floating_close_button wn_floating_close_button_right"><span>x</span></div>
            </div>
            <div class="wn_floating_close_text wn_floating_close_text_right"><?php echo lang('关闭广告'); ?></div>
        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>


    <script>
        $('.wn_floating_close_text').on('click',function(){
            $(this).parent().fadeOut();
        })
        $('.wn_floating').on('click',function(){
            $(this).fadeOut();
        })
    </script>
<?php endif; ?>
</div>



                        
                        <div class="stui-pannel__bd clearfix">
                            <h2 style="color:white;font-weight:bold;font-size:24px;padding-left:5px;">今日最新</h2>
                            <ul class="stui-vodlist clearfix">
                                <?php $__TAG__ = '{"by":"time","order":"desc","num":"12","id":"vo","key":"key"}';$__LIST__ = model("Vod")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?>
                                <li>
    <div class="stui-vodlist__box link_re">
        <a class="stui-vodlist__thumb lazyload" href="<?php echo mac_url_vod_play($vo); ?>" title="<?php echo unicode_encode($vo['vod_name']); ?>" data-original="<?php echo mac_url_img($vo['vod_pic']); ?>">
            <span class="play hidden-xs"></span>
            <span class="pic-text text-right"><?php echo date('Y-m-d', $vo['vod_time_add']); ?></span>
            <span class="pic-text-top text-right"><?php echo unicode_encode($vo['type']['type_name']); ?></span>
        </a>
        <div class="stui-vodlist__detail">
            <h4 class="title text-overflow"><a href="<?php echo mac_url_vod_detail($vo); ?>" title="<?php echo unicode_encode($vo['vod_name']); ?>" data-original="<?php echo mac_url_img($vo['vod_pic']); ?>"><?php echo unicode_encode($vo['vod_name']); ?>" data-original="<?php echo mac_url_img($vo['vod_pic']); ?>"></a></h4>
            <!--<p class="text text-overflow text-muted hidden-xs">内详</p>-->
        </div>
    </div>
</li>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                             </ul>
                        </div>
                    </div>
                </div>

                <div class="stui-pannel__bd">
    <!-- 分类列表 -->
    <?php $__TAG__ = '{"ids":"parent","flag":"vod","order":"asc","by":"sort","id":"vo","key":"key2"}';$__LIST__ = model("Type")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key2 = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key2 % 2 );++$key2;?>
    <div class="stui-vodlist__head">
        <a class="pull-right" href="<?php echo mac_url_type($vo); ?>">更多 <i class="iconfont icon-more"></i></a>
        <h3><a href="<?php echo mac_url_type($vo); ?>"><i class="iconfont icon-all"></i> <?php echo unicode_encode($vo['type_name']); ?></a></h3>
        <div class="grid-container">
            <?php $__TAG__ = '{"parent":"'. $vo['type_id'] .'","flag":"vod","order":"asc","by":"sort","id":"vo2","key":"key2"}';$__LIST__ = model("Type")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key2 = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($key2 % 2 );++$key2;?>
            <div class="grid-item" style="background: #;"> <a href="<?php echo mac_url_type($vo2); ?>" title="<?php echo unicode_encode($vo2['type_name']); ?>"><?php echo unicode_encode($vo2['type_name']); ?></a></div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <?php endforeach; endif; else: echo "" ;endif; ?>
    <!-- end 分类列表 -->
</div>

<?php if(($wn_link_upgrade)): ?>
<div class="wn_block_link wn_block_link_bottom">
    <h4 class="wn_block_title"><?php echo lang('友情链接'); ?></h4>
    <div class="wn_block_link_wrapper">
        <?php $__TAG__ = '{"type":"font","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(( $vo['link_sort'] >= 0 && $vo['link_sort'] <= 1000)): ?>
                <a class="wn_block_link_item" target="_blank" rel="nofollow" href="<?php echo unicode_encode($vo['link_url']); ?>" style="background-color:<?php echo $vo['link_background_color']; ?>" rel="<?php echo $vo['link_rel']; ?>">
                    <span class="wn_block_link_item_name" style="background-color:<?php echo $vo['link_background_color']; ?>;color:<?php echo $vo['link_name_color']; ?>;"><?php echo unicode_encode($vo['link_name']); ?></span>
                </a>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="container">
    <div class="stui-pannel_ft col-pd">
        <hr style="98%">
    </div>

    <br />

    <div class="row">
        <div class="stui-foot clearfix">
            <p class="text-muted text-center visible-xs">
                <!--本站共有视频资源:127235部 今日更新:425部<br>-->
                <a href="/rss/google.xml" target="_blank">Sitemap</a>丨地址发布页：<a href="xxxxx" target="_blank">xxxxx</a><br>
                合作联系方式：@xxxxx
            </p>
            <p class="text-muted text-center visible-xs">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
            <p class="text-muted text-center visible-xs">© Copyright <?php echo date('Y'); ?> <?php echo unicode_encode($maccms['site_name']); ?> - <?php echo unicode_encode($maccms['site_url']); ?> All Rights Reserved.</p>
        </div>
    </div>
</div>

<style>
    #myBtn {
        display: none;
        position: fixed;
        bottom: 20%;
        right: 2.5%;
        z-index: 99;
        border: none;
        outline: none;
        background-color: #fff;
        color: white;
        cursor: pointer;
        padding: 10px;
        border-radius: 30px;
    }

    #myBtn:hover {
        background-color: #fff;
    }
</style>
<button onclick="topFunction()" id="myBtn" title="返回顶部">👆</button>

<script>
    // 当网页向下滑动 20px 出现"返回顶部" 按钮
    window.onscroll = function () {scrollFunction()};

    function scrollFunction() {
        console.log(121);
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }

    // 点击按钮，返回顶部
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>


<script>
    //document.cookie = 'ad_click_data=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;';
    console.log('link_php loaded');

    function getClickData() {
        var data = document.cookie.split('; ').find(row => row.startsWith('ad_click_data='));
        if (data) { try { return JSON.parse(decodeURIComponent(data.split('=')[1])); } catch (e) {} }
        return {};
    }
    function setClickData(data) {
        document.cookie = 'ad_click_data=' + encodeURIComponent(JSON.stringify(data)) + '; path=/; max-age=' + (60 * 60 * 24);
    }

    async function apiJump() {
        const res = await fetch('/api.php/link/jump', { method: 'POST', cache: 'no-store' });
        const json = await res.json();
        if (json && json.code === 0 && json.data && json.data.url) return json.data; // {id, url, remainingClicks}
        throw new Error(json?.msg || 'jump failed');
    }

    function attachGroup(selector, jumpChance, jumpLimit) {
        const clickData = getClickData();
        const groupKey = 'g:' + selector; // per-group user counter

        document.querySelectorAll(selector).forEach(marker => {
            marker.addEventListener('click', async function (e) {
                // Per-user, per-group jump limit
                const totalGroupClicks = parseInt(clickData[groupKey] || 0);
                if (totalGroupClicks >= jumpLimit) {
                    console.log(`[${selector}] User jump limit reached`);
                    return;
                }

                // Chance gate
                const chanceRoll = Math.random() * 100;
                if (chanceRoll > jumpChance) {
                    console.log(`[${selector}] Jump chance failed. Rolled: ${chanceRoll}`);
                    return;
                }

                e.preventDefault();

                try {
                    const data = await apiJump();

                    // Update user-side counters
                    const idKey = 'l:' + data.id; 
                    clickData[idKey] = (parseInt(clickData[idKey] || 0) + 1);
                    clickData[groupKey] = (parseInt(clickData[groupKey] || 0) + 1);
                    setClickData(clickData);

                    // Analytics
                    if (typeof _paq !== 'undefined') _paq.push(['trackLink', data.url, 'link']);
                    if (typeof gtag === 'function') {
                        gtag('event', 'ad_click', {
                            'event_category': 'Ads',
                            'event_label': data.url,
                            'value': data.id
                        });
                    }

                    // Go!
                    window.open(data.url, '_blank');
                } catch (err) {
                    console.error('Jump failed:', err);
                }
            }, false);
        });
    }

    attachGroup('<?php echo $ads['hidden_ads_css_selector']; ?>',  parseInt('<?php echo !empty($ads['jump_chance'])?$ads['jump_chance']: 100; ?>'),  parseInt('<?php echo !empty($ads['jump_limit'])?$ads['jump_limit']: 9999; ?>'));
    attachGroup('<?php echo $ads['hidden_ads_css_selector2']; ?>', parseInt('<?php echo !empty($ads['jump_chance2'])?$ads['jump_chance2']: 100; ?>'), parseInt('<?php echo !empty($ads['jump_limit2'])?$ads['jump_limit2']: 9999; ?>'));

    console.log('完成');
</script>



<!--
<script>
    //document.cookie = 'ad_click_data=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;';
    console.log('link_js loaded');
    var links = [
        <?php $__TAG__ = '{"type":"font","order":"asc","by":"sort","num":"9999","id":"vo","key":"key"}';$__LIST__ = model("Link")->listCacheData($__TAG__); if(is_array($__LIST__['list']) || $__LIST__['list'] instanceof \think\Collection || $__LIST__['list'] instanceof \think\Paginator): $key = 0; $__LIST__ = $__LIST__['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;if(( $vo['link_sort'] == 9999)): if(($key > 0)): ?>,<?php endif; ?>
                {
                    id: <?php echo $vo['link_id']; ?>,
                    url: '<?php echo $vo['link_url']; ?>',
                    maxClicks: <?php echo $vo['link_max_clicks']; ?>,
                    weight: '<?php echo !empty($vo['link_weight'])?$vo['link_weight']: 1; ?>',
                    currentClicks: 0
                }
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    ];

    function getClickData() {
        var data = document.cookie.split('; ').find(row => row.startsWith('ad_click_data='));
        if (data) {
            try {
                return JSON.parse(decodeURIComponent(data.split('=')[1]));
            } catch (e) {}
        }
        return {};
    }

        function setClickData(data) {
            var now = new Date();
            var midnight = new Date();
            midnight.setHours(24, 0, 0, 0);
        
            document.cookie = 'ad_click_data=' + encodeURIComponent(JSON.stringify(data)) + '; path=/; expires=' + midnight.toUTCString();
        }

    var clickData = getClickData();
    links.forEach(link => {
        link.currentClicks = clickData[link.id] || 0;
    });

    function attachGroup(selector, jumpChance, jumpLimit) {
        document.querySelectorAll(selector).forEach(marker => {
            marker.addEventListener('click', function (e) {
                var availableLinks = links.filter(link => link.currentClicks < link.maxClicks);
                if (availableLinks.length === 0) {
                    console.log(`[${selector}] No available links`);
                    return;
                }

                var totalClicks = availableLinks.reduce((sum, link) => sum + link.currentClicks, 0);
                if (totalClicks >= jumpLimit) {
                    console.log(`[${selector}] Jump limit reached`);
                    return;
                }

                var chanceRoll = Math.random() * 100;
                if (chanceRoll > jumpChance) {
                    console.log(`[${selector}] Jump chance failed. Rolled: ${chanceRoll}`);
                    return;
                }

                e.preventDefault();

                var totalWeight = availableLinks.reduce((sum, link) => sum + parseInt(link.weight), 0);
                var randomWeight = Math.random() * totalWeight;
                var selectedLink;

                for (var i = 0; i < availableLinks.length; i++) {
                    randomWeight -= parseInt(availableLinks[i].weight);
                    if (randomWeight <= 0) {
                        selectedLink = availableLinks[i];
                        break;
                    }
                }

                if (!selectedLink) {
                    selectedLink = availableLinks[availableLinks.length - 1];
                }

                selectedLink.currentClicks++;
                clickData[selectedLink.id] = selectedLink.currentClicks;
                setClickData(clickData);

                console.log(`[${selector}] Logging click:`, selectedLink);
                if (typeof _paq !== 'undefined') _paq.push(['trackLink', selectedLink.url, 'link']);
                if (typeof gtag === 'function') {
                    gtag('event', 'ad_click', {
                        'event_category': 'Ads',
                        'event_label': selectedLink.url,
                        'value': selectedLink.id
                    });
                }

                window.open(selectedLink.url, '_blank');
            }, false);
        });
    }

    attachGroup('<?php echo $ads['hidden_ads_css_selector']; ?>', parseInt('<?php echo !empty($ads['jump_chance'])?$ads['jump_chance']: 100; ?>'), parseInt('<?php echo !empty($ads['jump_limit'])?$ads['jump_limit']: 9999; ?>'));
    attachGroup('<?php echo $ads['hidden_ads_css_selector2']; ?>', parseInt('<?php echo !empty($ads['jump_chance2'])?$ads['jump_chance2']: 100; ?>'), parseInt('<?php echo !empty($ads['jump_limit2'])?$ads['jump_limit2']: 9999; ?>'));

    console.log('完成');
</script>
-->

            </div>
        </div>

    </body>

</html>