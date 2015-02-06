<<<<<<< .mine
<article id="sb_say">
	<div class="ds-thread"></div>
</article>
<footer id="sb_yuxiaoxi">
  <span id="fuck">What's the fuck day! Back your mother's time!</span>  <span id="on"></span><span id="off"></span>
  <?php
  $musicurl = array();
  $musicurl[0] = "http://cdn2.yuxiaoxi.com/รอเธอหันมา";
  $musicurl[1] = "http://cdn2.yuxiaoxi.com/somethingelse";
  $musicurl[2] = "http://cdn2.yuxiaoxi.com/saveme";
  $musicurl[3] = "http://cdn2.yuxiaoxi.com/duandian";
  $musicurl[4] = "http://cdn2.yuxiaoxi.com/fengdu";
  $imusic =array_rand($musicurl,1);
  $music = $musicurl[$imusic].'.mp3';
  $lrcfile = $musicurl[$imusic].'.lrc';
  $lrc=file_get_contents($lrcfile,"utf-8");
  $lrc=preg_replace('/\r|\n/','', $lrc);
  ?>
<audio id="music" src="<?php echo $music; ?>" autoplay="autoplay" loop preload="auto" type="audio/mp3"></audio>
<?php $this->footer(); ?>
  <script src="http://libs.baidu.com/jquery/2.0.3/jquery.min.js"></script>
  <script src="<?php $this->options->themeUrl('sb.js'); ?>"></script>
  <div style="display:none"><script src="http://s14.cnzz.com/stat.php?id=5549401&web_id=5549401" language="JavaScript"></script></div>
</footer>
<script type="text/javascript">
  var lrc= "<?php echo $lrc; ?>";
</script>
</body>
=======
<article id="sb_say">
	<div class="ds-thread"></div>
</article>
<footer id="sb_yuxiaoxi">
  <span id="fuck" title="我是隐藏的评论页，如果你找到了，说明你真有闲心！">What's the fuck day! Back your mother's time!</span>  <span id="on" title="点击暂停"></span><span id="off" title="点击播放"></span>
  <?php
  $musicurl = array();
  $musicurl[0] = "http://cdn2.yuxiaoxi.com/รอเธอหันมา";
  $musicurl[1] = "http://cdn2.yuxiaoxi.com/somethingelse";
  $musicurl[2] = "http://cdn2.yuxiaoxi.com/saveme";
  $musicurl[3] = "http://cdn2.yuxiaoxi.com/duandian";
  $musicurl[4] = "http://cdn2.yuxiaoxi.com/fengdu";
  $imusic =array_rand($musicurl,1);
  $music = $musicurl[$imusic].'.mp3';
  $lrcfile = $musicurl[$imusic].'.lrc';
  $lrc=file_get_contents($lrcfile,"utf-8");
  $lrc=preg_replace('/\r|\n/','', $lrc);
  ?>
<audio id="music" src="<?php echo $music; ?>" autoplay="autoplay" loop="loop" preload="auto" type="audio/mp3"></audio>
<?php $this->footer(); ?>
  <script src="http://libs.baidu.com/jquery/2.0.3/jquery.min.js"></script>
  <script src="<?php $this->options->themeUrl('sb.js'); ?>"></script>
  <div style="display:none"><script src="http://s14.cnzz.com/stat.php?id=5549401&web_id=5549401" language="JavaScript"></script></div>
</footer>
<script type="text/javascript">
  var lrc= "<?php echo $lrc; ?>";
</script>
</body>
>>>>>>> .r284
</html>