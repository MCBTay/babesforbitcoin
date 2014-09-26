
<div id="page-messages">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<ul class="sidebar-navigation">
				<?php foreach ($messages as $key => $message): ?>
					<li>
						<a class="show-messages side-nav-thumb<?php echo $key == 0 ? ' active' : ''; echo $message->unread ? ' unread' : ''; ?>" href="javascript:void(0);" id="message-<?php echo $message->parent_id ? $message->parent_id : $message->message_id; ?>">
							<img alt="<?php echo isset($message->user->default) ? $message->user->default->asset_title : ''; ?>" src="<?php echo isset($message->user->default) && !empty($message->user->default->filename) ? CDN_URL . 'sml-' . strtolower($message->user->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="50" height="50">
							<span class="thumb-side">
								<?php echo $message->user->display_name; ?><br>
								<span><?php echo date('M j Y', $message->message_created); ?></span>
							</span>
							<img alt="" class="new-tag" src="<?php echo base_url(); ?>assets/img/new-tag.png">
							<?php if ($message->user->user_hd): ?>
								<div class="watermark-hd-small"></div>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			&nbsp;
		</div>
		<div class="content-right content-right-large copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Messages</h2>
				</div>
				<div class="panel-body">
					<div id="messages-loader"><div style="padding: 5px;">No messages yet, try <a href="<?php echo base_url(); ?><?php echo $this->_user->user_type == 2 ? 'contributors' : 'models'; ?>">striking up a conversation</a>!</div></div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
