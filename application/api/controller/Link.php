<?php

namespace app\api\controller;

use think\Controller;
use think\Cache;
use think\Db;

class Link extends Base
{
    protected $debug = true;
    //protected $pw = '123456';
    protected $allowedIps = ['127.0.0.1', '220.133.180.92', '1.161.89.119', '60.251.120.152','114.34.27.191'];
    
    public function __construct()
    {
        parent::__construct();
        // 額外允許的 IP（逗號分隔），Docker 測試環境用，見 deploy/README.md；沒設定時和原本相同
        if (getenv('PHP_ADMIN_ALLOWED_IPS')) {
            $this->allowedIps = array_merge($this->allowedIps, array_filter(array_map('trim', explode(',', getenv('PHP_ADMIN_ALLOWED_IPS')))));
        }
    }

    public function index()
    {
        echo "link api";
    }

    public function fetch()
    {
        if(!$this->debug){
            echo "debug mode is off";
            die;
        }
        $where = ['link_sort' => 9999];
        $order = 'link_id desc';

        $links = model("Link")->listData($where, $order, 1, 9999);

        $data = [];
        foreach (($links['list'] ?? []) as $row) {
            $max       = (int)($row['link_max_clicks'] ?? 9999);
            $remaining = $row['link_remaining_clicks'];
            $remaining = is_null($remaining) ? $max : (int)$remaining;

            $data[] = [
                'id'            => (int)($row['link_id'] ?? 0),
                'url'           => (string)($row['link_url'] ?? ''),
                'maxClicks'     => $max,
                'remainingClicks' => max(0, $remaining),
                'weight'        => (int)($row['link_weight'] ?? 1),
            ];
        }

        return json([
            'code' => 0,
            'msg'  => 'ok',
            'data' => ['links' => $data],
        ]);
    }

    public function fetch_pc()
    {
        if(!$this->debug){
            echo "debug mode is off";
            die;
        }
        $where = ['link_sort' => 9998];
        $order = 'link_id desc';

        $links = model("Link")->listData($where, $order, 1, 9999);

        $data = [];
        foreach (($links['list'] ?? []) as $row) {
            $max       = (int)($row['link_max_clicks'] ?? 9999);
            $remaining = $row['link_remaining_clicks'];
            $remaining = is_null($remaining) ? $max : (int)$remaining;

            $data[] = [
                'id'            => (int)($row['link_id'] ?? 0),
                'url'           => (string)($row['link_url'] ?? ''),
                'maxClicks'     => $max,
                'remainingClicks' => max(0, $remaining),
                'weight'        => (int)($row['link_weight'] ?? 1),
            ];
        }

        return json([
            'code' => 0,
            'msg'  => 'ok',
            'data' => ['links' => $data],
        ]);
    }

    public function count($id = null)
    {
        // /api.php/link/count?id=123
        $id = $id !== null ? intval($id) : intval(input('id'));
        if (!$id) {
            return json(['code' => 1, 'msg' => 'invalid id']);
        }

        $link = Db::name('Link')->where('link_id', $id)->find();

        if (!$link) {
            return json(['code' => 1, 'msg' => 'link not found']);
        }

        // If null, initialize with max clicks (only if still null, so a concurrent init cannot reset a decrement)
        if (is_null($link['link_remaining_clicks'])) {
            Db::name('Link')
                ->where('link_id', $id)
                ->whereNull('link_remaining_clicks')
                ->update(['link_remaining_clicks' => Db::raw('link_max_clicks')]);
        }

        // Atomic decrement guarded by > 0 so two concurrent requests cannot both consume the same click
        $affected = Db::name('Link')
            ->where('link_id', $id)
            ->where('link_remaining_clicks', '>', 0)
            ->setDec('link_remaining_clicks', 1);

        if ($affected === false) {
            return json(['code' => 1, 'msg' => 'update failed']);
        }

        $remaining = (int) Db::name('Link')->where('link_id', $id)->value('link_remaining_clicks');

        if (!$affected) {
            return json(['code' => 3, 'msg' => 'no remaining clicks', 'data' => ['remainingClicks' => $remaining]]);
        }

        return json([
            'code' => 0,
            'msg'  => 'ok',
            'data' => ['remainingClicks' => $remaining],
        ]);
    }

    public function reset()
    {
        $clientIp = request()->ip();
        if (!in_array($clientIp, $this->allowedIps) && !in_array($clientIp, ['127.0.0.1', '::1'])) {
            return '403';
        }
        // /api.php/link/reset            -> reset ALL
        // /api.php/link/reset?id=123     -> reset ONE
        $id         = intval(input('id', 0));
        $linkModel  = model("Link")->where('link_sort', 9999);
        $query      = $linkModel;

        if ($id > 0) {
            $query = $query->where('link_id', $id);
        }

        $affected = $query->update([
            'link_remaining_clicks' => \think\Db::raw('link_max_clicks')
        ]);

        if ($affected === false) {
            return json(['code' => 1, 'msg' => 'reset failed']);
        }

        return json(['code' => 0, 'msg' => 'ok', 'data' => ['affected' => $affected]]);
    }

