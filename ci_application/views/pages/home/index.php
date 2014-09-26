
<div id="page-home">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<?php if ($this->_user->user_type == 2 && !$this->_user->user_approved): ?>
				<div class="alert alert-danger">
					<p style="margin-top: 0;">In order to comply with the federal <a href="http://www.informationlaw.com/blog/What-is-18-USC-2257-and-How-Does-it-Affect-Me.htm" target="_blank">2257 regulations</a> we require a government issued ID.  You may redact (black out) any information other than Name, Date of Birth, Photo, and ID number. All information given will remain private and is only used for verification purposes, we will never sell your information.</p>
					<p>If you do not wish to show your face to the public, please include a fansign with BabesForBitcoin written on it. This photo must show your face, but will not be shared publicly.</p>
					<p style="margin-bottom: 0;"><a href="<?php echo base_url(); ?>upload/private">Please click here to upload</a></p>
				</div>
			<?php endif; ?>
			<?php if (!$messages): ?>
				<h2 class="normal">Welcome to <?php echo SITE_TITLE; ?></h2>
				<p>Thanks for joining!</p>
				<p>We recommend starting off by <a href="<?php echo base_url(); ?>upload/public">uploading your profile photo</a>.</p>
				<p>And don't forget to <a href="<?php echo base_url(); ?>account/profile">update your profile</a> as well.</p>
				<?php if ($this->_user->user_type == 1 || $this->_user->user_type >= 3): ?>
					<p>Check out our <a href="<?php echo base_url(); ?>models">models page</a> to find someone you like.</p>
				<?php endif; ?>
				<?php if ($this->_user->user_type == 2 || $this->_user->user_type >= 3): ?>
					<p>Check out our <a href="<?php echo base_url(); ?>contributors">contributors page</a> to find someone you like.</p>
				<?php endif; ?>
				<p>Enjoy your stay and <a href="<?php echo base_url(); ?>contact">let us know</a> if you have any questions.</p>
			<?php else: ?>
				<div class="panel">
					<div class="panel-title">
						<h2>Recent Messages</h2>
					</div>
					<div class="panel-body">
						<?php foreach ($messages as $key => $message): ?>
							<div class="panel-box<?php echo $key == 0 ? ' panel-box-borderless' : ''; ?>">
								<div class="panel-photo">
									<div class="watermark-wrap">
										<?php if ($message->user->user_type == 1): ?>
											<a href="<?php echo base_url(); ?>contributors/profile/<?php echo $message->user->user_id; ?>">
										<?php elseif ($message->user->user_type == 2): ?>
											<a href="<?php echo base_url(); ?>models/profile/<?php echo $message->user->user_id; ?>">
										<?php endif; ?>
										<img alt="<?php echo isset($message->user->default) ? $message->user->default->filename : ''; ?>" src="<?php echo isset($message->user->default) ? CDN_URL . 'tall-' . strtolower($message->user->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="130">
										<?php if ($message->user->user_hd): ?>
											<div class="watermark-hd"></div>
										<?php endif; ?>
										<?php if ($message->user->user_type == 1): ?>
											</a>
										<?php elseif ($message->user->user_type == 2): ?>
											</a>
										<?php endif; ?>
									</div>
									<div class="panel-photo">
										<div class="panel-photo-<?php echo $message->user->online ? 'online' : 'offline'; ?>"><?php echo $message->user->display_name; ?></div>
										<div class="panel-photo-details"><?php echo date('M j Y g:ia', $message->message_created); ?></div>
									</div>
								</div>
								<div class="panel-content">
									<?php if ($message->html): ?>
										<p><?php echo $message->message; ?></p>
									<?php else: ?>
										<p><?php echo nl2br(htmlspecialchars($message->message)); ?></p>
									<?php endif; ?>
									<div class="jshowhide">
										<div class="jshow">
											<div class="text-right">
												<button class="button" type="button">Reply</button>
											</div>
										</div>
										<div class="jhide">
											<form action="<?php echo base_url(); ?>" class="send_message" method="post">
												<p><textarea class="message" name="message" placeholder="Enter your reply"></textarea></p>
												<div class="text-right">
													<?php /* <span class="legal">*each message is $1 | &#579;<?php echo $this->cart_model->usd_to_btc(1); ?></span> */ ?>
													<button class="button button-cancel" type="button">Cancel</button>
													<input class="button" name="send" type="submit" value="Send">
												</div>
												<input class="user_id_to" name="user_id_to" type="hidden" value="<?php echo $message->user_id_from; ?>">
												<input class="parent_id" name="parent_id" type="hidden" value="<?php echo $message->parent_id ? $message->parent_id : $message->message_id; ?>">
											</form>
										</div>
									</div>
									<div class="text-right home-message-delete" style="margin-top: 15px;">
										<a href="<?php echo base_url(); ?>messages/delete/<?php echo $message->message_id; ?>" onclick="return confirm('Are you sure you want to delete this message?\n\nPlease note: Deleting the first message in a thread\nwill delete all messages from that thread.');"><button class="button" type="button">Delete</button></a>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						<?php endforeach; ?>
					</div>
					<p><a href="<?php echo base_url(); ?>messages"><button class="button">View All Messages</button></a></p>
				</div>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
