<?php
/*
'软件名称：苹果CMS 源码库：https://github.com/maccmspro
'--------------------------------------------------------
'Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
'遵循Apache2开源协议发布，并提供免费使用。
'--------------------------------------------------------
*/
header('Content-Type:text/html;charset=utf-8');

/**
 * 檢測訪問的ip是否為規定的允許的ip
 * Enter description here ...
 */
function check_ip(){ 
    $ALLOWED_IP=array('127.0.0.1','220.133.180.92','132.226.5.252','60.251.120.152','114.34.27.191');
    // 額外允許的 IP（逗號分隔，可用 * 萬用字元），Docker 測試環境用，見 deploy/README.md；沒設定時和原本相同
    if (getenv('PHP_ADMIN_ALLOWED_IPS')) {
        $ALLOWED_IP = array_merge($ALLOWED_IP, array_filter(array_map('trim', explode(',', getenv('PHP_ADMIN_ALLOWED_IPS')))));
    }
    $IP=getIP(); 
    $check_ip_arr= explode('.',$IP);//要檢測的ip拆分成陣列 
    #限制IP 
    if(!in_array($IP,$ALLOWED_IP)) { 
        foreach ($ALLOWED_IP as $val){ 
            if(strpos($val,'*')!==false){//發現有*號替代符 
                 $arr=array();// 
                 $arr=explode('.', $val); 
                 $bl=true;//用於記錄迴圈檢測中是否有匹配成功的 
                 for($i=0;$i<4;$i++){ 
                    if($arr[$i]!='*'){//不等於*  就要進來檢測,如果為*符號替代符就不檢查 
                        if($arr[$i]!=$check_ip_arr[$i]){ 
                            $bl=false; 
                            break;//終止檢查本個ip 繼續檢查下一個ip 
                        } 
                    } 
                 }//end for  
                 if($bl){//如果是true則找到有一個匹配成功的就返回 
                    return; 
                    die; 
                 } 
            } 
        }//end foreach 
        header('HTTP/1.1 403 Forbidden'); 
        echo "Access forbidden"; 
        die; 
    } 
} 

/**
 * 獲得訪問的IP
 * Enter description here ...
 */ 
function getIP() { 
    return isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"] 
    :(isset($_SERVER["HTTP_CLIENT_IP"])?$_SERVER["HTTP_CLIENT_IP"] 
    :$_SERVER["REMOTE_ADDR"]); 
}

check_ip();

/**
 * 檢測訪問的ip是否為規定的允許的ip end
 * Enter description here ...
 */ 



// 检测PHP环境
if(version_compare(PHP_VERSION,'5.5.0','<'))  die('PHP版本需要>=5.5，请升级【PHP version requires > = 5.5，please upgrade】');
//超时时间
@ini_set('max_execution_time', '0');
//内存限制 取消内存限制
@ini_set("memory_limit",'-1');
// 定义应用目录
define('ROOT_PATH', __DIR__ . '/');
define('APP_PATH', __DIR__ . '/application/');
define('MAC_COMM', __DIR__ . '/application/common/common/');
define('MAC_HOME_COMM', __DIR__ . '/application/index/common/');
define('MAC_ADMIN_COMM', __DIR__ . '/application/admin/common/');
define('MAC_START_TIME', microtime(true) );
//define('BIND_MODULE','admin');
define('ENTRANCE', 'admin');
$in_file = rtrim($_SERVER['SCRIPT_NAME'],'/');
if(substr($in_file,strlen($in_file)-4)!=='.php'){
    $in_file = substr($in_file,0,strpos($in_file,'.php')) .'.php';
}
define('IN_FILE',$in_file);

if(!is_file('./application/data/install/install.lock')) {
    header("Location: ./install.php");
    exit;
}
if (!@mb_check_encoding($_SERVER['PATH_INFO'], 'utf-8')){
    $_SERVER['PATH_INFO']=@mb_convert_encoding($_SERVER['PATH_INFO'], 'UTF-8', 'GBK');
}
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
if(env('app_environment') == 'production' && strpos($_SERVER["SCRIPT_NAME"],'/admin.php')!==false){
    echo '请将后台入口文件admin.php改名,避免被黑客入侵攻击【Please rename the background entry file admin.php to avoid being hacked】';
    exit;
}
