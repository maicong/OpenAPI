#!/usr/bin/php
<?php
/**
 *
 * PHP WEB QQ [演示]
 *
 * @author     MaiCong <admin@maicong.me>
 * @date  2015-01-22 13:21:21
 * @package    webqq
 * @version    0.2 alpha
 *
 */

if (php_sapi_name() != "cli") exit ('[' . date("Y-m-d H:i:s",time()) . '] 请在终端(Terminal)中运行!' . "\n");

//请先cd到目录，然后运行命令: /usr/local/php/bin/php qqrobot.php 2>&1 | tee -a logs/terminal.txt

header('Content-Type:text/html; charset=utf-8');
define('C_PATH', rtrim(str_replace("\\", "/", dirname(__FILE__)), '/'));
require_once 'webqq.class.php';

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

// 自动回复
function auto_reply($str){
    $answer = http_post('http://www.xiaohuangji.com/ajax.php', array('para'=>$str));
    $answer = clearhtml((string)$answer);
    $answer = preg_replace('/(机器人|小黄鸡)/isu','麦葱酱', $answer);
    $answer = str_replace('%n','/n', $answer);
    if(empty($answer)){
        return '';
    }
    return $answer;
}

// 比较相似度
function similar_word($word1, $word2, $num = 60){
    if(is_string($word1) && is_string($word2)){
        similar_text($word1, $word2, $similarity);
    }
    if(is_array($word1) && is_array($word2)){
        $word1 = implode('', $word1);
        $word2 = implode('', $word2);
        similar_text($word1, $word2, $similarity);
    }
    if(is_string($word1) && is_array($word2)){
        foreach ($word2 as $k=>$v) {
            similar_text($word1, $v, $similarity[$k]);
        }
        foreach ($similarity as $k2 => $v2) {
            $similarity[$k2] = round($v2);
        }
        arsort($similarity);
        $similarity = reset($similarity);
    }
    if(is_array($word1) && is_string($word2)){
        foreach ($word1 as $k3=>$v3) {
            similar_text($v3, $word2, $similarity[$k3]); 
        }
        foreach ($similarity as $k4 => $v4) {
            $similarity[$k4] = round($v4);
        }
        arsort($similarity);
        $similarity = reset($similarity);
    }
    if($similarity > $num){
        return true;
    }
    return false;
}

// 获取$_COOKIE
function _cookie($key) {
    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
}

function while_poll($runing = true){
    global $webqq, $cookie;
    static $run_num = 1;
    while($runing){
        sleep(3); // 睡3秒，不然进程卡死
        echo '[' . date("Y-m-d H:i:s",time()) . '] 正在检测新消息 (' . $run_num . ') ... ' . "\n";
        $poll = $webqq->poll($cookie['ptwebqq'], $cookie['clientid'], $cookie['psessionid']);
        $run_num++;
        if(empty($poll)) continue;
        echo '[' . date("Y-m-d H:i:s",time()) . '] 收到新消息 (' . count($poll) . ') !' . "\n";
        foreach ($poll as $val) {
            sleep(3); // 睡3秒，不然发送频率太高发不出去
            $msg = $val['value']['content'][1];
            $msg = preg_replace('/(机器人|小黄鸡|小九|图灵机器人)/isu','麦葱酱', $msg);

            if(empty($msg) || !is_string($msg)) {
                echo '[' . date("Y-m-d H:i:s",time()) . '] 消息不是有效文本, 跳过.' . "\n";
                continue;
            }
            $reply = auto_reply($msg);
            if(empty($reply)) {
                echo '[' . date("Y-m-d H:i:s",time()) . '] 没有对应回复信息, 跳过.' . "\n";
                continue;
            }

            $msg_out = array('麦葱酱再见', '麦葱酱退下', '麦葱酱闭嘴', '麦葱酱好吵', '麦葱酱下线', '麦葱酱滚蛋');
            $msg_in = array('麦葱酱回来', '麦葱酱过来', '麦葱酱粗来', '麦葱酱说话', '麦葱酱上线', '麦葱酱我爱你');

            if(similar_word($msg, $msg_out)){
                $reply = '拜拜啦~ 叫我出来请说: ' . implode(', ', $msg_in);
                setcookie('mc_online', 'off', time() + 36000);
            }
            if(similar_word($msg, $msg_in)){
                $reply = '我来了，叫我滚蛋请说: ' . implode(', ', $msg_out);
                setcookie('mc_online', 'on', time() + 36000);
                setcookie('online_status', 'yes', time() + 36000);
            }

            if(_cookie('mc_online') == 'off' && _cookie('online_status') == 'no') continue;

            echo '[' . date("Y-m-d H:i:s",time()) . '] 内容: ' . $msg . "\n";
            echo '[' . date("Y-m-d H:i:s",time()) . '] 回复: ' . $reply . "\n";

            $webqq->write_file(C_PATH.'/logs/msg.txt', "--> ".$msg . " ==> ".  $reply."\r\n", "a+");

            if($val['poll_type'] == 'group_message'){
                $sendmsg = $webqq->send_qun_msg($val['value']['from_uin'], $reply, $cookie['clientid'], $cookie['psessionid']);
            }else if($val['poll_type'] == 'message'){
                $sendmsg = $webqq->send_buddy_msg($val['value']['from_uin'], $reply, $cookie['clientid'], $cookie['psessionid']);
            }
            echo '[' . date("Y-m-d H:i:s",time()) . '] 回复成功!' . "\n";

            if(similar_word($msg, $msg_out)){
                setcookie('online_status', 'no', time() + 36000);
            }
        }
    }
}

echo '[' . date("Y-m-d H:i:s",time()) . '] 登录中...' . "\n";

$uin = '12345678'; // 帐号
$pwd = '12345678'; // 密码

$webqq = new PHPWebQQ($uin, $pwd);
$cookie = $webqq->get_cookie();
$cookie['clientid'] = mt_rand(20000000,88888888);

if(empty($cookie['ptwebqq'])){
    $login = $webqq->qq_login();
    if($login == 'verify'){
        $webqq->get_verify_code($uin);
        $verify_code = trim(fgets(STDIN));
        if($webqq->qq_login($verify_code)){
            $cookie = $webqq->get_cookie();
            echo '[' . date("Y-m-d H:i:s",time()) . '] 登录成功!' . "\n";
        }
    }else{
        $cookie = $webqq->get_cookie();
        echo '[' . date("Y-m-d H:i:s",time()) . '] 登录成功!' . "\n";
    }
}else{
    if(empty($cookie['psessionid'])){
        $online = $webqq->qq_online($cookie['ptwebqq'], $cookie['clientid']);
        $cookie += $online;
        echo '[' . date("Y-m-d H:i:s",time()) . '] 上线成功!' . "\n";
    }
}
while_poll(true);



