<?php
/**
 * wsidea 回复姬
 * @authors MaiCong (sb@yxx.me)
 * @date    2014-08-08 19:32:16
 * @version 0.2
 * 
 */

// 声明编码
header('Content-Type:text/html; charset=utf-8');

// 定义路径
define('C_PATH', rtrim(str_replace("\\", "/", dirname(__FILE__)), '/'));

// 删除路径下所有文件
function deldir($dir) {
    $dh=opendir($dir); 
    while ($file=readdir($dh)) { 
        if($file!="." && $file!="..") { 
            $fullpath=$dir."/".$file; 
            if(!is_dir($fullpath)) { 
                unlink($fullpath); 
            } else { 
                deldir($fullpath); 
            } 
        } 
    } 
    closedir($dh);
}

// 写文件
function write_file($path, $data, $mode = "rb+", $chmod = 0777) {
    @touch($path);

    if (!$handle = @fopen($path, $mode)) {
        return FALSE;
    }

    @flock($handle, LOCK_EX);
    @fwrite($handle, $data);

    if ($mode == "rb+") {
        @ftruncate($handle, strlen($data));
    }

    @flock($handle, LOCK_UN);
    @fclose($handle);
    @chmod($path, $chmod);

    return TRUE;
}

// 读文件或判断文件内容
function read_file($path, $key = '') {
    if(!file_exists($path)){
        return false;
    }

    $str = file_get_contents($path);

    if(empty($key)){
        return $str;
    }else{
        if(strpos($str, $key) !== false){
            return true;
        }else{
            return false;
        }
    }
}

// get方法
function get($str) {
    return isset($_GET[$str])?$_GET[$str]:null;
}

// curl post
function http_post($url, $param = '', $cookie = true, $header = true, $domain = true) {
    $host = parse_url($url);
    $site = $host['scheme']."://".$host['host'];
    $cookie_jar = C_PATH.'/cookie/iamhfj.php';

    $ch = curl_init();

    if (is_string($param)) {
        $post = $param;
    } else {
        $pArr = array();
        foreach ($param as $key => $val) {
            $pArr[] = $key."=".urlencode($val);
        }
        $post = join("&", $pArr);
    }

    if (stripos($url, "https://") !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//禁用验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    if($cookie) {
        if(read_file($cookie_jar, 'ffq__user_login') && $domain){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        } else {
            curl_setopt($ch, CURLOPT_COOKIEFILE, '');
        }
    } else {        
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        write_file($cookie_jar, $cookie_jar, 'w');
    }

    if($header){
        curl_setopt($ch, CURLOPT_HEADER, true);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_REFERER, $site);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:1.2.4.8', 'X-FORWARDED-HOST:'.$host['host'], 'X-FORWARDED-SERVER:'.$host['host']));//
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $content = curl_exec($ch);

    preg_match ("/HTTP\/1\.1\s(\d+)\s/i", $content, $status);

    curl_close($ch);

    return array('status'=>$status[1], 'content'=>$content);
}

