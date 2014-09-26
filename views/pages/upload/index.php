
<div id="page-upload">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2>What would you like to upload?</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php if (isset($success)): ?>
							<div class="alert alert-danger">
								<strong>Success:</strong> Your upload has been saved.
							</div>
						<?php endif; ?>
						<div class="buttons-large-fixed">
							<p><a href="<?php echo base_url(); ?>upload/public"><button class="button">Public Photos</button></a></p>
							<p><a href="<?php echo base_url(); ?>upload/private"><button class="button">Private Photos</button></a></p>
							<?php if ($this->_user->user_type > 1): ?>
								<p><a href="<?php echo base_url(); ?>upload/photoset"><button class="button">Photosets</button></a></p>
								<p><a href="<?php echo base_url(); ?>upload/video"><button class="button">Videos</button></a></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
