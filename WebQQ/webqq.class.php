<?php
/**
 *
 * PHP WEB QQ
 *
 * @author     MaiCong <admin@maicong.me>
 * @date  2015-01-22 13:21:21
 * @package    webqq
 * @version    0.2 alpha
 *
 */

class PHPWebQQ
{
    private $uid;
    private $pwd;
    protected $bkn;
    protected $c_path;
    protected $cookie_file;
    protected $verify_file;
    public $cookies = array();
    public function __construct($uid, $pwd, $c_path = '')
    {
        if(!$uid || !$pwd) exit('[' . date("Y-m-d H:i:s",time()) . '] 帐号和密码呢？' . "\n");
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->c_path = ($c_path) ? rtrim($c_path, '/') : rtrim(str_replace("\\", "/", dirname(__FILE__)), '/');
        $this->cookie_file = $this->c_path . '/cookie/qq_cookie.tmp';
        $this->verify_file = $this->c_path . '/cookie/qq_verify.jpg';
        $this->cookies = $this->get_cookie($this->cookie_file);
    }
    /**
     * 写日志
     * @param $level
     * @param $msg
     */
    public function write_log($level = 'error', $msg)
    {
        $level    = strtoupper($level);
        $filepath = $this->c_path . '/logs/log-' . date('Y-m-d') . '.php';
        $message  = '';
        if (!file_exists($filepath)) {
            $message .= "DEBUG LOG\n\n";
        }
        if (!$fp = fopen($filepath, "ab")) {
            return false;
        }
        $message .= $level . ' ' . (($level == 'INFO') ? ' -' : '-') . ' ' . date("Y-m-d H:i:s") . ' --->' . $msg . "\n";
        return $this->write_file($filepath, $message, 'ab', 0666);
    }
    /**
     * 写文件
     * @param $path
     * @param $data
     * @param $mode
     * @param $chmod
     */
    public function write_file($path, $data, $mode = 'rb+', $chmod = 0777)
    {
        @touch($path);
        if (!$fp = fopen($path, $mode)) {
            return false;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        if ($mode == "rb+") {
            @ftruncate($fp, strlen($data));
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($path, $chmod);
        return true;
    }
    /**
     * 读文件
     * @param $path
     * @param $key
     * @return mixed
     */
    public function read_file($path, $key = '')
    {
        if (!file_exists($path)) {
            return false;
        }
        if (function_exists('file_get_contents')) {
            $data = file_get_contents($path);
        } else {
            if (!$fp = fopen($path, 'rb')) {
                return false;
            }
            flock($fp, LOCK_SH);
            $data = '';
            if (filesize($path) > 0) {
                $data = &fread($fp, filesize($path));
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        if (empty($key)) {
            return $data;
        } else {
            if (strpos($data, $key) !== false) {
                return true;
            } else {
                return false;
            }
        }
    }
    /**
     * 获取cookie
     * @param $cookie
     * @return mixed
     */
    public function get_cookie($cookie_file = null) {
        if (!$cookie_file) {
            $cookie_file = $this->cookie_file;
        }
        if (!file_exists($cookie_file)) {
            return false;
        }
        $cookies = file($cookie_file);
        $datas = array();
        $data = array();
        foreach ($cookies as $v) {
            if (preg_match("/(.*)\t(.*)\t(.*)\t(.*)\t(.*)\t(.*)\t(.*)\n/U", $v, $p)) {
                $datas[] = array_slice($p, 1);
            }
        }
        if (empty($datas)) {
            return false;
        }
        foreach ($datas as $v) {
            if(!$v[6]) continue;
            $data[$v[5]] = $v[6];
        }
        return $data;
    }
    /**
     * 获取内容
     * @param $url
     * @param $param
     * @param $referer
     * @param $follow
     * @return mixed
     */
    public function get_conts($url, $param = '', $referer = '', $follow = false)
    {
        $ch   = curl_init();
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
        if (stripos($url, "https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        if (!empty($param)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if (empty($referer)) {
            $referer = $site;
        }
        if ($follow) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        } else {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:1.2.4.8', 'X-FORWARDED-HOST:' . $host, 'X-FORWARDED-SERVER:' . $host));
        $content = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header     = substr($content, 0, $headerSize);
            $body       = substr($content, $headerSize);
        }
        curl_close($ch);
        $response['head'] = $header;
        $response['body'] = $body;
        return $response;
    }
    /**
     * 登录
     * @param $uid
     * @param $pwd
     */
    public function qq_login($verify = null)
    {
        $ckData = $this->get_conts('https://ssl.ptlogin2.qq.com/check?uin=' . $this->uid . '&appid=501004106&ptlang=2052&js_type=2&js_ver=10009');
        preg_match_all("@'([^']+)'@", $ckData['body'], $ckMatch);
        $ckCode = $ckMatch[1][1];
        $ckMsg  = $ckMatch[1][2];
        if ($ckMatch[1][0] && !$verify) {
            $this->write_log('error', '登陆失败, 需要验证码.');
            return 'verify';
        }
        if($verify){
            $passwd = $this->qq_pwd($this->pwd, $ckMsg, $ckCode);
        }else{
            $passwd = $this->qq_pwd($this->pwd, $ckMsg, $verify);
        }
        $url    = 'http://ptlogin2.qq.com/login?u=' . $this->uid . '&p=' . $passwd . '&verifycode=' . $ckCode . '&webqq_type=10&remember_uin=1&login2qq=1&aid=501004106&u1=http%3A%2F%2Fweb.qq.com%2Floginproxy.html%3Flogin2qq%3D1%26webqq_type%3D10&h=1&ptredirect=0&ptlang=2052&daid=164&from_ui=1&pttype=1&dumy=&fp=loginerroralert&action=0-25-28303&mibao_css=m_webqq&t=1&g=1&js_type=0&js_ver=10111';
        $referer = 'https://ui.ptlogin2.qq.com/cgi-bin/login?daid=164&target=self&style=16&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http%3A%2F%2Fw.qq.com%2Fproxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001';
        $qqData = $this->get_conts($url, '', $referer);
        preg_match("/ptuiCB\('(.*)','(.*)','(.*)','(.*)','(.*)',\s'(.*)'\);/U", $qqData['body'], $qqDataMatch);
        if ($qqDataMatch['5'] == "登录成功！") {
            $this->get_conts($qqDataMatch['3']);
            $this->write_log('debug', '登陆成功: ' . $qqDataMatch['6']);
            return 'ok';
        } else {
            $this->write_log('error', '登陆失败: ' . $qqDataMatch['5']);
            exit('[' . date("Y-m-d H:i:s",time()) . '] 登陆失败, 错误提示: ' . $qqDataMatch['5'] . "\n");
        }
    }
    /**
     * 上线
     * @param $ptwebqq
     * @param $clientid
     */
    public function qq_online($ptwebqq, $clientid)
    {
        $url   = "http://d.web2.qq.com/channel/login2";
        $param = array(
            "r" => json_encode(array(
                "ptwebqq"    => $ptwebqq,
                "clientid"   => $clientid,
                "psessionid" => '',
                "status"     => "online",
            )),
        );
        $referer   = 'http://d.web2.qq.com/proxy.html?v=20130916001&callback=1&id=2';
        $online_json = $this->get_conts($url, $param, $referer);
        $online_data = json_decode($online_json['body'], true);
        if (empty($online_data['result'])) {
            unlink($this->cookie_file);
            $this->cookies = array();
            $this->write_log('error', '上线失败: ' . $online_data['retcode']);
            exit('[' . date("Y-m-d H:i:s",time()) . '] 上线失败, 错误代码: ' . $online_data['retcode'] . "\n");
        }
        return $online_data['result'];
    }
    /**
     * 退出
     * @param $clientid
     * @param $psessionid
     */
    public function logout($clientid, $psessionid)
    {
        $url = 'http://d.web2.qq.com/channel/logout2?ids=&clientid=' . $clientid . '&psessionid=' . $psessionid . '&t=' . time();
        $referer   = 'http://d.web2.qq.com/proxy.html?v=20110331002&callback=1&id=3';
        $this->get_conts($url, '', $referer);
        $this->write_log('debug', '退出成功');
        unlink($this->cookie_file);
        $this->cookies = array();
        return true;
    }
    /**
     * 获取验证码
     * @param $psessionid
     * @param $clientid
     */
    public function get_verify_code($uin)
    {
        $url   = 'https://ssl.captcha.qq.com/getimage?aid=501004106&r=0.5275350357405841&uin=' . $uin;
        $referer   = 'https://ui.ptlogin2.qq.com/cgi-bin/login?daid=164&target=self&style=16&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http%3A%2F%2Fw.qq.com%2Fproxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001';
        $get_json = $this->get_conts($url, '', $referer);
        $get_data = json_decode($get_json['body'], true);
        if (!empty($get_data)) {
            $this->write_log('debug', '验证码获取成功');
            file_put_contents($this->verify_file, $get_data);
            echo '[' . date("Y-m-d H:i:s",time()) . '] 请打开' . $this->verify_file . '查看验证码并输入: ' . "\n";
        }else{
            $this->write_log('error', '验证码获取失败');
            exit('[' . date("Y-m-d H:i:s",time()) . '] 验证码获取失败' . "\n");
        }

    }
    /**
     * 获取消息
     * @param $psessionid
     * @param $clientid
     */
    public function poll($ptwebqq, $clientid, $psessionid)
    {
        $url   = "http://d.web2.qq.com/channel/poll2";
        $param = array(
            "r" => json_encode(array(
                "ptwebqq"    => $ptwebqq,
                "clientid"   => $clientid,
                "psessionid" => $psessionid,
                "key"     => "",
            )),
        );
        $referer   = 'http://d.web2.qq.com/proxy.html?v=20130916001&callback=1&id=2';
        $poll_json = $this->get_conts($url, $param, $referer);
        $poll_data = json_decode($poll_json['body'], true);
        if (!empty($poll_data['result'])) {
            $this->write_log('debug', '消息获取成功');
            return $poll_data['result'];
        }
    }
    /**
     * 发送用户消息
     * @param $from_uin
     * @param $msg
     * @param $psessionid
     * @param $clientid
     */
    public function send_buddy_msg($from_uin, $msg, $clientid, $psessionid, $facenum = -1)
    {
        static $msg_id = 16110002;
        $msg_id++;
        $url   = "http://d.web2.qq.com/channel/send_buddy_msg2";
        if($facenum > -1){
            $content = "[[\"face\",{$facenum}],\"{$msg}\",[\"font\",{\"name\":\"微软雅黑\",\"size\":12,\"style\":[0,0,0],\"color\":\"EC4C4C\"}]]";
        }else{
            $content = "[\"{$msg}\",[\"font\",{\"name\":\"微软雅黑\",\"size\":12,\"style\":[0,0,0],\"color\":\"EC4C4C\"}]]";
        }
        $param = array(
            "r" => json_encode(array(
                "to"         => $from_uin,
                "content"    => $content,
                "face"       => 522,
                "clientid"   => $clientid,
                "msg_id"     => $msg_id,
                "psessionid" => $psessionid,                
            )),
        );

        $referer  = 'http://d.web2.qq.com/proxy.html?v=20130916001&callback=1&id=2';
        $sendJson = $this->get_conts($url, $param, $referer);
        $sendData = json_decode($sendJson['body'], true);
        // if ($sendData['result'] != 'ok') {
        //     exit("消息发送失败, 错误代码: {$sendData['retcode']}");
        // }
        // $this->write_log('debug', '消息发送成功');
        return $sendData['result'];
    }
    /**
     * 发送群消息
     * @param $group_id
     * @param $msg
     * @param $psessionid
     * @param $clientid
     */
    public function send_qun_msg($group_id, $msg, $clientid, $psessionid, $facenum = -1)
    {
        static $msg_id = 17110002;
        $msg_id++;
        $url   = "http://d.web2.qq.com/channel/send_qun_msg2";
        if($facenum > -1){
            $content = "[[\"face\",{$facenum}],\"{$msg}\",[\"font\",{\"name\":\"微软雅黑\",\"size\":12,\"style\":[0,0,0],\"color\":\"EC4C4C\"}]]";
        }else{
            $content = "[\"{$msg}\",[\"font\",{\"name\":\"微软雅黑\",\"size\":12,\"style\":[0,0,0],\"color\":\"EC4C4C\"}]]";
        }
        $param = array(
            "r" => json_encode(array(
                "group_uin"  => $group_id,
                "content"    => $content,
                "face"       => 522,
                "clientid"   => $clientid,
                "msg_id"     => $msg_id,
                "psessionid" => $psessionid, 
            )),
        );
        $referer  = 'http://d.web2.qq.com/proxy.html?v=20130916001&callback=1&id=2';
        $sendJson = $this->get_conts($url, $param, $referer);
        $sendData = json_decode($sendJson['body'], true);
        // if ($sendData['result'] != 'ok') {
        //     exit("群消息发送失败, 错误代码: {$sendData['retcode']}");
        // }
        // $this->write_log('debug', '群消息发送成功');
        return $sendData['result'];
    }
    /**
     * 获取vfwebqq
     * @param $ptwebqq
     * @param $clientid
     */
    public function get_vfwebqq($ptwebqq, $clientid)
    {
        $url   = 'http://s.web2.qq.com/api/getvfwebqq?ptwebqq=' . $ptwebqq . '&clientid=' . $clientid . '&psessionid=&t=' . time();
        $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';
        $getJson = $this->get_conts($url, '', $referer);
        $getData = json_decode($getJson['body'], true);
        if (empty($getData['result'])) {
            exit('[' . date("Y-m-d H:i:s",time()) . '] vfwebqq 获取失败, 错误代码: ' . $getData['retcode']);
        }
        $this->write_log('debug', 'vfwebqq 获取成功');
        $this->cookies['vfwebqq'] = $getData['result']['vfwebqq'];
        return true;
    }
    /**
     * 获取好友列表
     * @param $uin
     * @param $vfwebqq
     */
    public function get_user_friends($uin, $vfwebqq)
    {
        $url   = "http://s.web2.qq.com/api/get_user_friends2";
        $hash  = $this->get_hash($uin, $vfwebqq);
        $param = array(
            "r" => json_encode(array(
                "vfwebqq" => $vfwebqq,
                "hash"    => $hash,
            )),
        );
        $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';
        $getJson = $this->get_conts($url, $param, $referer);
        $getData = json_decode($getJson['body'], true);
        if (empty($getData['result'])) {
            exit('[' . date("Y-m-d H:i:s",time()) . '] 获取好友列表失败, 错误代码: ' . $getData['retcode']);
        }
        $this->write_log('debug', '好友列表获取成功');
        return $getData['result'];
    }
    /**
     * 获取群列表
     * @param $uin
     * @param $vfwebqq
     */
    public function get_group_name_list_mask($uin, $vfwebqq)
    {
        $url   = "http://s.web2.qq.com/api/get_group_name_list_mask2";
        $hash  = $this->get_hash($uin, $vfwebqq);
        $param = array(
            "r" => json_encode(array(
                "vfwebqq" => $vfwebqq,
                "hash"    => $hash,
            )),
        );
        $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';
        $getJson = $this->get_conts($url, $param, $referer);
        $getData = json_decode($getJson['body'], true);
        if (empty($getData['result'])) {
            exit('[' . date("Y-m-d H:i:s",time()) . '] 获取群列表失败, 错误代码: ' . $getData['retcode']);
        }
        $this->write_log('debug', '群列表获取成功');
        return $getData['result'];
    }
     /**
     * 获取用户信息
     * @param $uin
     * @param $vfwebqq
     */
    public function get_friend_uin($uin, $vfwebqq, $clientid, $psessionid)
    {
        $url   = 'http://s.web2.qq.com/api/get_friend_info2?tuin=' . $uin . '&vfwebqq=' . $vfwebqq . '&clientid=' . $clientid . '&psessionid=' . $psessionid . '&t=' . time();
        $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';
        $getJson = $this->get_conts($url, '', $referer);
        $getData = json_decode($getJson['body'], true);
        if (empty($getData['result'])) {
            exit('[' . date("Y-m-d H:i:s",time()) . '] 获取用户信息失败, 错误代码: ' . $getData['retcode']);
        }
        $this->write_log('debug', '用户信息获取成功');
        return $getData['result'];
    }
    /**
     * 获取真·好友列表
     * @param $bkn
     */
    public function get_friend_list($bkn)
    {
        $get_friend_list = $this->get_conts("http://qun.qq.com/cgi-bin/qun_mgr/get_friend_list?bkn={$bkn}");
        return json_decode($get_friend_list['body'], true);
    }
    /**
     * 获取真·群列表
     * @param $bkn
     */
    public function get_group_list($bkn)
    {
        $get_group_list = $this->get_conts("http://qun.qq.com/cgi-bin/qun_mgr/get_group_list?bkn={$bkn}");
        return json_decode($get_group_list['body'], true);
    }

    /**
     * 获取真·群成员列表
     * @param $gc
     * @param $bkn
     */
    public function search_group_members($gc, $bkn)
    {
        $search_group_members = $this->get_conts("http://qun.qq.com/cgi-bin/qun_mgr/search_group_members?gc={$gc}&st=0&end=2000&sort=0&bkn={$bkn}");
        return json_decode($search_group_members['body'], true);
    }
    /**
     * @param $p
     * @param $pt
     * @param $vc
     */
    protected function qq_pwd($p, $pt, $vc)
    {
        $p    = strtoupper(md5($p));
        $len  = strlen($p);
        $temp = '';
        for ($i = 0; $i < $len; $i = $i + 2) {
            $temp .= '\x' . substr($p, $i, 2);
        }
        return strtoupper(md5(strtoupper(md5($this->hex2asc($temp) . $this->hex2asc($pt))) . $vc));
    }

    protected function hex2asc($str)
    {
        $str  = join('', explode('\x', $str));
        $len  = strlen($str);
        $data = null;
        for ($i = 0; $i < $len; $i += 2) {
            $data .= chr(hexdec(substr($str, $i, 2)));
        }
        return $data;
    }
    protected function get_bkn($skey)
    {
        $len  = strlen($skey);
        $hash = 5381;
        for ($i = 0; $i < $len; $i++) {
            $hash += ($hash << 5) + ord($skey[$i]);
        }
        return $hash & 2147483647;
    }
    protected function get_hash($uin, $ptwebqq)
    {
        for ($N = $ptwebqq."password error", $T = "", $V = array(); ; ){
            if (strlen($T) <= strlen($N)) {
                $T .= $uin;
                if (strlen($T) == strlen($N)) break;
            } else {
                $T = substr($T, 0,strlen($N)); break;
            }
        }
        for ($U = 0; $U < strlen($T); $U++)
            $V[$U] = $this->uniord(substr($T,$U)) ^ $this->uniord(substr($N,$U));
        $N = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");
        $T = "";
        for ($U = 0; $U < count($V); $U++) {
            $T .= $N[$V[$U] >> 4 & 15];
            $T .= $N[$V[$U] & 15];
        }
        return $T;
    }
    /**
     * @param $str
     * @return mixed
     */
    protected function uniord($str)
    {
        list(, $ord) = unpack('N', mb_convert_encoding($str, 'UCS-4BE', 'UTF-8'));
        return $ord;
    }
}
