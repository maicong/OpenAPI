# jQuery Lazy Load - Modified by MaiCong

## 说明

配置项增加 `broken_pic`，如果原始图片加载错误则使用 `broken_pic` 定义的图片

需要和  `jquery.imagesloaded.js` 配合使用

例如：

```
$("img.lazy").lazyload({
    effect: 'fadeIn',
    broken_pic: '//xxx.com/images/broken_pic.jpg'
});
```

## License

see: https://github.com/desandro/imagesloaded

see: https://github.com/tuupola/jquery_lazyload
