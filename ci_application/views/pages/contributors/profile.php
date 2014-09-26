
<div id="page-models-profile">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<div class="panel-photo">
				<div class="watermark-wrap">
					<a href="<?php echo base_url(); ?>contributors/public/<?php echo $contributor->user_id; ?>"><img alt="<?php echo isset($contributor->default) ? $contributor->default->filename : ''; ?>" src="<?php echo isset($contributor->default) ? CDN_URL . 'tall-' . strtolower($contributor->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="210" height="254"></a>
					<?php if ($contributor->user_hd): ?>
						<div class="watermark-hd"></div>
					<?php endif; ?>
				</div>
				<div class="panel-photo-<?php echo $contributor->online ? 'online' : 'offline'; ?>"><?php echo $contributor->display_name; ?></div>
				<p><a href="<?php echo base_url(); ?>manage-my-files/send/<?php echo $contributor->user_id; ?>"><button class="button" style="width: 210px;">Send Files to Contributor</button></a></p>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="content-right content-right-large copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Profile</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php if (!empty($contributor->profile)): ?>
							<?php echo nl2br(htmlspecialchars($contributor->profile)); ?>
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
								<input class="button" name="send" type="submit" value="Send">
							</div>
							<input class="user_id_to" name="user_id_to" type="hidden" value="<?php echo $contributor->user_id; ?>">
							<input class="parent_id" name="parent_id" type="hidden" value="0">
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
