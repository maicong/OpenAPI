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
function http_get($url){
    $header = array(
        'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Encoding:gzip,deflate,sdch',
        'Accept-Language:zh-CN,zh;q=0.8',
        'Cache-Control:no-cache',
        'Connection:keep-alive',
        'Cookie:JSESSIONID=aaaV-nxKGQ-kr_v4LUlSu',
        'Host:www.tuling123.com',
        'Pragma:no-cache',
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36'
    );
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30秒超时
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 返回原网页
    curl_setopt($ch, CURLOPT_HTTPHEADER , $header);
    curl_setopt($ch, CURLOPT_REFERER , 'http://www.tuling123.com/openapi/cloud/proexp.jsp');
    $response =  curl_exec($ch);
    curl_close($ch);
    return $response;
}
// object转arrat
function object2array($obj){
    $_arr = is_object($obj)?get_object_vars($obj):$obj;
    foreach ($_arr as $key => $val){
        $val=(is_array($val)) || is_object($val)?object2array($val):$val;
        $arr[$key] = $val;
    }
    return $arr; 
}
// 清除HTML标记
function clearhtml($str) {
    $str = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $str);
    $str = preg_replace("/\<[a-z]+(.*)\>/iU", "", $str);
    $str = preg_replace("/\<\/[a-z]+\>/iU", "", $str);
    $str = str_replace(array(' ','  ', chr(13), chr(10), '&nbsp;'), array('', '', '', '', ''), $str);
    return $str;
}
function zdhf($str){
    $tlinfo = http_get('http://www.tuling123.com/openapi/productexp.do?info='.$str);
    $answer = simplexml_load_string($tlinfo, 'SimpleXMLElement', LIBXML_NOCDATA);
    switch ($answer->MsgType) {
        case 'text':
            $anscont = $answer->Content;
            break;
        case 'news':
            $anscontArr = object2array($answer->Articles);
            $anscont = '';
            foreach ($anscontArr['item'] as $key =>$value) {
                $value['Title'] = preg_replace('/<br>/',' ',$value['Title']);
                $anscont .= $value['Title'].' ';
            }
            break;
        default:
            $anscont = '...马蛋卡壳了，报时算了。现在是'.date('Y-m-d H:i:s', time());
            break;
    }
    if($anscont == "亲爱的，不明白你是什么意思，麻烦换一种说法" || $anscont == "n . 数组，阵列；排列，列阵；大批，一系列；衣服<br>vt . 排列，部署；打扮") {
        $anscont = '...~';
    }
    $anscont = str_replace('机器人','麦葱酱',$anscont);
    $anscont = str_replace('小黄鸡','麦葱酱',$anscont);
    $anscont = str_replace('图灵','麦葱酱',$anscont);
    return clearhtml($anscont);
}
//$friend_list = $webqq->get_user_friends($uin, $cookie['ptwebqq']);
// $fuid = $friend_list['info'][0]['uin'];
//$qun_list = $webqq->get_group_name_list_mask($uin, $cookie['ptwebqq']);
// $quid = $friend_list['gnamelist'][1]['gid'];

// print_r($cookie);
// print_r($friend_list);
// print_r($qun_list);
// $sendmsg = $webqq->send_buddy_msg('2415586530', "哈哈哈，搞siao哦", $cookie['clientid'], $cookie['psessionid']);
// print_r($sendmsg);
static $run_num = 1;
while(true){
    sleep(3);
    echo "--------> 正在检测新消息 ({$run_num}) \n";
    $poll = $webqq->poll($cookie['ptwebqq'], $cookie['clientid'], $cookie['psessionid']);
    $run_num++;
    if(empty($poll)) continue;
    foreach ($poll as $val) {
        $cont = zdhf($val['value']['content'][1]);
        echo "-----------> 获取到新消息: {$val['value']['content'][1]} \n";
        $webqq->write_file(C_PATH.'/logs/msg.txt', "-->".$val['value']['content'][1] . "=->".  $cont."\r\n");
        if($val['poll_type'] == 'group_message'){
            $sendmsg = $webqq->send_qun_msg($val['value']['from_uin'], $cont, $cookie['clientid'], $cookie['psessionid']);
        }else if($val['poll_type'] == 'message'){
            $sendmsg = $webqq->send_buddy_msg($val['value']['from_uin'], $cont, $cookie['clientid'], $cookie['psessionid']);
        }
        echo "-----------> 回复成功: {$cont} \n";
    }
}




// $sendmsg = $webqq->send_buddy_msg('2415586530', "哈哈哈，搞siao哦", $cookie['clientid'], $cookie['psessionid']);
// $sendqunmsg = $webqq->send_qun_msg('2558014021', "什么情况怎么发不出去了", $cookie['clientid'], $cookie['psessionid']);
// print_r($sendmsg);
// print_r($sendqunmsg);
