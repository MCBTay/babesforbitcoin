
		<div class="page-header">
			<h2>Edit User # <?php echo $user->user_id; ?></h2>
		</div>
		<?php if (validation_errors() != '' || isset($error)): ?>
			<div class="alert alert-danger">
				<span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp; <strong>Warning:</strong> Error saving user. Please see the errors below.
			</div>
		<?php endif; ?>
		<?php if ($success): ?>
			<div class="alert alert-success">
				User # <?php echo $user->user_id; ?> was successfully saved.
			</div>
		<?php endif; ?>
		<p>
			<a href="<?php echo base_url(); ?>management/users" class="btn btn-primary back">Back to list of users</a>
			&nbsp;
			<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>" class="btn btn-default">View user gallery</a>
		</p>
		<form action="<?php echo base_url(); ?>management/users/edit/<?php echo $user->user_id; ?>" class="form-horizontal" enctype="multipart/form-data" method="post" role="form">
			<div class="form-group">
				<label for="user_id" class="col-sm-2 control-label">User ID</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->user_id; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="user_type" class="col-sm-2 control-label">Type</label>
				<div class="col-sm-10">
					<select class="form-control" name="user_type">
						<?php foreach ($types as $type): ?>
							<option value="<?php echo $type->user_type_id; ?>"<?php echo $user->user_type == $type->user_type_id ? ' selected="selected"' : ''; ?>><?php echo $type->user_type_title; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group<?php echo form_error('display_name') != '' ? ' has-error' : ''; ?> has-feedback">
				<label for="display_name" class="col-sm-2 control-label">Display Name</label>
				<div class="col-sm-10">
					<input class="form-control" maxlength="15" name="display_name" type="text" value="<?php echo $user->display_name; ?>">
					<?php if (form_error('display_name') != ''): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<div class="form-error">
							<?php echo form_error('display_name'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="old_admin_thumb" class="col-sm-2 control-label">Admin Thumb</label>
				<div class="col-sm-10">
					<img alt="<?php echo $user->display_name; ?>" src="<?php echo $user->admin_thumb ? CDN_URL . $user->admin_thumb : base_url() . 'assets/img/no-photo.png'; ?>" width="72" height="72">
				</div>
			</div>
			<div class="form-group<?php echo isset($error) ? ' has-error' : ''; ?> has-feedback">
				<label for="admin_thumb" class="col-sm-2 control-label">Replace Thumb</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-default btn-file">
								Browse... <input class="form-control" name="admin_thumb" type="file">
							</span>
						</span>
						<input type="text" class="form-control btn-file-fixer" readonly>
					</div>
					<?php if (isset($error)): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<div class="form-error">
							<?php echo $error; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-group<?php echo form_error('email') != '' ? ' has-error' : ''; ?> has-feedback">
				<label for="email" class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10">
					<input class="form-control" name="email" type="email" value="<?php echo $user->email; ?>">
					<?php if (form_error('email') != ''): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<div class="form-error">
							<?php echo form_error('email'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-group<?php echo form_error('drowssap') != '' ? ' has-error' : ''; ?> has-feedback">
				<label for="drowssap" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-10">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse_password">
									Click here to change this user's password.
								</a>
							</h4>
						</div>
						<div id="collapse_password" class="panel-collapse collapse<?php echo form_error('drowssap') != '' ? ' in' : ''; ?>">
							<div class="panel-body">
								<p>Don't change passwords without permission. Leave this empty to keep current password.</p>
								<input autocomplete="off" class="form-control" name="drowssap" type="password">
								<?php if (form_error('drowssap') != ''): ?>
									<span class="glyphicon glyphicon-remove form-control-feedback"></span>
									<div class="form-error">
										<?php echo form_error('drowssap'); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="reset_hash" class="col-sm-2 control-label">Reset Hash</label>
				<div class="col-sm-10">
					<input class="form-control" name="reset_hash" type="text" value="<?php echo $user->reset_hash; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="reset_expiration" class="col-sm-2 control-label">Reset Expiration</label>
				<div class="col-sm-10">
					<div class="input-group date datetimepicker">
						<input class="form-control" name="reset_expiration" type="text" value="<?php echo $user->reset_expiration ? date('m/d/Y g:i A', $user->reset_expiration) : ''; ?>">
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
			</div>
			<?php if ($this->_user->user_type == 4): ?>
				<div class="form-group">
					<label for="funds_btc" class="col-sm-2 control-label">Funds BTC</label>
					<div class="col-sm-10">
						<input class="form-control" maxlength="10" name="funds_btc" type="text" value="<?php echo $user->funds_btc; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="funds_usd" class="col-sm-2 control-label">Funds USD</label>
					<div class="col-sm-10">
						<input class="form-control" maxlength="10" name="funds_usd" type="text" value="<?php echo $user->funds_usd; ?>">
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label for="text_number" class="col-sm-2 control-label">Cell Number</label>
				<div class="col-sm-10">
					<input class="form-control" maxlength="10" name="text_number" type="text" value="<?php echo $user->text_number ? $user->text_number : ''; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="cell_carrier" class="col-sm-2 control-label">Cell Carrier</label>
				<div class="col-sm-10">
					<select class="form-control" name="text_carrier">
						<option value="0"> - Select Carrier - </option>
						<?php foreach ($carriers as $carrier): ?>
							<option value="<?php echo $carrier->carrier_id; ?>"<?php echo $user->carrier_id == $carrier->carrier_id ? ' selected="selected"' : ''; ?>><?php echo $carrier->carrier_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="notifications" class="col-sm-2 control-label">Notifications</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label>
							<input<?php echo $user->notify_email_messages ? ' checked="checked"' : ''; ?> name="notify_email_messages" type="checkbox" value="1"> Email: When I receive a message
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input<?php echo $user->notify_email_photos ? ' checked="checked"' : ''; ?> name="notify_email_photos" type="checkbox" value="1"> Email: When I receive a photo or photoset
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input<?php echo $user->notify_email_videos ? ' checked="checked"' : ''; ?> name="notify_email_videos" type="checkbox" value="1"> Email: When I receive a video
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input<?php echo $user->notify_text_messages ? ' checked="checked"' : ''; ?> name="notify_text_messages" type="checkbox" value="1"> Text: When I receive a message
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input<?php echo $user->notify_text_photos ? ' checked="checked"' : ''; ?> name="notify_text_photos" type="checkbox" value="1"> Text: When I receive a photo or photoset
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input<?php echo $user->notify_text_videos ? ' checked="checked"' : ''; ?> name="notify_text_videos" type="checkbox" value="1"> Text: When I receive a video
						</label>
					</div>
				</div>
			</div>
			<?php if ($user->user_type == 1): ?>
				<div class="form-group">
					<label for="prefer_btc" class="col-sm-2 control-label">Prefer BTC</label>
					<div class="col-sm-10">
					<select class="form-control" name="prefer_btc">
						<option value="0"<?php echo $user->prefer_btc != 1 ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $user->prefer_btc == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($user->user_type == 2): ?>
				<div class="form-group">
					<label for="user_hd" class="col-sm-2 control-label">Set as HD</label>
					<div class="col-sm-10">
					<select class="form-control" name="user_hd">
						<option value="0"<?php echo $user->user_hd != 1 ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $user->user_hd == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label for="accept_btc" class="col-sm-2 control-label">Accept BTC</label>
					<div class="col-sm-10">
					<select class="form-control" name="accept_btc">
						<option value="0"<?php echo $user->accept_btc != 1 ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $user->accept_btc == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label for="trusted" class="col-sm-2 control-label">Trusted</label>
					<div class="col-sm-10">
					<select class="form-control" name="trusted">
						<option value="0"<?php echo $user->trusted != 1 ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $user->trusted == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label for="featured" class="col-sm-2 control-label">Front Page</label>
					<div class="col-sm-10">
					<select class="form-control" name="featured">
						<option value="0"<?php echo $user->featured != 1 ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $user->featured == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label for="user_approved" class="col-sm-2 control-label">Approved</label>
					<div class="col-sm-10">
						<select class="form-control" name="user_approved">
							<option value="0"<?php echo $user->user_approved != 1 ? ' selected="selected"' : ''; ?>>No</option>
							<option value="1"<?php echo $user->user_approved == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label for="disabled" class="col-sm-2 control-label">Hidden</label>
				<div class="col-sm-10">
					<select class="form-control" name="disabled">
						<option value="0"<?php echo $user->disabled != 1 ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $user->disabled == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="lockout" class="col-sm-2 control-label">Locked Out</label>
				<div class="col-sm-10">
					<select class="form-control" name="lockout">
						<option value="0"<?php echo $user->lockout != 1 ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $user->lockout == 1 ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="tags" class="col-sm-2 control-label">Tags</label>
				<div class="col-sm-10">
					<input class="form-control" name="tags" id="tags" placeholder="Enter tags" type="text" value="<?php echo $user->tags; ?>">
					<span class="help-block" style="margin-bottom: 0;">Enter a comma-delimited list of tags for this user.</span>
				</div>
			</div>
			<?php if ($user->user_type == 2 && $user->user_approved): ?>
				<div class="form-group">
					<label for="user_approved_by" class="col-sm-2 control-label">Approved By</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo $user->user_approved_by_name; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label for="user_approved_on" class="col-sm-2 control-label">Approved On</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo date('Y-m-d H:i:s', $user->user_approved_on); ?></p>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label for="created" class="col-sm-2 control-label">Created</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo date('Y-m-d H:i:s', $user->created); ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="last_login" class="col-sm-2 control-label">Last Login</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo date('Y-m-d H:i:s', $user->last_login); ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="profile" class="col-sm-2 control-label">Profile</label>
				<div class="col-sm-10">
					<textarea class="form-control" name="profile" rows="10"><?php echo htmlspecialchars($user->profile); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input name="password_reset" type="checkbox" value="1"> Initiate password reset
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-large btn-success">Save</button>
				</div>
			</div>
			<?php if ($this->_user->user_type == 4): ?>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<a href="<?php echo base_url(); ?>management/users/delete/<?php echo $user->user_id; ?>" onclick="return confirm('Are you sure you want to permanently delete this user?\n\nThis will remove all of the user\'s assets and is irreversible!');"><button type="button" class="btn btn-large btn-danger">Delete</button></a>
					</div>
				</div>
			<?php endif; ?>
		</form>
