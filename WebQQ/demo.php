<?php
/**
 *
 * PHP WEB QQ [演示]
 *
 * @author     MaiCong <admin@maicong.me>
 * @date  2015-01-21 16:52:17
 * @package    webqq
 * @version    0.1.0 alpha
 *
 */

header('Content-Type:text/html; charset=utf-8');
require_once 'webqq.class.php';

$uin = '12345678'; // 帐号
$pwd = '12345678'; // 密码

$webqq = new PHPWebQQ($uin, $pwd);
$cookie = $webqq->get_cookie();
$cookie['clientid'] = mt_rand(20000000,88888888);
if(empty($cookie['ptwebqq'])){
    if($webqq->qq_login()){
        $cookie = $webqq->get_cookie();
    }
}else{
    if(empty($cookie['psessionid'])){
        $online = $webqq->qq_online($cookie['ptwebqq'], $cookie['clientid']);
        $cookie += $online;
    }
}
// 抓取内容
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
// object转array
function object2array($obj){
    $_arr = is_object($obj)?get_object_vars($obj):$obj;
    foreach ($_arr as $key => $val){
        $val=(is_array($val)) || is_object($val)?object2array($val):$val;
        $arr[$key] = $val;
    }
    return $arr; 
}
// 清除HTML标记
function clear_html($str) {
    $str = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $str);
    $str = preg_replace("/\<[a-z]+(.*)\>/iU", "", $str);
    $str = preg_replace("/\<\/[a-z]+\>/iU", "", $str);
    $str = str_replace(array(' ','  ', chr(13), chr(10), '&nbsp;'), array('', '', '', '', ''), $str);
    return $str;
}
//自动回复
function auto_reply($str){
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
    return clear_html($anscont);
}

while(true){
    sleep(3); // 3秒睡死撒过
    $poll = $webqq->poll($cookie['ptwebqq'], $cookie['clientid'], $cookie['psessionid']);
    if(empty($poll)) continue;
    foreach ($poll as $val) {
        $cont = auto_reply($val['value']['content'][1]);
        if($val['poll_type'] == 'group_message'){
            $sendmsg = $webqq->send_qun_msg($val['value']['from_uin'], $cont, $cookie['clientid'], $cookie['psessionid']);
        }else if($val['poll_type'] == 'message'){
            $sendmsg = $webqq->send_buddy_msg($val['value']['from_uin'], $cont, $cookie['clientid'], $cookie['psessionid']);
        }
       
    }
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
// while(true){
//     sleep(3); // 3秒睡死撒过
//     $poll = $webqq->poll($cookie['ptwebqq'], $cookie['clientid'], $cookie['psessionid']);

//     foreach ($poll as $val) {
//         if($val['poll_type'] == 'group_message'){
//             // if(in_array(array('麦葱'),$val['value']['content'][1])){}
//             $sendmsg = $webqq->send_qun_msg($val['value']['from_uin'], "麦葱情报机器人已将此消息暂存，等待主人查看。", $cookie['clientid'], $cookie['psessionid']);
//         }else if($val['poll_type'] == 'message'){
//             $sendmsg = $webqq->send_buddy_msg($val['value']['from_uin'], "好的，麦葱情报机器人已将此消息暂存，等待主人查看。", $cookie['clientid'], $cookie['psessionid']);
//         }
       
//     }
// }

// $sendmsg = $webqq->send_buddy_msg('2415586530', "哈哈哈，搞siao哦", $cookie['clientid'], $cookie['psessionid']);
// $sendqunmsg = $webqq->send_qun_msg('2558014021', "什么情况怎么发不出去了", $cookie['clientid'], $cookie['psessionid']);
// print_r($sendmsg);
// print_r($sendqunmsg);
