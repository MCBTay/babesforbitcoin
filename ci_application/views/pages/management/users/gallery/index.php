
		<div class="page-header">
			<h2><?php echo $user->display_name ? $user->display_name : 'User # ' . $user->user_id; ?>'s Gallery</h2>
		</div>
		<p class="marginbottomlarge">
			<a href="<?php echo base_url(); ?>management/users" class="btn btn-primary back">Back to list of users</a>
			&nbsp;
			<a href="<?php echo base_url(); ?>management/users/view/<?php echo $user->user_id; ?>" class="btn btn-default">View user</a>
		</p>
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-6 col-sm-3">
					<div class="thumbnail">
						<div class="caption">
							<h3 class="thumbnail-title">Public Photos</h3>
							<p class="thumbnail-details">
								<?php echo $stats[1]->created; ?> since <?php echo $stats[1]->since ? date('M j Y', $stats[1]->since) : 'never'; ?>.<br>
								<?php echo $stats[1]->awaiting; ?> awaiting approval.<br>
								<?php echo $stats[1]->approved; ?> already approved.<br>
								<?php echo $stats[1]->deleted; ?> deleted by model.<br>
							</p>
							<p class="thumbnail-cta">
								<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/view/1" class="btn btn-primary" role="button">View</a>
								&nbsp;
								<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/add/1" class="btn btn-success" role="button">Add</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3">
					<div class="thumbnail">
						<div class="caption">
							<h3 class="thumbnail-title">Private Photos</h3>
							<p class="thumbnail-details">
								<?php echo $stats[2]->created; ?> since <?php echo $stats[2]->since ? date('M j Y', $stats[2]->since) : 'never'; ?>.<br>
								<?php echo $stats[2]->awaiting; ?> awaiting approval.<br>
								<?php echo $stats[2]->approved; ?> already approved.<br>
								<?php echo $stats[2]->deleted; ?> deleted by model.<br>
							</p>
							<p class="thumbnail-cta">
								<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/view/2" class="btn btn-primary" role="button">View</a>
								&nbsp;
								<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/add/2" class="btn btn-success" role="button">Add</a>
							</p>
						</div>
					</div>
				</div>
				<?php if ($user->user_type == 2): ?>
					<div class="clearfix visible-xs"></div>
					<div class="col-xs-6 col-sm-3">
						<div class="thumbnail">
							<div class="caption">
								<h3 class="thumbnail-title">Photosets</h3>
								<p class="thumbnail-details">
									<?php echo $stats[3]->created; ?> since <?php echo $stats[3]->since ? date('M j Y', $stats[3]->since) : 'never'; ?>.<br>
									<?php echo $stats[3]->awaiting; ?> awaiting approval.<br>
									<?php echo $stats[3]->approved; ?> already approved.<br>
									<?php echo $stats[3]->deleted; ?> deleted by model.<br>
								</p>
								<p class="thumbnail-cta">
									<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/view/3" class="btn btn-primary" role="button">View</a>
									&nbsp;
									<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/add/3" class="btn btn-success" role="button">Add</a>
								</p>
							</div>
						</div>
					</div>
					<div class="col-xs-6 col-sm-3">
						<div class="thumbnail">
							<div class="caption">
								<h3 class="thumbnail-title">Videos</h3>
								<p class="thumbnail-details">
									<?php echo $stats[5]->created; ?> since <?php echo $stats[5]->since ? date('M j Y', $stats[5]->since) : 'never'; ?>.<br>
									<?php echo $stats[5]->awaiting; ?> awaiting approval.<br>
									<?php echo $stats[5]->approved; ?> already approved.<br>
									<?php echo $stats[5]->deleted; ?> deleted by model.<br>
								</p>
								<p class="thumbnail-cta">
									<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/view/5" class="btn btn-primary" role="button">View</a>
									&nbsp;
									<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/add/5" class="btn btn-success" role="button">Add</a>
								</p>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="pull-right">
				<button type="button" class="btn btn-large btn-danger" data-toggle="modal" data-target="#jModal<?php echo $user->user_id; ?>">Delete entire gallery</button>
			</div>
		</div>
		<div class="modal fade" id="jModal<?php echo $user->user_id; ?>">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Are you sure?</h4>
					</div>
					<div class="modal-body">
						<p>Are you sure you want to PERMANENTLY delete this entire gallery?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/delete" class="btn btn-danger" role="button">Delete entire gallery</a>
					</div>
				</div>
			</div>
		</div>
