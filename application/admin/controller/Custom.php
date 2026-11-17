<?php
namespace app\admin\controller;
use think\Db;

class Custom extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function theme()
    {
 
        if (Request()->isPost()) {
            $config = input();
                
            $validate = \think\Loader::validate('Token');
            if(!$validate->check($config)){
                return $this->error($validate->getError());
            }
            unset($config['__token__']);

            $config_new = $config['param'];
            $config_old = config('theme') !== null ? config('theme') : [];
            $config_new = array_merge($config_old, $config_new);
						//mdym用
            //$overide_madou = custom_arr2file('template/MDYouTiao/admin/madou.config.php', $config_new ,'madou');
            $res = mac_arr2file(APP_PATH . 'extra/theme.php', $config_new);
            if ($res === false) {
                return $this->error(lang('save_err'));
            }
            return $this->success(lang('save_ok'));

        }

        $this->assign('config', config('theme'));
        return $this->fetch('admin@custom/theme');
    }

    public function ads()
    {
        if (Request()->isPost()) {
            $config = input();
                
            $validate = \think\Loader::validate('Token');
            if(!$validate->check($config)){
                return $this->error($validate->getError());
            }
            unset($config['__token__']);

            $config_new = $config['param'];
            $config_old = config('ads') !== null ? config('ads') : [];
            $config_new = array_merge($config_old, $config_new);
            $res = mac_arr2file(APP_PATH . 'extra/ads.php', $config_new);
            if ($res === false) {
                return $this->error(lang('save_err'));
            }
            return $this->success(lang('save_ok'));

        }

        $this->assign('config', config('ads'));
        return $this->fetch('admin@custom/ads');
    }

}