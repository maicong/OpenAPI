<?php $this->need('header.php'); ?>
<?php if ($this->have()): ?>
<?php while($this->next()): ?>
<div id="posts">
<div class="hentry"><h2><a href="<?php $this->permalink() ?>" title="<?php $this->title() ?>" ><?php $this->title() ?></a></h2>
<div class="date-container"><span class="date"><?php $this->date('Y-m-d H:i'); ?></span></div>
<div class="postContent"><?php $this->content(); ?></div>

<div class="postInfo">
<div class="postTags">
<?php $this->category(', '); ?> &#47; <?php $this->tags(', ', true, 'none'); ?>
</div>
<div class="postNotes"><?php $this->views();?>人屎过 
<a href="<?php $this->permalink() ?>#comments"><?php $this->commentsNum('没有评论', '1条评论', '%d 条评论'); ?></a>
</div>

<div class="clear"></div>
</div>
</div>

<?php endwhile; ?>
<?php else: ?>
<div class="post">
<div class="hentry"><h2><a><?php _e('Nothing Found'); ?></a></h2>
</div>
<?php endif; ?>
</div>

<?php $this->pageNav('&#60;','&#62;',5,'<a>...</a>'); ?>

<?php $this->need('footer.php'); ?>