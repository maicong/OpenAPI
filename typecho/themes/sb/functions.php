<?php
    function threadedComments($comments,$singleCommentOptions) {
            $author = '<a href="'.$comments->url.'" rel="external nofollow" target="_blank">'.$comments->author.'</a>';
			$depth = $comments->levels +1;	
			if($depth<=1){
				$floor = $comments->sequence.'L'; // 主楼层
			}else{
				$floor = ($depth-1).'#';  // 子楼层
				$style = 'style="margin-left:' . ceil(80/$depth) . 'px;"';
			}
        ?>
	<li id="<?php $comments->theId(); ?>" class="<?php echo 'depth-'.$depth;?>" <?php if( $depth > 1) echo $style;?>>
		<div id="comment-<?php $comments->theId(); ?>">
            <div  class="comment-body">
			    <div class="commentmeta"><?php $comments->gravatar('32',''); ?></div>
				<div class="commentmetadata">&nbsp;-&nbsp;<?php $comments->date('Y-m-d H:i:s') ?>&nbsp;&nbsp;<span><?php echo $floor; ?></span></div>
				<div class="comment-reply"><?php $comments->reply('Reply') ?></div>
				<div class="vcard"><?php echo $author; ?></div>
				<?php $comments->content(); ?>
            </div>
            <div class="children">
                <?php if ($comments->children) { ?><?php $comments->threadedComments($singleCommentOptions); ?><?php } ?>
            </div>
		</div>
	</li>
<?php } ?>