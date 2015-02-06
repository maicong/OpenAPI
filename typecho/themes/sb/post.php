<?php $this->need('header.php'); ?>
<article id="you_are_sb">
  <time class="time" datetime="<?php $this->date('Y-m-d H:i:s'); ?>"><a href="<?php $this->permalink() ?>"><?php $this->date('Y-m-d H:i'); ?></a></time>
  <section class="content"><?php $this->content(); ?></section>
</article>
<?php $this->need('footer.php'); ?>