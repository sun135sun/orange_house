<?php
namespace app\admin\controller;

class Link extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //文字連結
        $param = input();
  
        $param['page'] = intval($param['page']) <1 ? 1 : $param['page'];
        $param['limit'] = intval($param['limit']) <1 ? $this->_pagesize : $param['limit'];
        $where=[];

        // echo "<pre>";
        // var_dump($param);
        // die;

        if(!empty($param['wd'])){
            $param['wd'] = htmlspecialchars(urldecode($param['wd']));
            $where['link_name'] = ['like','%'.$param['wd'].'%'];
        }

 
        if(isset($param['link_type']) && ( $param['link_type'] == 1 ||  $param['link_type'] == '0' )){
            $param['link_type'] = htmlspecialchars(urldecode($param['link_type']));
            if($param['link_type'] == 1){
                $where['link_type'] = ['like', $param['link_type'] ];
            }elseif($param['link_type'] === '0'){
                $where['link_type'] = ['like', $param['link_type'] ];
            }else{
                unset($param['link_type']);
                unset($where['link_type']);
            }
        }

        $positions = [
            //站長推薦
            'webmaster_recommend'=>[
                    'min'=>0,
                    'max'=>1000,
                    'link_type'=>0
            ],
            //上方友連
            'link_recommend'=>[
                    'min'=>1001,
                    'max'=>2000,
                    'link_type'=>0
            ],
            //下方友連
            'bottom_link'=>[
                'min'=>2001,
                'max'=>3000,
                'link_type'=>0
            ],
            //APP圖標
            'app'=>[
                'min'=>3001,
                'max'=>4000,
                'link_type'=>1
            ],
            //橫幅banner
            'banner'=>[
                    'min'=>4001,
                    'max'=>5000,
                    'link_type'=>1
            ],
            //浮窗
            'floating'=>[
                    'min'=>9001,
                    'max'=>9004,
                    'link_type'=>1
            ],
            //影片列表輪播
            'random_banner_ads'=>[
                    'min'=>7001,
                    'max'=>8000,
                    'link_type'=>1
            ],
            //橫幅輪播
            'random_vodlist_ads'=>[
                    'min'=>8001,
                    'max'=>9000,
                    'link_type'=>1
            ],
            //暗链
            'random_jump_link'=>[
                    'min'=>9999,
                    'max'=>9999,
                    'link_type'=>0
            ],
            //视频区链接
            'player_text_link'=>[
                    'min'=>6001,
                    'max'=>6999,
                    'link_type'=>0
            ],
        ];

        if(!empty($param['position'])){
            $param['position'] = htmlspecialchars(urldecode($param['position']));
            $where['link_type'] = ['like','%'.$positions[$param['position']]['link_type'].'%'];
            $where['link_sort'] = ['between', [
                $positions[$param['position']]['min'],
                $positions[$param['position']]['max'],
            ]];
        }


        //顯示排序
        // $order='link_id desc';
        $order='link_sort asc';
        $res = model('Link')->listData($where,$order,$param['page'],$param['limit'], 0 ,$whereBetween);
 
        $this->assign('list',$res['list']);
        $this->assign('total',$res['total']);
        $this->assign('page',$res['page']);
        $this->assign('limit',$res['limit']);

        $param['page'] = '{page}';
        $param['limit'] = '{limit}';
        $this->assign('param',$param);

        // echo "</pre>";
        $this->assign('title',lang('admin/link/title'));
        return $this->fetch('admin@link/index');
    }

    public function info()
    {
        if (Request()->isPost()) {
            $param = input();
            $res = model('Link')->saveData($param);
            if($res['code']>1){
                return $this->error($res['msg']);
            }
            return $this->success($res['msg']);
        }

        $id = input('id');
        $where=[];
        $where['link_id'] = ['eq',$id];
        $res = model('Link')->infoData($where);


        $this->assign('info',$res['info']);
        $this->assign('title',lang('admin/link/title'));
        return $this->fetch('admin@link/info');
    }

    public function del()
    {
        $param = input();
        $ids = $param['ids'];

        if(!empty($ids)){
            $where=[];
            $where['link_id'] = ['in',$ids];
            $res = model('Link')->delData($where);
            if($res['code']>1){
                return $this->error($res['msg']);
            }
            return $this->success($res['msg']);
        }
        return $this->error(lang('param_err'));
    }

    public function batch()
    {
        $param = input();
        $ids = $param['ids'];
     
        foreach ($ids as $k=>$id) {
            $data = [];
            $data['link_id'] = intval($id);
            $data['link_name'] = $param['link_name_' . $id];
            $data['link_sort'] = $param['link_sort_' . $id];
            $data['link_max_clicks'] = $param['link_max_clicks_' . $id];
            $data['link_url'] = $param['link_url_' . $id];
            $data['link_name_color'] = $param['link_name_color_' . $id];
            $data['link_background_color'] = $param['link_background_color_' . $id];
            $data['link_type'] = intval($param['link_type_' . $id]);
            $data['link_logo_height'] = $param['link_logo_height_' . $id];
            $data['link_logo'] = $param['link_logo_' . $id];
            $data['link_weight'] = $param['link_weight_' . $id];
            $data['link_rel'] = $param['link_rel_' . $id];

            if (empty($data['link_name'])) {
                $data['link_name'] = lang('unknown');
            }
          
            $res = model('Link')->saveData($data);
            if($res['code']>1){
                return $this->error($res['msg']);
            }
        }
        $this->success($res['msg']);
    }



}
