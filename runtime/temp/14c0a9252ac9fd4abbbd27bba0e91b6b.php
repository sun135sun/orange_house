<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:68:"/www/wwwroot/18av.web/application/admin/view/system/configemail.html";i:1629196480;s:61:"/www/wwwroot/18av.web/application/admin/view/public/head.html";i:1629196480;s:61:"/www/wwwroot/18av.web/application/admin/view/public/foot.html";i:1629196480;}*/ ?>
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
        <div class="layui-tab" lay-filter="tb1">
            <ul class="layui-tab-title">
                <li class="layui-this" lay-id="configemail_1"><?php echo lang('admin/system/configemail/title'); ?></li>
                <?php if(is_array($extends['ext_list']) || $extends['ext_list'] instanceof \think\Collection || $extends['ext_list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $extends['ext_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li data-key="<?php echo $key; ?>" lay-id="configemail_<?php echo $i+1; ?>"><?php echo $vo; ?></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">

                    <blockquote class="layui-elem-quote layui-quote-nm">
                        <?php echo lang('admin/system/configemail/tip'); ?>
                    </blockquote>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/type'); ?>：</label>
                        <div class="layui-input-inline">
                            <select class="w150" id="ac" name="email[type]" lay-filter="ac">
                                <option value="" ><?php echo lang('select_please'); ?>...</option>
                                <?php if(is_array($extends['ext_list']) || $extends['ext_list'] instanceof \think\Collection || $extends['ext_list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $extends['ext_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo $key; ?>" <?php if($config['email']['type'] == $key): ?>selected <?php endif; ?>><?php echo $vo; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/time'); ?>：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="email[time]" placeholder="" value="<?php echo $config['email']['time']; ?>" class="layui-input w200"  >
                        </div>
                        <div class="layui-form-mid layui-word-aux"><?php echo lang('admin/system/configemail/time_tip'); ?></div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/nick'); ?>：</label>
                        <div class="layui-input-inline">
                            <input type="text" id="nick" name="email[nick]" placeholder="" value="<?php echo $config['email']['nick']; ?>" class="layui-input w200"  >
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/test'); ?>：</label>
                        <div class="layui-input-inline">
                            <input type="text" id="test" name="email[test]" placeholder="" value="<?php echo $config['email']['test']; ?>" class="layui-input w200"  >
                        </div>
                        <button type="button" class="layui-btn layui-btn-normal" onclick="test_email()"><?php echo lang('admin/system/configemail/btn_test'); ?></button>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/test_title'); ?>：</label>
                        <div class="layui-input-block">
                            <input type="text" name="email[tpl][test_title]" placeholder="" value="<?php echo $config['email']['tpl']['test_title']; ?>" class="layui-input"  >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/test_body'); ?>：</label>
                        <div class="layui-input-block">
                            <textarea name="email[tpl][test_body]" class="layui-textarea"><?php echo $config['email']['tpl']['test_body']; ?></textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/user_reg_title'); ?>：</label>
                        <div class="layui-input-block">
                            <input type="text" name="email[tpl][user_reg_title]" placeholder="" value="<?php echo $config['email']['tpl']['user_reg_title']; ?>" class="layui-input"  >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/user_reg_body'); ?>：</label>
                        <div class="layui-input-block">
                            <textarea name="email[tpl][user_reg_body]" class="layui-textarea"><?php echo $config['email']['tpl']['user_reg_body']; ?></textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/user_bind_title'); ?>：</label>
                        <div class="layui-input-block">
                            <input type="text" name="email[tpl][user_bind_title]" placeholder="" value="<?php echo $config['email']['tpl']['user_bind_title']; ?>" class="layui-input"  >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/user_bind_body'); ?>：</label>
                        <div class="layui-input-block">
                            <textarea name="email[tpl][user_bind_body]" class="layui-textarea"><?php echo $config['email']['tpl']['user_bind_body']; ?></textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/user_findpass_title'); ?>：</label>
                        <div class="layui-input-block">
                            <input type="text" name="email[tpl][user_findpass_title]" placeholder="" value="<?php echo $config['email']['tpl']['user_findpass_title']; ?>" class="layui-input"  >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo lang('admin/system/configemail/user_findpass_body'); ?>：</label>
                        <div class="layui-input-block">
                            <textarea name="email[tpl][user_findpass_body]" class="layui-textarea"><?php echo $config['email']['tpl']['user_findpass_body']; ?></textarea>
                        </div>
                    </div>
                </div>
                <?php echo $extends['ext_html']; ?>
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
<script type="text/javascript" src="/static/js/jquery.cookie.js"></script>
<script type="text/javascript">
    layui.use(['element', 'form', 'layer'], function() {
        var element = layui.element
            ,form = layui.form
            , layer = layui.layer;


        element.on('tab(tb1)', function(){
            $.cookie('configemail_tab', this.getAttribute('lay-id'));
        });

        if( $.cookie('configemail_tab') !=null ) {
            element.tabChange('tb1', $.cookie('configemail_tab'));
        }

    });
    function test_email() {
        var type = $('#ac').val();
        var test = $("#test").val();
        var nick = $("#nick").val();

        layer.msg("<?php echo lang('wait_submit'); ?>",{time:500000});
        $.ajax({
            url: "<?php echo url('system/test_email'); ?>",
            type: "post",
            dataType: "json",
            data: {type:type,nick:nick,test:test},
            beforeSend: function () {
            },
            error:function(r){
                layer.msg("<?php echo lang('admin/system/configemail/test_err'); ?>",{time:1800});
            },
            success: function (r) {
                layer.msg(r.msg,{time:1800});
            },
            complete: function () {
            }
        });
    }
</script>

</body>
</html>