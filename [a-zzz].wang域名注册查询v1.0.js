/**
 * 增加功能：在LocalStorage存储查询进度和可注册域名
 * @fileoverview  [a-zzz].wang域名注册查询js代码
 * @author        MaiCong <sb@yxx.me>
 * @date          2014-06-30 13:21:05
 * @version       1.1
 */

// 引入jQuery
var jq = document.createElement('script');
jq.src = 'http://upcdn.b0.upaiyun.com/libs/jquery/jquery-1.8.3.min.js';
document.getElementsByTagName('head')[0].appendChild(jq);


// 生成查询数组
var data = [''];
for (var i = 0; i < 26; i++) { //a-z
    data.push(String.fromCharCode((65 + i)));
    for (var j = 0; j < 26; j++) { //aa-zz
        data.push(String.fromCharCode((65 + i)) + String.fromCharCode((65 + j)));
        for (var k = 0; k < 26; k++) { //aaa-zzz
            data.push(String.fromCharCode((65 + i)) + String.fromCharCode((65 + j)) + String.fromCharCode((65 + k)));
        }
    }
}

// 清空localStorage (根据需要)
// localStorage.clear();

// 选择性清除 (根据需要)
// localStorage.removeItem('getProgress'); // 查询进度
// localStorage.removeItem('allowReg'); // 可注册域名

// 开始查询
$(document).ready(function() {
    $('body').css('padding', '2em').html('正在准备查询...');

    if (window.localStorage && localStorage.allowReg) {
        $('body').append('<br><br><i style="color:#1ca529">已查询到的可注册域名</i> ' + localStorage.allowReg);
    }

    $('body').append('<br><br>开始查询...');

    var len = data.length,
        allow = [],
        num = 0,
        jqGet = true,
        domain,
        timer;

    if (window.localStorage && localStorage.getProgress) {
        num = localStorage.getProgress;
    }

    $('body').append('<br><br>查询[a-zzz].wang， 共有' + len + '条结果<br>');

    if (jqGet) {
        timer = setInterval(function() {
            num++;
            domain = data[num].toLowerCase();
            jqGet = false;
            $.get('http://www.west263.com/services/domain/whois.asp?act=query&domains=' + domain + '&suffixs=.wang', function(d) {
                if (d) {
                    jqGet = true;
                    if (window.localStorage) {
                        localStorage.setItem('getProgress', num); // 保存进度
                    }
                    if (d.indexOf('yes') >= 0) {
                        allow.push(domain + '.wang');
                        $('body').append('<br><i style="color:#1ca529">可以注册</i> ', domain + '.wang ' + num + '/' + len);
                        if (window.localStorage) {
                            localStorage.setItem('allowReg', allow); // 保存可注册域名
                        }
                    } else {
                        $('body').append('<br>已注册: ', domain + '.wang ' + num + '/' + len);
                    }
                } else {
                    $('body').append('<br><i style="color:#d35b5b">查询中...</i> ', domain + '.wang ' + num + '/' + len);
                }
            });
            if (num === len - 1) {
                clearInterval(timer);
            }
        }, 1500); // 每1.5秒get一次
    }
});