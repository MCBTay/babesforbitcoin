
		<div class="page-header">
			<h2>Add User</h2>
		</div>
		<?php if (validation_errors() != '' || isset($error)): ?>
			<div class="alert alert-danger">
				<span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp; <strong>Warning:</strong> Error saving user. Please see the errors below.
			</div>
		<?php endif; ?>
		<p><a href="<?php echo base_url(); ?>management/users" class="btn btn-primary back">Back to list of users</a></p>
		<form action="<?php echo base_url(); ?>management/users/add" class="form-horizontal" enctype="multipart/form-data" method="post" role="form">
			<div class="form-group">
				<label for="user_type" class="col-sm-2 control-label">Type</label>
				<div class="col-sm-10">
					<select class="form-control" name="user_type">
						<?php foreach ($types as $type): ?>
							<option value="<?php echo $type->user_type_id; ?>" <?php echo set_select('user_type', $type->user_type_id); ?>><?php echo $type->user_type_title; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group<?php echo form_error('display_name') != '' ? ' has-error' : ''; ?> has-feedback">
				<label for="display_name" class="col-sm-2 control-label">Display Name</label>
				<div class="col-sm-10">
					<input class="form-control" maxlength="15" name="display_name" type="text" value="<?php echo set_value('display_name'); ?>">
					<?php if (form_error('display_name') != ''): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<div class="form-error">
							<?php echo form_error('display_name'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-group<?php echo isset($error) ? ' has-error' : ''; ?> has-feedback">
				<label for="admin_thumb" class="col-sm-2 control-label">Admin Thumb</label>
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
					<input class="form-control" name="email" type="email" value="<?php echo set_value('email'); ?>">
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
					<input autocomplete="off" class="form-control" name="drowssap" type="password">
					<?php if (form_error('drowssap') != ''): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<div class="form-error">
							<?php echo form_error('drowssap'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="user_hd" class="col-sm-2 control-label">Set as HD</label>
				<div class="col-sm-10">
				<select class="form-control" name="user_hd">
					<option value="0"<?php echo set_select('user_hd', '0'); ?>>No</option>
					<option value="1"<?php echo set_select('user_hd', '1'); ?>>Yes</option>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label for="accept_btc" class="col-sm-2 control-label">Accept BTC</label>
				<div class="col-sm-10">
				<select class="form-control" name="accept_btc">
					<option value="0"<?php echo set_select('accept_btc', '0'); ?>>No</option>
					<option value="1"<?php echo set_select('accept_btc', '1'); ?>>Yes</option>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label for="prefer_btc" class="col-sm-2 control-label">Prefer BTC</label>
				<div class="col-sm-10">
				<select class="form-control" name="prefer_btc">
					<option value="0"<?php echo set_select('prefer_btc', '0'); ?>>No</option>
					<option value="1"<?php echo set_select('prefer_btc', '1'); ?>>Yes</option>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label for="trusted" class="col-sm-2 control-label">Trusted</label>
				<div class="col-sm-10">
				<select class="form-control" name="trusted">
					<option value="0"<?php echo set_select('trusted', '0'); ?>>No</option>
					<option value="1"<?php echo set_select('trusted', '1'); ?>>Yes</option>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label for="featured" class="col-sm-2 control-label">Front Page</label>
				<div class="col-sm-10">
				<select class="form-control" name="featured">
					<option value="0"<?php echo set_select('featured', '0'); ?>>No</option>
					<option value="1"<?php echo set_select('featured', '1'); ?>>Yes</option>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label for="user_approved" class="col-sm-2 control-label">Approved</label>
				<div class="col-sm-10">
					<select class="form-control" name="user_approved">
						<option value="0"<?php echo set_select('user_approved', '0'); ?>>No</option>
						<option value="1"<?php echo set_select('user_approved', '1'); ?>>Yes</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="disabled" class="col-sm-2 control-label">Hidden</label>
				<div class="col-sm-10">
					<select class="form-control" name="disabled">
						<option value="0"<?php echo set_select('disabled', '0'); ?>>No</option>
						<option value="1"<?php echo set_select('disabled', '1'); ?>>Yes</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="lockout" class="col-sm-2 control-label">Locked Out</label>
				<div class="col-sm-10">
					<select class="form-control" name="lockout">
						<option value="0"<?php echo set_select('lockout', '0'); ?>>No</option>
						<option value="1"<?php echo set_select('lockout', '1'); ?>>Yes</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="tags" class="col-sm-2 control-label">Tags</label>
				<div class="col-sm-10">
					<input class="form-control" name="tags" id="tags" placeholder="Enter tags" type="text" value="<?php echo set_value('tags', ''); ?>">
					<span class="help-block" style="margin-bottom: 0;">Enter a comma-delimited list of tags for this user.</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-large btn-success">Save</button>
				</div>
			</div>
		</form>
