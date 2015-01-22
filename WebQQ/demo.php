#!/usr/bin/php
<?php
/**
 *
 * PHP WEB QQ [演示]
 *
 * 发布地址    http://www.yuxiaoxi.com/2015-01-22-php-web-qq.html
 * 源码获取    https://github.com/maicong/OpenAPI/tree/master/WebQQ
 * @author     MaiCong <admin@maicong.me>
 * @date       2015-01-22 17:35:26
 * @package    webqq
 * @version    0.2.3 alpha
 *
 */

if (php_sapi_name() != "cli") exit ('[' . date("Y-m-d H:i:s",time()) . '] 请在终端(Terminal)中运行!' . "\n");

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
// 提取中文
function getChinese($string) {
    $tmpstr = '';
    $strlen = strlen($string);
    for($i=0; $i<$strlen; $i++) {       
        if(ord(substr($string, $i, 1)) > 0xA0) {
            $tmpstr.= substr($string, $i, 3);
            $i = $i+2;
        }
    }
    return $tmpstr;
}

// 自动回复
function auto_reply($str){
    $answer = http_post('www.niurenqushi.com/app/simsimi/ajax.aspx', array('txt'=>$str));
    $answer = clearhtml((string)$answer);
    $answer = preg_replace('/(机器人|小黄鸡|小九|图灵机器人)/isu', '麦葱酱', $answer);
    $answer = preg_replace('/(未找出结果，已反馈给客服美眉，敬请期待！|主淫出去泡妹妹了！回来再答你。|亲爱的，不明白你是什么意思，麻烦说的简单明了一点点……)/isu','', $answer);
    if(empty($answer)){
        return '';
    }
    return $answer;
}

// 比较相似度
function similar_word($word1, $word2){
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
    return $similarity;
}

//匹配url
function match_url($url){
    if(preg_match('/^((http|ftp|https):\/\/)?[\w-_.]+(\/[\w-_]+)*\/?$/is', trim($url), $match)){
        $host = parse_url($match[0]);
        if(empty($host['scheme'])){
            return 'http://' . $host['path'];
        }
        $site = $host['scheme'] . "://" . $host['host'];
        return $site;
    }else{
        return false;
    }
}

//获取url信息
function url_result($url){
    if(!match_url($url)) return false;
    $res = http_post('http://guanjia.qq.com/tapi/url_query.php',array('content'=> $url.'^0'));
    $res = ltrim($res,'url_query(');
    $res = rtrim($res,')');
    $json = json_decode($res, true);
    $data = $json['results'][0];
    if($data['Wording']){
        return '网址信息: ' . $data['url'] . ', ' . $data['Wording'];
    }
    if($data['Orgnization']){
        return '网址信息: ' . $data['url'] . ', ' . $data['Orgnization'] . ', ' . $data['ICPSerial'];
    }
    return false;
}

// 获取$_COOKIE
function _cookie($key) {
    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
}

//数组转字符串
function array2string($array) {
    if (is_array($array)) {
        return implode(',', array_map('array2string', $array));
    }
    return $array;
}

// 轮询
function while_poll($runing = true){
    global $webqq, $cookie;
    static $run_num = 1;
    $from_uin_file = C_PATH . '/cookie2/from_uin_';
    while($runing){

        sleep(3); // 睡3秒，不然进程卡死

        echo '[' . date("Y-m-d H:i:s",time()) . '] 正在检测新消息(' . $run_num . ')...' . "\n";

        $poll = $webqq->poll($cookie['ptwebqq'], $cookie['clientid'], $cookie['psessionid']);
        $run_num++;

        if(empty($poll)) continue;

        echo '[' . date("Y-m-d H:i:s",time()) . '] 收到新消息(' . count($poll) . ')!' . "\n";

        foreach ($poll as $val) {

            sleep(3); // 睡3秒，不然发送频率太高发不出去

            $msg = $val['value']['content'][1];
            
            if(empty($msg)) {
                echo '[' . date("Y-m-d H:i:s",time()) . '] 消息为空, 跳过.' . "\n";
                continue;
            }

            if(is_array($msg)) {
                echo '[' . date("Y-m-d H:i:s",time()) . '] 消息为图片, 跳过.' . "\n";
                continue;
            }

            $msg = preg_replace('/(机器人|小黄鸡|小九|图灵机器人)/isu','麦葱酱', $msg);

            $from_uin = $val['value']['from_uin'];

            echo '[' . date("Y-m-d H:i:s",time()) . '] 内容: ' . $msg . "\n";

            $msg_out = array('麦葱酱再见', '麦葱酱退下', '麦葱酱闭嘴', '麦葱酱好吵', '麦葱酱下线', '麦葱酱滚蛋');
            $msg_in = array('麦葱酱回来', '麦葱酱过来', '麦葱酱粗来', '麦葱酱说话', '麦葱酱上线', '麦葱酱我爱你');
            $msg_out_sw = similar_word($msg, $msg_out);
            $msg_in_sw = similar_word($msg, $msg_in);

            if($msg_out_sw >= 80 || $msg_in_sw >= 80) {
                if($msg_out_sw >= $msg_in_sw) {
                    echo '[' . date("Y-m-d H:i:s",time()) . '] 指令匹配成功, 准备下线.' . "\n";
                    $reply = '拜拜啦~ 叫我出来请说: ' . implode(', ', $msg_in);
                    $webqq->write_file($from_uin_file . $from_uin, 'ready');
                }else{
                    echo '[' . date("Y-m-d H:i:s",time()) . '] 指令匹配成功, 准备上线.' . "\n";
                    $reply = '我来了！叫我滚蛋请说: ' . implode(', ', $msg_out);
                    $webqq->write_file($from_uin_file . $from_uin, 'online');
                }
            }else{
                if($msg_url = match_url($msg)){
                    $reply = url_result($msg_url);
                }else{
                    $reply = auto_reply($msg);
                }
                if(empty($reply)) {
                    echo '[' . date("Y-m-d H:i:s",time()) . '] 没有对应回复信息, 跳过.' . "\n";
                    continue;
                }
            }

            if($webqq->read_file($from_uin_file . $from_uin) == 'offline'){
                echo '[' . date("Y-m-d H:i:s",time()) . '] 该会话已设为不回复, 跳过.' . "\n";
                continue;
            }

            echo '[' . date("Y-m-d H:i:s",time()) . '] 回复: ' . $reply . "\n";

            $webqq->write_file(C_PATH.'/logs/msg.txt', "--> ".$msg . " ==> ".  $reply."\r\n", "a+");

            if($val['poll_type'] == 'group_message'){
                $sendmsg = $webqq->send_qun_msg($val['value']['from_uin'], $reply, $cookie['clientid'], $cookie['psessionid']);
            }else if($val['poll_type'] == 'message'){
                $sendmsg = $webqq->send_buddy_msg($val['value']['from_uin'], $reply, $cookie['clientid'], $cookie['psessionid']);
            }
            echo '[' . date("Y-m-d H:i:s",time()) . '] 回复成功!' . "\n";

            if($webqq->read_file($from_uin_file . $from_uin) == 'ready'){
                $webqq->write_file($from_uin_file . $from_uin, 'offline');
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



