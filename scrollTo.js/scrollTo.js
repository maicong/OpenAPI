/**
 *
 * scrollTo 滚动到指定位置插件
 *
 * 有两个版本，选其一就行
 *
 * @author  MaiCong (i@maicong.me)
 * @create  2016-09-12 22:59:37
 * @update  2016-09-12 22:59:37
 * @since   0.1
 */

// Zepto版 滚动到指定高度
//
// $('body').scrollTo({ to: 0 });
//
!(function ($) {
    $.fn.scrollTo = function(options){
        var defaults = {
            to : 0,
            durTime : 350,
            delay : 10,
            callback: null
        };
        var opts = $.extend(defaults,options),
            timer = null,
            _this = this,
            curTop = _this.scrollTop(),
            subTop = opts.to - curTop,
            index = 0,
            dur = Math.round(opts.durTime / opts.delay),
            smoothScroll = function(t){
                index++;
                var per = Math.round(subTop/dur);
                if(index >= dur){
                    _this.scrollTop(t);
                    window.clearInterval(timer);
                    if(opts.callback && typeof opts.callback === 'function'){
                        opts.callback();
                    }
                    return;
                }else{
                    _this.scrollTop(curTop + index*per);
                }
            };
        timer = window.setInterval(function(){
            smoothScroll(opts.to);
        }, opts.delay);
        return _this;
    };
})(window.Zepto);


// 非 Zepto 版
//
// animatedScrollTo(document.body, 0);
//
var animatedScrollTo = function(element, to, duration, callback) {
    var requestAnimFrame = (function() {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function(callback) { window.setTimeout(callback, 1000 / 60); }; })();
    var easeInOutQuad = function(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    };
    var start = element.scrollTop,
        change = to - start,
        animationStart = +new Date();
    var animating = true;
    var lastpos = null;
    var animateScroll = function() {
        if (!animating) {
            return;
        }
        duration = duration || 600;
        requestAnimFrame(animateScroll);
        var now = +new Date();
        var val = Math.floor(easeInOutQuad(now - animationStart, start, change, duration));
        if (lastpos) {
            if (lastpos === element.scrollTop) {
                lastpos = val;
                element.scrollTop = val;
            } else {
                animating = false;
            }
        } else {
            lastpos = val;
            element.scrollTop = val;
        }
        if (now > animationStart + duration) {
            element.scrollTop = to;
            animating = false;
            if (callback) { callback(); }
        }
    };
    requestAnimFrame(animateScroll);
};