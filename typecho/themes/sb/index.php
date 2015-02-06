<?php
/** 
 * 麦田一根葱，你就是个傻逼！
 * @sb
 * @author 傻逼余小昔
 * @version 2012
 * @link http://i.yuxiaoxi.com/
 */
 $this->need('header.php');
 ?>
<?php while($this->next()): ?>
<article id="you_are_sb">
  <time class="time" datetime="<?php $this->date('Y-m-d H:i:s'); ?>"><a href="<?php $this->permalink() ?>"><?php $this->date('Y-m-d H:i'); ?></a></time>
  <section class="content">
    <?php $this->content(); ?>
    
  </section>
</article>
<?php endwhile; ?>
<?php $this->need('footer.php'); ?>