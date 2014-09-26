
		<div class="page-header">
			<h2>View User # <?php echo $user->user_id; ?></h2>
		</div>
		<p>
			<a href="<?php echo base_url(); ?>management/users" class="btn btn-primary back">Back to list of users</a>
			&nbsp;
			<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>" class="btn btn-default">View user gallery</a>
			&nbsp;
			<a href="<?php echo base_url(); ?>management/users/transactions/<?php echo $user->user_id; ?>" class="btn btn-default">Transactions</a>
		</p>
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label for="user_id" class="col-sm-2 control-label">User ID</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->user_id; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="user_type" class="col-sm-2 control-label">Type</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->user_type_title; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="display_name" class="col-sm-2 control-label">Display Name</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->display_name; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="admin_thumb" class="col-sm-2 control-label">Admin Thumb</label>
				<div class="col-sm-10">
					<p class="form-control-static"><img alt="<?php echo $user->display_name; ?>" src="<?php echo $user->admin_thumb ? CDN_URL . $user->admin_thumb : base_url() . 'assets/img/no-photo.png'; ?>" width="72" height="72"></p>
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->email; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="reset_hash" class="col-sm-2 control-label">Reset Hash</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->reset_hash; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="reset_expiration" class="col-sm-2 control-label">Reset Expiration</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->reset_expiration ? date('Y-m-d H:i:s', $user->reset_expiration) : 'n/a'; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="funds_btc" class="col-sm-2 control-label">Funds BTC</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->funds_btc; ?>&#579;TC</p>
				</div>
			</div>
			<div class="form-group">
				<label for="funds_usd" class="col-sm-2 control-label">Funds USD</label>
				<div class="col-sm-10">
					<p class="form-control-static">$<?php echo $user->funds_usd; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="text_number" class="col-sm-2 control-label">Cell Number</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->text_number ? $user->text_number : ''; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="cell_carrier" class="col-sm-2 control-label">Cell Carrier</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->text_carrier ? $user->carrier_name : ''; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="notifications" class="col-sm-2 control-label">Notifications</label>
				<div class="col-sm-10">
					<p class="form-control-static">
						<span class="glyphicon glyphicon-<?php echo $user->notify_email_messages ? 'ok' : 'remove'; ?>"></span> Email: When I receive a message<br />
						<span class="glyphicon glyphicon-<?php echo $user->notify_email_photos ? 'ok' : 'remove'; ?>"></span> Email: When I receive a photo or photoset<br />
						<span class="glyphicon glyphicon-<?php echo $user->notify_email_videos ? 'ok' : 'remove'; ?>"></span> Email: When I receive a video<br />
						<span class="glyphicon glyphicon-<?php echo $user->notify_text_messages ? 'ok' : 'remove'; ?>"></span> Text: When I receive a message<br />
						<span class="glyphicon glyphicon-<?php echo $user->notify_text_photos ? 'ok' : 'remove'; ?>"></span> Text: When I receive a photo or photoset<br />
						<span class="glyphicon glyphicon-<?php echo $user->notify_text_videos ? 'ok' : 'remove'; ?>"></span> Text: When I receive a video
					</p>
				</div>
			</div>
			<?php if ($user->user_type == 1): ?>
				<div class="form-group">
					<label for="prefer_btc" class="col-sm-2 control-label">Prefer BTC</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo $user->prefer_btc ? 'Yes' : 'No'; ?></p>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($user->user_type == 2): ?>
				<div class="form-group">
					<label for="user_hd" class="col-sm-2 control-label">Set as HD</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo $user->user_hd ? 'Yes' : 'No'; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label for="accept_btc" class="col-sm-2 control-label">Accept BTC</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo $user->accept_btc ? 'Yes' : 'No'; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label for="trusted" class="col-sm-2 control-label">Trusted</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo $user->trusted ? 'Yes' : 'No'; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label for="featured" class="col-sm-2 control-label">Front Page</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo $user->featured ? 'Yes' : 'No'; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label for="user_approved" class="col-sm-2 control-label">Approved</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?php echo $user->user_approved ? 'Yes' : 'No'; ?></p>
					</div>
				</div>
				<?php if ($user->user_approved == 0): ?>
					<div class="form-group">
						<label for="approved_quick" class="col-sm-2 control-label">Approve Now</label>
						<div class="col-sm-10">
							<p class="form-control-static"><a href="<?php echo base_url(); ?>management/users/approve/<?php echo $user->user_id; ?>" class="btn btn-success" role="button">Approve</a></p>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="form-group">
				<label for="disabled" class="col-sm-2 control-label">Hidden</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->disabled ? 'Yes' : 'No'; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="lockout" class="col-sm-2 control-label">Locked Out</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->lockout ? 'Yes' : 'No'; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="tags" class="col-sm-2 control-label">Tags</label>
				<div class="col-sm-10">
					<p class="form-control-static"><?php echo $user->tags; ?></p>
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
					<p class="form-control-static"><?php echo nl2br(htmlspecialchars($user->profile)); ?></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<a href="<?php echo base_url(); ?>management/users/edit/<?php echo $user->user_id; ?>" class="btn btn-large btn-success">Edit this user</a>
				</div>
			</div>
		</form>
