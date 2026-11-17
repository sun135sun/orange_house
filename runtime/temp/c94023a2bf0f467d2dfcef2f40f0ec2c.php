<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:69:"/www/wwwroot/18av.web/application/admin/view/system/configweixin.html";i:1629196480;s:61:"/www/wwwroot/18av.web/application/admin/view/public/head.html";i:1629196480;s:61:"/www/wwwroot/18av.web/application/admin/view/public/foot.html";i:1629196480;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $title; ?> - <?php echo lang('admin/public/head/title'); ?></title>
    <link rel="stylesheet" href="/static/layui/css/layui.css?v=1024">
    <link rel="stylesheet" href="/static/css/admin_style.css?v=1024">
    <script type="text/javascript" src="/static/js/jquery.js?v=1024"></script>
    <script type="text/javascript" src="/static/layui/layui.js?v=1024"></script>
    <script>
        var ROOT_PATH="",ADMIN_PATH="<?php echo $_SERVER['SCRIPT_NAME']; ?>", MAC_VERSION="v10";
    </script>
</head>
<body>

<div class="page-container">
    <form class="layui-form layui-form-pane" action="">
        <input type="hidden" name="__token__" value="<?php echo \think\Request::instance()->token(); ?>" />
        <div class="layui-tab">
            <ul class="layui-tab-title">
                <li class="layui-this"><?php echo lang('admin/system/configweixin/title'); ?></li>
            </ul>
            <div class="layui-tab-content">



            <div class="layui-tab-item layui-show">
                <blockquote class="layui-elem-quote layui-quote-nm">
                    <?php echo lang('admin/system/configweixin/tip'); ?>
                </blockquote>

                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('status'); ?>：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="weixin[status]" value="0" title="<?php echo lang('close'); ?>" <?php if($config['weixin']['status'] != 1): ?>checked <?php endif; ?>>
                        <input type="radio" name="weixin[status]" value="1" title="<?php echo lang('open'); ?>" <?php if($config['weixin']['status'] == 1): ?>checked <?php endif; ?>>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('admin/system/configweixin/duijie'); ?>：</label>
                    <div class="layui-input-block">
                        <input type="text" name="weixin[duijie]" placeholder="<?php echo lang('admin/system/configweixin/duijie_tip'); ?>" value="<?php echo $config['weixin']['duijie']; ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('admin/system/configweixin/sousuo'); ?>：</label>
                    <div class="layui-input-block">
                        <input type="text" name="weixin[sousuo]" placeholder="<?php echo lang('admin/system/configweixin/sousuo_tip'); ?>" value="<?php echo $config['weixin']['sousuo']; ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('admin/system/configweixin/token'); ?>：</label>
                    <div class="layui-input-block">
                        <input type="text" name="weixin[token]" placeholder="<?php echo lang('admin/system/configweixin/token_tip'); ?>" value="<?php echo $config['weixin']['token']; ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('admin/system/configweixin/guanzhu'); ?>：</label>
                    <div class="layui-input-block">
                        <textarea type="text" name="weixin[guanzhu]" placeholder="<?php echo lang('admin/system/configweixin/guanzhu_tip'); ?>" value="" class="layui-textarea"><?php echo $config['weixin']['guanzhu']; ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('admin/system/configweixin/wuziyuan'); ?>：</label>
                    <div class="layui-input-block">
                        <textarea type="text" name="weixin[wuziyuan]" placeholder="<?php echo lang('admin/system/configweixin/wuziyuan_tip'); ?>" value="" class="layui-textarea"><?php echo $config['weixin']['wuziyuan']; ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('admin/system/configweixin/wuziyuanlink'); ?>：</label>
                    <div class="layui-input-block">
                        <input type="text" name="weixin[wuziyuanlink]" placeholder="<?php echo lang('admin/system/configweixin/wuziyuanlink_tip'); ?>" value="<?php echo $config['weixin']['wuziyuanlink']; ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('admin/system/configweixin/pagelink'); ?>：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="weixin[bofang]" value="0" title="<?php echo lang('admin/system/configweixin/pagelink_detail'); ?>" <?php if($config['weixin']['bofang'] != 1): ?>checked <?php endif; ?>>
                        <input type="radio" name="weixin[bofang]" value="1" title="<?php echo lang('admin/system/configweixin/pagelink_play'); ?>" <?php if($config['weixin']['bofang'] == 1): ?>checked <?php endif; ?>>
                        <input type="radio" name="weixin[bofang]" value="2" title="<?php echo lang('admin/system/configweixin/pagelink_search'); ?>" <?php if($config['weixin']['bofang'] == 2): ?>checked <?php endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo lang('admin/system/configweixin/msgtype'); ?>：</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="weixin[msgtype]" value="0" title="<?php echo lang('admin/system/configweixin/msgtype_pic'); ?>" <?php if($config['weixin']['msgtype'] != 1): ?>checked <?php endif; ?>>
                        <input type="radio" name="weixin[msgtype]" value="1" title="<?php echo lang('admin/system/configweixin/msgtype_font'); ?>" <?php if($config['weixin']['msgtype'] == 1): ?>checked <?php endif; ?>>
                    </div>
                    <div class="layui-form-mid layui-word-aux"><?php echo lang('admin/system/configweixin/msgtype_tip'); ?></div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <?php echo lang('admin/system/configweixin/gjc'); ?>1：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjc1]" placeholder="<?php echo lang('admin/system/configweixin/keyword'); ?>" value="<?php echo $config['weixin']['gjc1']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjcm1]" placeholder="<?php echo lang('admin/system/configweixin/return_text'); ?>" value="<?php echo $config['weixin']['gjcm1']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjci1]" placeholder="<?php echo lang('admin/system/configweixin/return_pic'); ?>" value="<?php echo $config['weixin']['gjci1']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjcl1]" placeholder="<?php echo lang('admin/system/configweixin/return_link'); ?>" value="<?php echo $config['weixin']['gjcl1']; ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <?php echo lang('admin/system/configweixin/gjc'); ?>2：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjc2]" placeholder="<?php echo lang('admin/system/configweixin/keyword'); ?>" value="<?php echo $config['weixin']['gjc2']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjcm2]" placeholder="<?php echo lang('admin/system/configweixin/return_text'); ?>" value="<?php echo $config['weixin']['gjcm2']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjci2]" placeholder="<?php echo lang('admin/system/configweixin/return_pic'); ?>" value="<?php echo $config['weixin']['gjci2']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjcl2]" placeholder="<?php echo lang('admin/system/configweixin/return_link'); ?>" value="<?php echo $config['weixin']['gjcl2']; ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <?php echo lang('admin/system/configweixin/gjc'); ?>3：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjc3]" placeholder="<?php echo lang('admin/system/configweixin/keyword'); ?>" value="<?php echo $config['weixin']['gjc3']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjcm3]" placeholder="<?php echo lang('admin/system/configweixin/return_text'); ?>" value="<?php echo $config['weixin']['gjcm3']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjci3]" placeholder="<?php echo lang('admin/system/configweixin/return_pic'); ?>" value="<?php echo $config['weixin']['gjci3']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjcl3]" placeholder="<?php echo lang('admin/system/configweixin/return_link'); ?>" value="<?php echo $config['weixin']['gjcl3']; ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <?php echo lang('admin/system/configweixin/gjc'); ?>4：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjc4]" placeholder="<?php echo lang('admin/system/configweixin/keyword'); ?>" value="<?php echo $config['weixin']['gjc4']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjcm4]" placeholder="<?php echo lang('admin/system/configweixin/return_text'); ?>" value="<?php echo $config['weixin']['gjcm4']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjci4]" placeholder="<?php echo lang('admin/system/configweixin/return_pic'); ?>" value="<?php echo $config['weixin']['gjci4']; ?>" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="weixin[gjcl4]" placeholder="<?php echo lang('admin/system/configweixin/return_link'); ?>" value="<?php echo $config['weixin']['gjcl4']; ?>" class="layui-input">
                    </div>
                </div>

            </div>
            </div>
        </div>
        <div class="layui-form-item center">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-submit="" lay-filter="formSubmit"><?php echo lang('btn_save'); ?></button>
                <button class="layui-btn layui-btn-warm" type="reset"><?php echo lang('btn_reset'); ?></button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript" src="/static/js/admin_common.js?<?php echo system_version(); ?>"></script>
<script type="text/javascript">

</script>

</body>
</html>