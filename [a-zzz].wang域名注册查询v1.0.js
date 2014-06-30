/**
 * @fileoverview  [a-zzz].wang域名注册查询js代码
 * @author        MaiCong <sb@yxx.me>
 * @date          2014-06-30 11:15:12
 * @version       1.0
 */

// 引入jQuery
var jq = document.createElement('script');
jq.src = "http://upcdn.b0.upaiyun.com/libs/jquery/jquery-1.8.3.min.js";
document.getElementsByTagName('head')[0].appendChild(jq);

// 生成查询数组
var data = [""];
for (var i = 0; i < 26; i++) { //a-z
    data.push(String.fromCharCode((65 + i)));
    for (var j = 0; j < 26; j++) { //aa-zz
        data.push(String.fromCharCode((65 + i)) + String.fromCharCode((65 + j)));
        for (var k = 0; k < 26; k++) { //aaa-zzz
            data.push(String.fromCharCode((65 + i)) + String.fromCharCode((65 + j)) + String.fromCharCode((65 + k)));
        }
    }
}

//开始查询
var num = 0,
    len = data.length,
    timer =
    setInterval(function() {
        num++;
        var domain = data[num].toLowerCase();
        $.get('http://www.west263.com/services/domain/whois.asp?act=query&domains=' + domain + '&suffixs=.wang', function(d) {
            if (d) {
                if (d.indexOf('yes') >= 0) {
                    console.log('可以注册 ', domain + '.wang', num, len);
                } else {
                    console.log('已注册 ', domain + '.wang', num, len);
                }
            } else {
                console.log('暂无结果 ', domain + '.wang', num, len);
            }

        });
        if (num === len - 1) {
            clearInterval(timer);
        }
    }, 1000); // 每1秒get一次