
		<div class="page-header">
			<h2>Dashboard</h2>
		</div>
		<div class="row" id="dashboard">
			<div class="col-xs-12 col-sm-6 col-lg-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Unapproved Models</h3>
					</div>
					<div class="panel-body">
						<table class="jtable">
							<thead>
								<tr>
									<th>User</th>
									<th>Joined</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($users as $user): ?>
									<tr>
										<td>
											<a href="<?php echo base_url(); ?>management/users/view/<?php echo $user->user_id; ?>"><?php echo $user->display_name ? $user->display_name : 'User # ' . $user->user_id; ?></a>
										</td>
										<td>
											<?php echo date('M j Y', $user->created); ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-lg-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Unapproved Assets by User</h3>
					</div>
					<div class="panel-body">
						<table class="jtable">
							<thead>
								<tr>
									<th>User</th>
									<th>Assets</th>
									<th>Since</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($assets_users as $user): ?>
									<tr>
										<td>
											<?php echo $user['user']->display_name ? $user['user']->display_name : 'User # ' . $user['user']->user_id; ?>
										</td>
										<td>
											<nobr>
												<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user['user']->user_id; ?>/view/1" class="jtooltip" data-toggle="tooltip" title="Public Photos"><?php echo $user[1]; ?></a>
												|
												<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user['user']->user_id; ?>/view/2" class="jtooltip" data-toggle="tooltip" title="Private Photos"><?php echo $user[2]; ?></a>
												<?php if ($user['user']->user_type == 2): ?>
													|
													<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user['user']->user_id; ?>/view/3" class="jtooltip" data-toggle="tooltip" title="Photosets"><?php echo $user[3]; ?></a>
												<?php endif; ?>
												<?php if ($user['user']->user_type == 2): ?>
													|
													<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user['user']->user_id; ?>/view/3" class="jtooltip" data-toggle="tooltip" title="Photoset Photos"><?php echo $user[4]; ?></a>
												<?php endif; ?>
												<?php if ($user['user']->user_type == 2): ?>
													|
													<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user['user']->user_id; ?>/view/5" class="jtooltip" data-toggle="tooltip" title="Videos"><?php echo $user[5]; ?></a>
												<?php endif; ?>
											</nobr>
										</td>
										<td>
											<?php echo date('M j Y', $user['since']); ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-lg-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Recently Approved Assets</h3>
					</div>
					<div class="panel-body">
						<table class="jtable">
							<tbody>
								<?php foreach ($recent as $item): ?>
									<tr>
										<td>
											<a class="<?php echo $item->age; ?>" href="<?php echo base_url(); ?>management/users/gallery/<?php echo $item->user_id; ?>/view/<?php echo $item->asset_type; ?>"<?php echo $item->asset_type >= 3 ? ' style="color: #3e8f3e;"' : ''; ?>>
												<?php echo $item->moderator; ?> approved <?php echo $item->total; ?> <?php echo $item->asset_type_title; ?><?php echo $item->total > 1 ? 's' : ''; ?> of <?php echo $item->user; ?>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
