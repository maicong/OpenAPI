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

//$friend_list = $webqq->get_user_friends($uin, $cookie['ptwebqq']);
// $fuid = $friend_list['info'][0]['uin'];
//$qun_list = $webqq->get_group_name_list_mask($uin, $cookie['ptwebqq']);
// $quid = $friend_list['gnamelist'][1]['gid'];

// print_r($cookie);
// print_r($friend_list);
// print_r($qun_list);
// $sendmsg = $webqq->send_buddy_msg('2415586530', "哈哈哈，搞siao哦", $cookie['clientid'], $cookie['psessionid']);
// print_r($sendmsg);
while(true){
    sleep(3); // 3秒睡死撒过
    $poll = $webqq->poll($cookie['ptwebqq'], $cookie['clientid'], $cookie['psessionid']);

    foreach ($poll as $val) {
        if($val['poll_type'] == 'group_message'){
            // if(in_array(array('麦葱'),$val['value']['content'][1])){}
            $sendmsg = $webqq->send_qun_msg($val['value']['from_uin'], "麦葱情报机器人已将此消息暂存，等待主人查看。", $cookie['clientid'], $cookie['psessionid']);
        }else if($val['poll_type'] == 'message'){
            $sendmsg = $webqq->send_buddy_msg($val['value']['from_uin'], "好的，麦葱情报机器人已将此消息暂存，等待主人查看。", $cookie['clientid'], $cookie['psessionid']);
        }
       
    }
}




// $sendmsg = $webqq->send_buddy_msg('2415586530', "哈哈哈，搞siao哦", $cookie['clientid'], $cookie['psessionid']);
// $sendqunmsg = $webqq->send_qun_msg('2558014021', "什么情况怎么发不出去了", $cookie['clientid'], $cookie['psessionid']);
// print_r($sendmsg);
// print_r($sendqunmsg);
