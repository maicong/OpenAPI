<?php
/**
 * 
 * @authors MaiCong (sb@yxx.me)
 * @date    2014-06-06 16:20:11
 * @version 0.1
 * curl_get() curl 反盗链
 *
 */

function curl_get($url){
    $host = parse_url($url);
    $site = $host['scheme']."://".$host['host'];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30秒超时
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 禁用验证
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // 返回原网页
    curl_setopt($ch, CURLOPT_REFERER, $site); // 伪造来源
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)'); // 伪造User-Agent
    curl_setopt($ch, CURLOPT_HTTPHEADER , array('X-FORWARDED-FOR:1.2.4.8', 'X-FORWARDED-HOST:'.$host['host'], 'X-FORWARDED-SERVER:'.$host['host'])); // 伪造HTTP头
    $response =  curl_exec($ch);
    curl_close($ch);

    return $response;
}