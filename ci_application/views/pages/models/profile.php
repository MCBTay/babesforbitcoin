
<div id="page-models-profile">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<div class="panel-photo">
				<div class="watermark-wrap">
					<a href="<?php echo base_url(); ?>models/public/<?php echo $model->user_id; ?>"><img alt="<?php echo isset($model->default) ? $model->default->filename : ''; ?>" src="<?php echo isset($model->default) ? CDN_URL . 'tall-' . strtolower($model->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="210" height="254"></a>
					<?php if ($model->user_hd): ?>
						<div class="watermark-hd"></div>
					<?php endif; ?>
				</div>
				<div class="panel-photo-<?php echo $model->online ? 'online' : 'offline'; ?>"><?php echo $model->display_name; ?></div>
			</div>
			<div class="clearfix"></div>
			<div class="model-stats">
				<dl>
					<dt><a href="<?php echo base_url(); ?>models/photosets/<?php echo $model->user_id; ?>"><?php echo $model->assets[3]; ?></a></dt>
						<dd><a href="<?php echo base_url(); ?>models/photosets/<?php echo $model->user_id; ?>">Photosets</a></dd>
					<dt><a href="<?php echo base_url(); ?>models/videos/<?php echo $model->user_id; ?>"><?php echo $model->assets[5]; ?></a></dt>
						<dd><a href="<?php echo base_url(); ?>models/videos/<?php echo $model->user_id; ?>">Videos</a></dd>
					<dt><a href="<?php echo base_url(); ?>my-files/model/<?php echo $model->user_id; ?>"><?php echo $owned; ?></a></dt>
						<dd><a href="<?php echo base_url(); ?>my-files/model/<?php echo $model->user_id; ?>">Owned</a></dd>
				</dl>
			</div>
			<div class="clearfix"></div>
			<?php /*
			<div class="model-buttons">
				<p><a href="javascript:alert('This feature is coming soon!');"><button class="button"><span class="jshown">Add to Favorites</span><span class="jhidden">Coming Soon</span></button></a></p>
				<p><a href="javascript:alert('This feature is coming soon!');"><button class="button"><span class="jshown">Invite to Chat</span><span class="jhidden">Coming Soon</span></button></a></p>
			</div>
			*/ ?>
		</div>
		<div class="content-right content-right-large copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Profile</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php if (!empty($model->profile)): ?>
							<?php echo nl2br(htmlspecialchars($model->profile)); ?>
						<?php else: ?>
							This user does not have a profile set yet.
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-title">
					<h2>Send a Message</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>" class="send_message" method="post">
							<p><textarea class="message" name="message" placeholder="Enter your message"></textarea></p>
							<div class="text-right">
								<?php /* <span class="legal">*each message is $1 | &#579;<?php echo $this->cart_model->usd_to_btc(1); ?></span> */ ?>
								<input class="button" name="send" type="submit" value="Send">
							</div>
							<input class="user_id_to" name="user_id_to" type="hidden" value="<?php echo $model->user_id; ?>">
							<input class="parent_id" name="parent_id" type="hidden" value="0">
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
