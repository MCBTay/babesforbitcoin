
		<div class="page-header">
			<h2><?php echo $user->display_name ? $user->display_name : 'User # ' . $user->user_id; ?> - Add <?php echo $category; ?></h2>
		</div>
		<?php if (validation_errors() != '' || isset($error) || isset($verror)): ?>
			<div class="alert alert-danger">
				<span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp; <strong>Warning:</strong> Error adding <?php echo $category; ?>. Please see the errors below.
			</div>
		<?php endif; ?>
		<p class="marginbottomlarge"><a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>" class="btn btn-primary back">Back to user's gallery</a></p>
		<form action="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/add/<?php echo $type; ?><?php echo $type == 4 ? '/' . $photoset_id : ''; ?>" class="form-horizontal" enctype="multipart/form-data" method="post" role="form">
			<div class="form-group">
				<label for="user_id" class="col-sm-2 control-label">User ID</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->user_id; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="asset_type" class="col-sm-2 control-label">Type</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $category; ?></p>
				</div>
			</div>
			<?php if ($type == 4): ?>
				<div class="form-group">
					<label for="photoset_id" class="col-sm-2 control-label">Photoset ID</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo $photoset_id; ?></p>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group<?php echo form_error('asset_title') != '' ? ' has-error' : ''; ?> has-feedback">
				<label for="asset_title" class="col-sm-2 control-label">Title</label>
				<div class="col-sm-10">
					<input class="form-control" name="asset_title" type="text" value="<?php echo set_value('asset_title', ''); ?>">
					<?php if (form_error('asset_title') != ''): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<div class="form-error">
							<?php echo form_error('asset_title'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ($type == 3 || $type == 5): ?>
				<div class="form-group<?php echo form_error('asset_cost') != '' ? ' has-error' : ''; ?> has-feedback">
					<label for="asset_cost" class="col-sm-2 control-label">Cost</label>
					<div class="col-sm-10">
						<input class="form-control" name="asset_cost" type="text" value="<?php echo set_value('asset_cost', ''); ?>">
						<?php if (form_error('asset_cost') != ''): ?>
							<span class="glyphicon glyphicon-remove form-control-feedback"></span>
							<div class="form-error">
								<?php echo form_error('asset_cost'); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group<?php echo isset($error) ? ' has-error' : ''; ?> has-feedback">
				<label for="filename" class="col-sm-2 control-label"><?php echo $type == 3 | $type == 5 ? 'Cover Photo' : 'Upload File'; ?></label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-default btn-file">
								Browse... <input class="form-control" name="filename" type="file">
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
			<?php if ($type == 5): ?>
			<div class="form-group<?php echo isset($verror) ? ' has-error' : ''; ?> has-feedback">
				<label for="video" class="col-sm-2 control-label">Upload Video</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-default btn-file">
								Browse... <input class="form-control" name="video" type="file">
							</span>
						</span>
						<input type="text" class="form-control btn-file-fixer" readonly>
					</div>
					<?php if (isset($verror)): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<div class="form-error">
							<?php echo $verror; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($type == 1): ?>
				<div class="form-group">
					<label for="default" class="col-sm-2 control-label">Default</label>
					<div class="col-sm-10">
						<select class="form-control" name="default">
							<option value="0"<?php echo set_select('default', '0'); ?>>No</option>
							<option value="1"<?php echo set_select('default', '1'); ?>>Yes</option>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label for="deleted" class="col-sm-2 control-label">Deleted</label>
				<div class="col-sm-10">
					<select class="form-control" name="deleted">
						<option value="0"<?php echo set_select('deleted', '0'); ?>>No</option>
						<option value="1"<?php echo set_select('deleted', '1'); ?>>Yes</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="approved" class="col-sm-2 control-label">Approved</label>
				<div class="col-sm-10">
					<select class="form-control" name="approved">
						<option value="0"<?php echo set_select('approved', '0'); ?>>No</option>
						<option value="1"<?php echo set_select('approved', '1'); ?>>Yes</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="asset_hd" class="col-sm-2 control-label">Set as HD</label>
				<div class="col-sm-10">
					<select class="form-control" name="asset_hd">
						<option value="0"<?php echo set_select('asset_hd', '0'); ?>>No</option>
						<option value="1"<?php echo set_select('asset_hd', '1'); ?>>Yes</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="tags" class="col-sm-2 control-label">Tags</label>
				<div class="col-sm-10">
					<input class="form-control" name="tags" id="tags" placeholder="Enter tags" type="text" value="<?php echo set_value('tags', ''); ?>">
					<span class="help-block" style="margin-bottom: 0;">Enter a comma-delimited list of tags for this asset.</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-large btn-success">Add</button>
				</div>
			</div>
		</form>