    public function reset_pc()
    {
        $clientIp = request()->ip();
        if (!in_array($clientIp, $this->allowedIps) && !in_array($clientIp, ['127.0.0.1', '::1'])) {
            return '403';
        }
        // /api.php/link/reset            -> reset ALL
        // /api.php/link/reset?id=123     -> reset ONE
        $id         = intval(input('id', 0));
        $linkModel  = model("Link")->where('link_sort', 9998);
        $query      = $linkModel;

        if ($id > 0) {
            $query = $query->where('link_id', $id);
        }

        $affected = $query->update([
            'link_remaining_clicks' => \think\Db::raw('link_max_clicks')
        ]);

        if ($affected === false) {
            return json(['code' => 1, 'msg' => 'reset failed']);
        }

        return json(['code' => 0, 'msg' => 'ok', 'data' => ['affected' => $affected]]);
    }

    public function jump()
    {
        // get available link pool
        $links = Db::name('Link')
            ->where('link_sort', 9999)
            ->where(function ($q) {
                $q->where('link_remaining_clicks', '>', 0)
                    ->whereOr(function ($q2) {
                        $q2->whereNull('link_remaining_clicks')->where('link_max_clicks', '>', 0);
                    });
            })
            ->order('link_id desc')
            ->select();

        if (empty($links)) {
            return json(['code' => 2, 'msg' => 'no eligible links']);
        }

        // pick one according to weight
        $pickedId = $this->pick($links);
        if (!$pickedId) {
            return json(['code' => 2, 'msg' => 'no link picked']);
        }

        // deduct count via count($id)
        $resp    = $this->count($pickedId);
        $payload = json_decode($resp->getContent(), true);
        if (!is_array($payload) || ($payload['code'] ?? 1) !== 0) {
            return json(['code' => 5, 'msg' => 'decrement failed']);
        }

        // fetch URL to return
        $selectedUrl = (string) Db::name('Link')->where('link_id', $pickedId)->value('link_url');

        return json([
            'code' => 0,
            'msg'  => 'ok',
            'data' => [
                'id'              => (int)$pickedId,
                'url'             => $selectedUrl,
                'remainingClicks' => (int)($payload['data']['remainingClicks'] ?? 0),
            ],
        ]);
    }

    public function jump_pc()
    {
        // get available link pool
        $links = Db::name('Link')
            ->where('link_sort', 9998)
            ->where(function ($q) {
                $q->where('link_remaining_clicks', '>', 0)
                    ->whereOr(function ($q2) {
                        $q2->whereNull('link_remaining_clicks')->where('link_max_clicks', '>', 0);
                    });
            })
            ->order('link_id desc')
            ->select();

        if (empty($links)) {
            return json(['code' => 2, 'msg' => 'no eligible links']);
        }

        // pick one according to weight
        $pickedId = $this->pick($links);
        if (!$pickedId) {
            return json(['code' => 2, 'msg' => 'no link picked']);
        }

        // deduct count via count($id)
        $resp    = $this->count($pickedId);
        $payload = json_decode($resp->getContent(), true);
        if (!is_array($payload) || ($payload['code'] ?? 1) !== 0) {
            return json(['code' => 5, 'msg' => 'decrement failed']);
        }

        // fetch URL to return
        $selectedUrl = (string) Db::name('Link')->where('link_id', $pickedId)->value('link_url');

        return json([
            'code' => 0,
            'msg'  => 'ok',
            'data' => [
                'id'              => (int)$pickedId,
                'url'             => $selectedUrl,
                'remainingClicks' => (int)($payload['data']['remainingClicks'] ?? 0),
            ],
        ]);
    }

    protected function pick($links)
    {
        $totalWeight = 0;
        foreach ($links as $row) {
            $w = isset($row['link_weight']) ? (int)$row['link_weight'] : 1;
            if ($w < 1) $w = 1;
            $totalWeight += $w;
        }
        if ($totalWeight <= 0) return null;

        $rand = mt_rand(1, $totalWeight);
        foreach ($links as $row) {
            $w = isset($row['link_weight']) ? (int)$row['link_weight'] : 1;
            if ($w < 1) $w = 1;
            $rand -= $w;
            if ($rand <= 0) {
                return (int)$row['link_id'];
            }
        }
        // fallback
        return (int)end($links)['link_id'];
    }
}
