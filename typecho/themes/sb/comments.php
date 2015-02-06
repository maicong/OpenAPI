<article id="comments">
            <?php $this->comments()->to($comments); ?>
            <?php if ($comments->have()): ?>
            <?php $comments->pageNav(); ?>  
            <?php $comments->listComments(); ?>       
            <?php endif; ?>
            <?php if($this->allow('comment')): ?>
            <div id="<?php $this->respondId(); ?>" class="respond">            
			<form method="post" action="<?php $this->commentUrl() ?>" id="comment_form">
				<h3 class="clearfix"><span id="cancel-comment-reply"><?php $comments->cancelReply(); ?></span></h3>
                <?php if($this->user->hasLogin()): ?>
				<p class="title welcome">
                    <?php _e('欢迎 '); ?> <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a><?php _e(' 童鞋归来, '); ?><a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出 »'); ?></a>
                    <span class="cancel-comment-reply"><?php $comments->cancelReply(); ?></span>
                </p>
                <?php else: ?>
                <div id="author_info">
				<p>
					<input type="text" name="author" id="author" class="text" size="15" value="<?php $this->remember('author'); ?>" />
                    <label for="author"><small><?php _e('姓名'); ?></small></label>
				</p>
				<p>
					<input type="text" name="mail" id="mail" class="text" size="15" value="<?php $this->remember('mail'); ?>" />
                    <label for="mail"><small><?php _e('邮箱'); ?></small></label>
				</p>
				<p>
					<input type="text" name="url" id="url" class="text" size="15" value="<?php $this->remember('url'); ?>" />
                    <label for="url"><small><?php _e('网站'); ?></small></label>
				</p>
                </div>
                <?php endif; ?>
				<div id="author_textarea"><textarea name="text" id="comment" class="textarea" cols="105" rows="14" tabindex="4" onkeydown="if(event.ctrlKey&&event.keyCode==13){document.getElementById('submit').click();return false};"><?php $this->remember('text'); ?></textarea></div>
				<p>
                    <input id="submit" type="submit" value="<?php _e('确认提交 / Ctrl+Enter'); ?>" class="submit" />
                </p>
			</form>
            </div>
            <?php else: ?>
            <h4><?php _e('评论已关闭'); ?></h4>
            <?php endif; ?>
</article>