// curl get
function http_get($url){
    $header = array(
        'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Encoding:gzip,deflate,sdch',
        'Accept-Language:zh-CN,zh;q=0.8',
        'Cache-Control:no-cache',
        'Connection:keep-alive',
        'Cookie:JSESSIONID=aaaj3ErcPp8yVdgCD2ZEu',
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

// 提交回复
function post_answer($type, $id, $title, $user){

    // 小黄鸡
    // $answer = http_post('http://www.niurenqushi.com/app/simsimi/ajax.aspx', $getArr, true, false, false);
    // $anscont = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/iu', '', strip_tags($answer['content']));
    // $anscont = str_replace('小黄鸡','回复姬',$anscont);
    // $anscont = empty($anscont)?'你说的好友道理，我竟无言以对！':$anscont;

    // 图灵机器人
    $tlinfo = http_get('http://www.tuling123.com/openapi/productexp.do?info='.$title);
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
            $anscont = '阁下说的好有道理！我竟无言以对！';
            break;
    }

    if(read_file(C_PATH.'/'.$type.'.txt', '['.$id.']')){
        return '已经回复了';
    }else{
        switch ($type) {
            case 'question':
                $question = http_post('http://www.wsidea.com/question/'.$id, '', true, false);
                preg_match ('/<input\stype="hidden"\sname="post_hash"\svalue="(\w+)"/iu', $question['content'], $post_hash);
                $ansArr = array(
                    'question_id'=>$id,
                    'post_hash'=>$post_hash[1],
                    'answer_content'=>$anscont
                );
                $post_answer = http_post('http://www.wsidea.com/question/ajax/save_answer/', $ansArr, true, false);
                break;
            case 'comment':
                $ansArr = array(
                    '_post_type'=>'ajax',
                    'message'=>'@'.$user.': '.$anscont
                );
                $post_answer = http_post('http://www.wsidea.com/question/ajax/save_answer_comment/answer_id-'.$id, $ansArr, true, false);
                break;
        }

        write_file(C_PATH.'/'.$type.'.txt', '['.$id.']', 'a+');

        preg_match ('/"errno":([\d\-]*?),"err":(.*?)}/i', $post_answer['content'], $status);

        if($status[1] == 1){
            return '回复成功';
        } else {
            return json_decode($content[2]);
        }
    }
}

// 登录
function get_login(){
    if(read_file(C_PATH.'/cookie/iamhfj.php','ffq__user_login')){
        return 'logged';
        exit();
    }

    $vars = array(
        'user_name' => 'admin',
        'password' => '**************',
        'net_auto_login' => '1'
    );

    $login = http_post('http://www.wsidea.com/account/ajax/login_process/', $vars, false);

    preg_match ('/"errno":([\d\-]*?),"err":(.*?)}/i', $login['content'], $content);

    if($content[1] == 1){
        return 'logged';
    } else {
        return json_decode($content[2]);
    }
}

// 获取内容并回复
function get_public($type){
    switch ($type) {
        case 'question':
            $url = 'http://www.wsidea.com/home/ajax/index_actions/page-0__filter-public';
            $url2 = 'http://www.wsidea.com/home/ajax/index_actions/page-1__filter-public';
            break;
        case 'comment':
            $url = 'http://www.wsidea.com/notifications/ajax/list/__page-0';
            $url2 = 'http://www.wsidea.com/notifications/ajax/list/__page-1';
            break;
    }
    $public = http_post($url);
    if ($public['status'] != 200) {
        $login = get_login();
        if ($login === 'logged'){
            $public = http_post($url);
            echo '回复姬登录中';
        } else {
            echo '回复姬登录失败，原因：'.$login;
        }
    } else {
        $public2 = http_post($url2);
        $public['content'] .= $public2['content'];

        switch ($type) {
            case 'question':
                preg_match_all ('/<h4><a\shref="http:\/\/www\.wsidea\.com\/question\/(\d+)">(.*?)<\/a><\/h4>/iu', $public['content'], $match, PREG_SET_ORDER);
                foreach ($match as $key => $value) {
                    $mtArr[$key] = array($value[1],$value[2],'');
                }
                break;
            case 'comment':
                preg_match_all ('/<a\shref="http:\/\/www\.wsidea\.com\/people\/(.*?)">(.*?)<\/a>\s(.*?)\s<a\shref="http:\/\/www\.wsidea\.com\/question\/(.*?)answer_(\d+)">(.*?)<\/a>/iu', $public['content'], $match, PREG_SET_ORDER);
                foreach ($match as $key => $value) {
                    $mtArr[$key] = array($value[5],$value[6],$value[2]);
                }
                break;   
        }
        
        $last_id = read_file(C_PATH.'/last_'.$type.'_id.txt');

        if($mtArr[0][0] == $last_id){
            echo '还没有发现新内容 '.date('Y-m-d H:i:s',time());
            exit();
        }

        write_file(C_PATH.'/last_'.$type.'_id.txt', $mtArr[0][0], 'w');

        $i = 0;
        $count = count($mtArr);
        while ($i < $count) {
            $anss = post_answer($type, $mtArr[$i][0], $mtArr[$i][1], $mtArr[$i][2]);
            echo $i.' '.$mtArr[$i][1].':'.$anss."<br>";
            $i++;
        }
    }
}

// 干！
switch (get('do')) {
    case 'comment':
        get_public('comment');
        break;
    case 'question':
        get_public('question');
        break;
    default:
        echo '<a href="?do=question">回复前两页新问题</a> <a href="?do=comment">回复前两页新通知</a>';
        break;
}