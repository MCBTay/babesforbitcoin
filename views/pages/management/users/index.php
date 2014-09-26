
		<div class="page-header">
			<h2>Users</h2>
		</div>
		<div class="alert alert-info">
			Total entries: <strong><?php echo $total; ?></strong>
		</div>
		<div class="pull-left">
			<form action="<?php echo base_url(); ?>management/users" class="form-inline" id="filter-form" role="form">
				<div class="form-group">
					<label>Filter:</label>
					&nbsp;
					User Type:
					<select class="form-control input-sm" id="filter_user_type" name="filter_user_type">
						<option value="0"> - Select - </option>
						<?php foreach ($types as $type): ?>
							<option value="<?php echo $type->user_type_id; ?>"<?php echo $filter_type == $type->user_type_id ? ' selected="selected"' : ''; ?>><?php echo $type->user_type_title; ?></option>
						<?php endforeach; ?>
					</select>
					&nbsp;
					Approved:
					<select class="form-control input-sm" id="filter_user_approved" name="filter_approved">
						<option value="all"> - Select - </option>
						<option value="0"<?php echo $filter_approved === '0' ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $filter_approved === '1' ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
					&nbsp;
					Hidden:
					<select class="form-control input-sm" id="filter_disabled" name="filter_disabled">
						<option value="all"> - Select - </option>
						<option value="0"<?php echo $filter_disabled === '0' ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $filter_disabled === '1' ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
					&nbsp;
					Locked Out:
					<select class="form-control input-sm" id="filter_lockout" name="filter_lockout">
						<option value="all"> - Select - </option>
						<option value="0"<?php echo $filter_lockout === '0' ? ' selected="selected"' : ''; ?>>No</option>
						<option value="1"<?php echo $filter_lockout === '1' ? ' selected="selected"' : ''; ?>>Yes</option>
					</select>
				</div>
				<input id="hidden-type" name="hidden-type" type="hidden" value="<?php echo $filter_type; ?>">
				<input id="hidden-approved" name="hidden-approved" type="hidden" value="<?php echo $filter_approved; ?>">
				<input id="hidden-disabled" name="hidden-disabled" type="hidden" value="<?php echo $filter_disabled; ?>">
				<input id="hidden-lockout" name="hidden-lockout" type="hidden" value="<?php echo $filter_lockout; ?>">
				<input id="hidden-sort" name="hidden-sort" type="hidden" value="<?php echo $sort; ?>">
				<input id="hidden-dir" name="hidden-dir" type="hidden" value="<?php echo $dir; ?>">
			</form>
		</div>
		<?php if ($this->_user->user_type == 4): ?>
			<div class="pull-right">
				<a href="<?php echo base_url(); ?>management/users/add" class="btn btn-success" role="button">Add User</a>
			</div>
		<?php endif; ?>
		<table class="table table-responsive">
			<thead>
				<tr>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/users/<?php echo $filter; ?>/user_id/<?php echo $sort == 'user_id' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						ID
						<?php if ($sort == 'user_id' || $sort == ''): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/users/<?php echo $filter; ?>/display_name/<?php echo $sort == 'display_name' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Display Name
						<?php if ($sort == 'display_name'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/users/<?php echo $filter; ?>/user_type_title/<?php echo $sort == 'user_type_title' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Type
						<?php if ($sort == 'user_type_title'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/users/<?php echo $filter; ?>/email/<?php echo $sort == 'email' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Email
						<?php if ($sort == 'email'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/users/<?php echo $filter; ?>/last_login/<?php echo $sort == 'last_login' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Last Login
						<?php if ($sort == 'last_login'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user): ?>
					<tr>
						<td><?php echo $user->user_id; ?></td>
						<td>
							<img alt="<?php echo $user->display_name; ?>" src="<?php echo $user->admin_thumb ? CDN_URL . $user->admin_thumb : base_url() . 'assets/img/no-photo.png'; ?>" width="72" height="72">
							<?php echo $user->display_name; ?>
						</td>
						<td><?php echo $user->user_type_title; ?></td>
						<td><?php echo $user->email; ?></td>
						<td><?php echo date('Y-m-d H:i:s', $user->last_login); ?></td>
						<td>
							<?php if ($user->user_approved): ?>
								<a href="#" class="jpopover" data-container="body" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" data-title="Approved" data-content="<?php echo $user->user_approved_by_name; ?> approved this user&lt;br&gt;on <?php echo date('Y-m-d', $user->user_approved_on); ?> at <?php echo date('H:i:s', $user->user_approved_on); ?>."><span class="glyphicon glyphicon-ok"></span></a>
							<?php else: ?>
								<span class="glyphicon glyphicon-darkened glyphicon-ok"></span>
							<?php endif; ?>
							<?php if ($user->disabled): ?>
								<a href="#" class="jtooltip" data-toggle="tooltip" title="Hidden"><span class="glyphicon glyphicon-ban-circle"></span></a>
							<?php else: ?>
								<span class="glyphicon glyphicon-darkened glyphicon-ban-circle"></span>
							<?php endif; ?>
							<?php if ($user->lockout): ?>
								<a href="#" class="jtooltip" data-toggle="tooltip" title="Locked Out"><span class="glyphicon glyphicon-lock"></span></a>
							<?php else: ?>
								<span class="glyphicon glyphicon-darkened glyphicon-lock"></span>
							<?php endif; ?>
							<?php if ($user->user_type == 2): ?>
								<?php if ($user->featured): ?>
									<a href="#" class="jtooltip" data-toggle="tooltip" title="Front Page"><span class="glyphicon glyphicon-home"></span></a>
								<?php else: ?>
									<span class="glyphicon glyphicon-darkened glyphicon-home"></span>
								<?php endif; ?>
							<?php endif; ?>
						</td>
						<td>
							&bull; <a href="<?php echo base_url(); ?>management/users/view/<?php echo $user->user_id; ?>" class="view">View</a>
							<br>
							&bull; <a href="<?php echo base_url(); ?>management/users/edit/<?php echo $user->user_id; ?>" class="edit">Edit</a>
							<br>
							&bull; <a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>" class="gallery">Gallery</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="text-center">
			<?php echo $pagination; ?>
		</div>
