/**
 *
 * 列表滚动侦听
 *
 * 此插件备用，暂不加说明
 *
 * @author  MaiCong (i@maicong.me)
 * @create  2016-09-12 23:14:35
 * @update  2016-09-12 23:14:35
 * @since   0.1
 */

!(function ($) {
    $.fn.scrollList = function (options) {
        var defaults = {
            listBlock: '.list-block',
            listElem: '.list-group',
            listTitle: '.list-title',
            navBlock: '.list-index',
            navElem: 'span'
        };
        var opts = $.extend(defaults, options || {}),
            isJump = false,
            $elems = [],
            $nav = [],
            $this = $(this),
            $listBlock = $this.find(opts.listBlock),
            $listElem = $listBlock.find(opts.listElem),
            $navBlock = $this.find(opts.navBlock);

        // 监听滚动
        $this._scroll = function ($navElem) {
            var isScrolling = false, timer = null;
            $.map($navElem, function (e) {
                $nav.push({
                    'offset': $(e).position().top
                });
            });
            $listBlock.on('scroll', function () {
                var _top = $(this).scrollTop(),
                    _eNum = Math.floor($navBlock.height() / 2 / $navElem.height()),
                    _offset = 0, i = 0;
                if (timer !== null) {
                    window.clearTimeout(timer);
                }
                timer = window.setTimeout( function () {
                    for (; i < $elems.length; i++) {
                        _offset = (isJump) ? $elems[i].offset: $elems[i].offheight;
                        if (_top <= _offset) {
                            break;
                        }
                    }
                    if ($navBlock[0].scrollHeight > $navBlock.height()) {
                        if (isScrolling) {
                            return false;
                        }
                        isScrolling = true;
                        $navBlock.scrollTo({
                            to: i > _eNum ? $nav[i].offset - $navBlock.height() / 2: 0,
                            callback: function () {
                                isScrolling = false;
                            }
                        });
                    }
                    isJump = false;
                    $navElem.eq(i).addClass('active').siblings(opts.navElem).removeClass('active');
                }, 150);
            });
        };

        // 配置
        $this._init = function () {
            // 遍历列表
            $.map($listElem, function (elem, i) {
                var _height = $(elem).height(),
                    _offset = $(elem).position().top,
                    _title = $(elem).find(opts.listTitle).text();
                $elems.push({
                    'height': _height,
                    'offset': _offset,
                    'offheight': _height + _offset,
                });
                if (i === $listElem.length - 1) {
                    $(elem).css({
                        marginBottom: $listBlock.height() - _height + 4 + 'px'
                    });
                }
                $navBlock.append($('<' + opts.navElem + '/>').text(_title));
            });
            $navBlock.find(opts.navElem).eq(0).addClass('active');

            // 判断事件
            var $navElem = $navBlock.find(opts.navElem);
            $navElem.on('click', function () {
                var j = $(this).index();
                if ($elems[j]) {
                    isJump = true;
                    $listBlock.scrollTo({
                        to: $elems[j].offset
                    });
                }
            });

            $this._scroll($navElem);

            window.initialledScroll = true;
        };

        return $this._init();
    };
})(window.Zepto);
