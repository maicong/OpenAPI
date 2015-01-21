#!/usr/bin/php
<?php
if (php_sapi_name() != "cli") exit ("请在终端(Terminal)中运行! \n");
header('Content-Type:text/html; charset=utf-8');
define('C_PATH', rtrim(str_replace("\\", "/", dirname(__FILE__)), '/'));
require_once 'webqq.class.php';
echo "--------> 登录中 \n";

$uin = '12345678'; // 帐号
$pwd = '12345678'; // 密码

$webqq = new WebQQ($uin, $pwd);
$cookie = $webqq->get_cookie();
$cookie['clientid'] = mt_rand(20000000,88888888);
if(empty($cookie['ptwebqq'])){
    if($webqq->qq_login()){
        $cookie = $webqq->get_cookie();
        echo "--------> 登录成功 \n";
    }
}else{
    if(empty($cookie['psessionid'])){
        $online = $webqq->qq_online($cookie['ptwebqq'], $cookie['clientid']);
        $cookie += $online;
        echo "--------> 上线成功 \n";
    }
}
function http_post($url, $param){
    $ch = curl_init();
    $host = parse_url($url);
    $site = $host['scheme'] . "://" . $host['host'];
    if (is_string($param)) {
        $post = $param;
    } else {
        $pArr = array();
        foreach ($param as $key => $val) {
            $pArr[] = $key . "=" . urlencode($val);
        }
        $post = join("&", $pArr);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tlcookie);
    curl_setopt($ch, CURLOPT_REFERER, $site);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:1.2.4.8', 'X-FORWARDED-HOST:' . $host, 'X-FORWARDED-SERVER:' . $host));
    $response =  curl_exec($ch);
    curl_close($ch);
    return $response;
}

// 清除HTML标记
function clearhtml($str) {
    $str = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $str);
    $str = preg_replace("/\<[a-z]+(.*)\>/iU", " ", $str);
    $str = preg_replace("/\<\/[a-z]+\>/iU", " ", $str);
    $str = str_replace(array(' ','  ', chr(13), chr(10), '&nbsp;'), array(' ', ' ', ' ', ' ', ' '), $str);
    return $str;
}

function auto_reply($str){
    $answer = http_post('http://www.xiaohuangji.com/ajax.php', array('para'=>$str));
    $answer = clearhtml((string)$answer);
    $answer = preg_replace('/(机器人|小黄鸡)/isu','麦葱酱', $answer);
    if(empty($answer)){
        $answer = '...什么嘛。现在是 '.date('Y-m-d H:i:s', time());
    }
    return $answer;
}

static $run_num = 1;
while(true){
    sleep(3);
    echo "--------> 正在检测新消息 (第 {$run_num} 次) \n";
    $poll = $webqq->poll($cookie['ptwebqq'], $cookie['clientid'], $cookie['psessionid']);
    $run_num++;
    if(empty($poll)) continue;
    echo '--------> 收到新消息 (' . count($poll) . ' 条)' . "\n";
    foreach ($poll as $val) {
        $msg = $val['value']['content'][1];
        if(empty($msg)) continue;
        if(is_array($msg)){
            $msg = '[图片]';
            $cont = ' ';
        }else{
            $msg = $msg;
            $cont = auto_reply($msg);
        }
        echo "-----------> 内容: {$msg} \n";
        $webqq->write_file(C_PATH.'/logs/msg.txt', "--> ".$msg . " ==> ".  $cont."\r\n", "a+");
        if($val['poll_type'] == 'group_message'){
            if($msg == '[图片]'){
                $sendmsg = $webqq->send_qun_msg($val['value']['from_uin'], $cont, $cookie['clientid'], $cookie['psessionid'], mt_rand(0,56));
            }else{
                $sendmsg = $webqq->send_qun_msg($val['value']['from_uin'], $cont, $cookie['clientid'], $cookie['psessionid']);
            }
        }else if($val['poll_type'] == 'message'){
            if($msg == '[图片]'){
                $sendmsg = $webqq->send_buddy_msg($val['value']['from_uin'], $cont, $cookie['clientid'], $cookie['psessionid'], mt_rand(0,56));
            }else{
                $sendmsg = $webqq->send_buddy_msg($val['value']['from_uin'], $cont, $cookie['clientid'], $cookie['psessionid']);
            }
        }
        echo "-----------> 回复: {$cont} \n";
    }
}