
					<div class="panel-box panel-box-borderless panel-box-reply">
						<form action="<?php echo base_url(); ?>" class="send_message" method="post">
							<p><textarea class="message" name="message" placeholder="Enter your reply"></textarea></p>
							<div class="text-right">
								<?php /* <span class="legal">*each message is $1 | &#579;<?php echo $this->cart_model->usd_to_btc(1); ?></span> */ ?>
								<input class="button" name="send" type="submit" value="Send">
							</div>
							<input class="user_id_to" name="user_id_to" type="hidden" value="<?php echo $messages[0]->other_id; ?>">
							<input class="parent_id" name="parent_id" type="hidden" value="<?php echo $parent_id; ?>">
						</form>
					</div>
					<?php foreach ($messages as $message): ?>
						<?php if ($message->user_id_to != $this->_user->user_id || $message->message_deleted == 0): ?>
							<div class="panel-box">
								<div class="panel-photo">
									<div class="watermark-wrap">
										<?php if ($message->from->user_type == 1): ?>
											<a href="<?php echo base_url(); ?>contributors/profile/<?php echo $message->from->user_id; ?>">
										<?php elseif ($message->from->user_type == 2): ?>
											<a href="<?php echo base_url(); ?>models/profile/<?php echo $message->from->user_id; ?>">
										<?php endif; ?>
										<img alt="<?php echo isset($message->from->default) ? $message->from->default->filename : ''; ?>" src="<?php echo isset($message->from->default) ? CDN_URL . 'tall-' . strtolower($message->from->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="130">
										<?php if ($message->from->user_hd): ?>
											<div class="watermark-hd"></div>
										<?php endif; ?>
										<?php if ($message->from->user_type == 1): ?>
											</a>
										<?php elseif ($message->from->user_type == 2): ?>
											</a>
										<?php endif; ?>
									</div>
									<div class="panel-photo-<?php echo $message->from->online ? 'online' : 'offline'; ?>"><?php echo $message->from->display_name; ?></div>
									<div class="panel-photo-details"><?php echo date('M j Y g:ia', $message->message_created); ?></div>
								</div>
								<div class="panel-content" style="width: 566px;">
									<?php if ($message->html): ?>
										<p><?php echo $message->message; ?></p>
									<?php else: ?>
										<p><?php echo nl2br(htmlspecialchars($message->message)); ?></p>
									<?php endif; ?>
									<?php if ($message->user_id_to == $this->_user->user_id): ?>
										<div class="text-right">
											<a href="<?php echo base_url(); ?>messages/delete/<?php echo $message->message_id; ?>" onclick="return confirm('Are you sure you want to delete this message?\n\nPlease note: Deleting the first message in a thread\nwill delete all messages from that thread.');"><button class="button" type="button">Delete</button></a>
										</div>
									<?php endif; ?>
								</div>
								<div class="clearfix"></div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
