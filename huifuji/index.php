<?php
ignore_user_abort(1);
set_time_limit(0);
$interval=15;

define('C_PATH', rtrim(str_replace("\\", "/", dirname(__FILE__)), '/'));

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

function cookie($str) {
    return isset($_COOKIE[$str])?$_COOKIE[$str]:null;
}

function read_file($path, $key) {
    if(!file_exists($path)){
        return false;
    }

    $str = file_get_contents($path);

    if(strpos($str, $key) !== false){
        return true;
    }else{
        return false;
    }
}

function http_post($url, $param = '', $cookie = true, $header = true, $domain = true) {
    $host = parse_url($url);
    $site = $host['scheme']."://".$host['host'];
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
        if(read_file(cookie('cookie_jar'), 'ffq__user_login') && $domain){
            curl_setopt($ch, CURLOPT_COOKIEFILE, cookie('cookie_jar'));
        } else {
            curl_setopt($ch, CURLOPT_COOKIEFILE, '');
        }
    } else {
        deldir('cookie');
        $cookie_jar = tempnam('cookie', 'cookie');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        setcookie('cookie_jar', $cookie_jar);
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

function post_answer($id, $title){

    sleep($interval);

    if(!read_file(C_PATH.'/answer.txt', '['.$id.']')){   

        write_file(C_PATH.'/answer.txt', '['.$id.']', 'a+');

        $question = http_post('http://www.wsidea.com/question/'.$id, '', true, false);
        preg_match ('/<input\stype="hidden"\sname="post_hash"\svalue="(\w+)"/iu', $question['content'], $post_hash);

        $getArr = array(
            'txt'=>$title
        );

        $answer = http_post('http://www.niurenqushi.com/app/simsimi/ajax.aspx', $getArr, true, false, false);
        $answer = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/iu', '', $answer);
        $answer = str_replace('小黄鸡','回复姬',$answer);

        $answer = empty($answer)?'你说的好友道理，我竟无言以对！':$answer;

        $ansArr = array(
            'question_id'=>$id,
            'post_hash'=>$post_hash[1],
            'answer_content'=>$answer['content']
        );

        $post_answer = http_post('http://www.wsidea.com/question/ajax/save_answer/', $ansArr, true, false);

        preg_match ('/"errno":([\d\-]*?),"err":(.*?)}/i', $post_answer['content'], $status);

        if($status[1] == 1){
            return '回复成功';
        } else {
            return json_decode($content[2]);
        }
    } else{
        return '已经回复了';
    }
}

function get_login(){
    if(read_file(cookie('cookie_jar'),'ffq__user_login')){
        return 'logged';
        exit();
    }

    $vars = array(
        'user_name' => 'admin',
        'password' => 'daydayupwsidea',
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

function get_public(){
    $url = 'http://www.wsidea.com/home/ajax/index_actions/page-0__filter-public';
    $url2 = 'http://www.wsidea.com/home/ajax/index_actions/page-1__filter-public';
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
        preg_match_all ('/<h4><a\shref="http:\/\/www\.wsidea\.com\/question\/(\d+)">(.*?)<\/a><\/h4>/iu', $public['content'], $match, PREG_SET_ORDER);

        $last_id = intval(file_get_contents(C_PATH.'/lastid.txt'));

        write_file(C_PATH.'/lastid.txt', $match[0][1], 'w');

        if($match[0][1] == $last_id){
            echo '还没有发现新内容';
            exit();
        }

        $count = count($match);
        $i = 0;
        foreach ($match as $key => $value) {
            echo $key.' '.$value[2].':'.post_answer($value[1], $value[2])."<br>";
        }
    }
}

get_public();

