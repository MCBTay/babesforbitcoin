
<div id="page-preferences">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Account Preferences</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>account/preferences/<?php echo htmlspecialchars($pw_note); ?>" method="post">
							<?php if (validation_errors() != ''): ?>
								<div class="alert alert-danger">
									<strong>Warning:</strong> Please fix any errors noted below.
								</div>
							<?php endif; ?>
							<?php if (isset($success)): ?>
								<div class="alert alert-danger">
									<strong>Success:</strong> Your account preferences have been saved.
								</div>
							<?php endif; ?>
							<div class="input-group">
								<label for="display_name">Display Name:</label>
								<input id="display_name" maxlength="15" name="display_name" type="text" value="<?php echo $user->display_name; ?>">
								<?php if (form_error('display_name') != ''): ?>
									<div class="form-error">
										<?php echo form_error('display_name'); ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="input-group">
								<label for="email">Email:</label>
								<input id="email" name="email" type="email" value="<?php echo $user->email; ?>">
								<?php if (form_error('email') != ''): ?>
									<div class="form-error">
										<?php echo form_error('email'); ?>
									</div>
								<?php endif; ?>
							</div>
							<?php if ($this->_user->user_type == 2): ?>
								<div class="input-group">
									<label for="accept_btc">Accept &#579;TC:</label>
									<select id="accept_btc" name="accept_btc">
										<option value="0"<?php echo $user->accept_btc == 0 ? ' selected="selected"' : ''; ?>>No</option>
										<option value="1"<?php echo $user->accept_btc == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
									</select>
								</div>
							<?php endif; ?>
							<?php if ($this->_user->user_type == 1): ?>
								<div class="input-group">
									<label for="prefer_btc">Default Currency:</label>
									<select id="prefer_btc" name="prefer_btc">
										<option value="0"<?php echo $user->prefer_btc == 0 ? ' selected="selected"' : ''; ?>>USD</option>
										<option value="1"<?php echo $user->prefer_btc == 1 ? ' selected="selected"' : ''; ?>>&#579;TC</option>
									</select>
								</div>
							<?php endif; ?>
							<h3 class="input-group-header">Change Password</h3>
							<?php if ($pw_note == 'password'): ?>
								<div class="alert alert-danger">
									<strong>Attention:</strong> Please use this form to update your password.
								</div>
							<?php else: ?>
								<div class="input-group">
									<label for="password">Current Password:</label>
									<input autocomplete="off" id="password" name="password" type="password">
									<?php if (form_error('password') != ''): ?>
										<div class="form-error">
											<?php echo form_error('password'); ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="input-group">
								<label for="drowssap">New Password:</label>
								<input autocomplete="off" id="drowssap" name="drowssap" type="password">
								<?php if (form_error('drowssap') != ''): ?>
									<div class="form-error">
										<?php echo form_error('drowssap'); ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="input-group">
								<label for="confirm-password">Confirm Password:</label>
								<input autocomplete="off" id="confirm-password" name="confirm-password" type="password">
								<?php if (form_error('confirm-password') != ''): ?>
									<div class="form-error">
										<?php echo form_error('confirm-password'); ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="clearfix"></div>
							<div class="input-group input-group-submit">
								<input id="change" name="change" type="submit" value="Confirm Change">
							</div>
							<h3 class="input-group-header">Email Notifications</h3>
							<div class="input-group">
								<label class="input-checkbox"><input<?php echo $user->notify_email_messages == 1 ? ' checked="checked"' : ''; ?> name="notify_email_messages" type="checkbox" value="1"> When I receive a message</label>
								<label class="input-checkbox"><input<?php echo $user->notify_email_photos == 1 ? ' checked="checked"' : ''; ?> name="notify_email_photos" type="checkbox" value="1"> When I receive a photo or photoset</label>
								<label class="input-checkbox"><input<?php echo $user->notify_email_videos == 1 ? ' checked="checked"' : ''; ?> name="notify_email_videos" type="checkbox" value="1"> When I receive a video</label>
							</div>
							<div class="clearfix"></div>
							<h3 class="input-group-header">Text Notifications</h3>
							<div class="input-group">
								<label for="text_number">Cell Number:</label>
								<input id="text_number" maxlength="10" name="text_number" type="tel" value="<?php echo $user->text_number ? $user->text_number : ''; ?>">
								<?php if (form_error('text_number') != ''): ?>
									<div class="form-error">
										<?php echo form_error('text_number'); ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="input-group">
								<label for="text_carrier">Cell Carrier:</label>
								<select id="text_carrier" name="text_carrier">
									<option value="0"> - Select Carrier - </option>
									<?php foreach ($carriers as $carrier): ?>
										<option value="<?php echo $carrier->carrier_id; ?>"<?php echo $user->text_carrier == $carrier->carrier_id ? ' selected="selected"' : ''; ?>><?php echo $carrier->carrier_name; ?></option>
									<?php endforeach; ?>
								</select>
								<div class="form-error">
									<a href="mailto:info@babesforbitcoin.com&amp;subject=My carrier is not listed&amp;body=Please tell us which carrier you use:">My carrier is not listed.</a>
								</div>
								<?php if (form_error('text_carrier') != ''): ?>
									<div class="form-error">
										<?php echo form_error('text_carrier'); ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="input-group">
								<label class="input-checkbox"><input<?php echo $user->notify_text_messages == 1 ? ' checked="checked"' : ''; ?> name="notify_text_messages" type="checkbox" value="1"> When I receive a message</label>
								<label class="input-checkbox"><input<?php echo $user->notify_text_photos == 1 ? ' checked="checked"' : ''; ?> name="notify_text_photos" type="checkbox" value="1"> When I receive a photo or photoset</label>
								<label class="input-checkbox"><input<?php echo $user->notify_text_videos == 1 ? ' checked="checked"' : ''; ?> name="notify_text_videos" type="checkbox" value="1"> When I receive a video</label>
							</div>
							<div class="clearfix"></div>
							<div class="input-group input-group-submit">
								<input id="save" name="save" type="submit" value="Save">
							</div>
							<div class="input-group input-group-submit">
								<p style="margin-top: 15px;"><a href="<?php echo base_url(); ?>contact/cancel"><small>Cancel Account</small></a></p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
