<?php $this->need('header.php'); ?>
<div id="content">
<div id="posts">
<div class="hentry"><h2><a href="<?php $this->permalink() ?>" title="<?php $this->title() ?>" ><?php $this->title() ?></a></h2>
<div class="postContent"><?php $this->content(); ?></div>

<div class="postInfo">
<div class="postTags">
<?php $this->date('Y-m-d H:i'); ?> <?php $this->views();?>人屎过
</div>

<br class="clear">
</div>
</div>


<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('footer.php'); ?>