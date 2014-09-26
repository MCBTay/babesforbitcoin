
<div id="page-contact">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Contact Us</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>contact" method="post">
							<?php if (validation_errors() != ''): ?>
								<div class="alert alert-danger">
									<strong>Warning:</strong> Please fix any errors noted below.
								</div>
							<?php endif; ?>
							<div class="form-long">
								<p>
									<label for="display_name">Your Name:</label><br>
									<input class="inputbox" id="display_name" maxlength="15" name="display_name" type="text" value="<?php echo $user->display_name; ?>" readonly>
								</p>
								<p>
									<label for="email">Your Email:</label><br>
									<input class="inputbox" id="email" maxlength="15" name="email" type="text" value="<?php echo $user->email; ?>" readonly>
								</p>
								<p>
									<label for="subject">Subject:</label><br>
									<input class="inputbox" id="subject" name="subject" type="text" value="<?php echo set_value('subject', 'Request to delete photoset # ' . $asset->asset_id . ' - ' . $asset->asset_title); ?>">
									<?php if (form_error('subject') != ''): ?>
										<span class="form-error">
											<?php echo form_error('subject'); ?>
										</span>
									<?php endif; ?>
								</p>
								<p>
									<label for="message">Message:</label><br>
									<textarea class="textarea" id="message" name="message"><?php echo set_value('message', 'Please state the reason you are requesting removal:' . "\n\n"); ?></textarea>
									<?php if (form_error('message') != ''): ?>
										<span class="form-error">
											<?php echo form_error('message'); ?>
										</span>
									<?php endif; ?>
								</p>
								<p>
									<input class="submit" id="send" name="send" type="submit" value="Send">
								</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
