
		<div class="page-header">
			<h2>Manage IP's</h2>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-lg-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Blocked IP's</h3>
					</div>
					<div class="panel-body">
						<?php if (isset($success)): ?>
							<div class="alert alert-success">
								Blocked IP's were successfully saved.
							</div>
						<?php endif; ?>
						<form action="<?php echo base_url(); ?>management/manage/ips" class="form-horizontal" method="post" role="form">
							<p><textarea class="form-control" name="ip_addresses" rows="20"><?php echo $blocked_ips; ?></textarea></p>
							<button type="submit" class="btn btn-large btn-success">Save</button>
						</form>
					</div>
				</div>
			</div>
		</div>
