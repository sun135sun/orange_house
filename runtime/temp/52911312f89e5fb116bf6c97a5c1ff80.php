<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"/www/wwwroot/18av.web/application/admin/view/extend/pay/funpay.html";i:1629196480;}*/ ?>
<div class="layui-tab-item">

    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>乐付支付设置 <a target="_blank" href="https://www.fun-pays.com/register" class="layui-btn layui-btn-primary">点击进入注册</a></legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">支付商家编号：</label>
        <div class="layui-input-inline w400">
            <input type="text" name="pay[funpay][appid]" placeholder="" value="<?php echo $config['pay']['funpay']['appid']; ?>" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">支付商家密钥：</label>
        <div class="layui-input-inline w400">
            <input type="text" name="pay[funpay][appkey]" placeholder="" value="<?php echo $config['pay']['funpay']['appkey']; ?>" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">收款渠道编号：</label>
        <div class="layui-input-inline w400">
            <input type="text" name="pay[funpay][type]" placeholder="" value="<?php echo $config['pay']['funpay']['type']; ?>" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">1：微信H5；2：支付宝H5；（多个用逗号分隔）</div>
    </div>
</div>