<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 連線資訊可用環境變數覆蓋（Docker 測試環境用，見 deploy/README.md）：
//   PHP_DATABASE_HOSTNAME / PHP_DATABASE_DATABASE / PHP_DATABASE_USERNAME / PHP_DATABASE_PASSWORD / PHP_DATABASE_HOSTPORT
// 沒設定時使用下面的預設值（和原本相同）
return [
    // 数据库类型
    'type'            => 'mysql',
    // 服务器地址
    'hostname'        => \think\Env::get('database.hostname', '127.0.0.1'),
    // 数据库名
    'database'        => \think\Env::get('database.database', '18av999_com'),
    // 用户名
    'username'        => \think\Env::get('database.username', '18av999_com'),
    // 密码
    'password'        => \think\Env::get('database.password', 'YStiDbMHmEGNWEY3'),
    // 端口
    'hostport'        => \think\Env::get('database.hostport', '3306'),
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    //'params'          => [], 



    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => 'mac_',
    // 数据库调试模式
    'debug'           => false,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 是否严格检查字段是否存在
    'fields_strict'   => false,
    // 数据集返回类型
    'resultset_type'  => 'array',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,
    // Builder类
    'builder'         => '',
    // Query类
    'query'           => '\think\db\Query',
];
