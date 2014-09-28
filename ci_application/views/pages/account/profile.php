
<div id="page-preferences">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Edit Profile</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>account/profile" method="post">
							<?php if (validation_errors() != ''): ?>
								<div class="alert alert-danger">
									<strong>Warning:</strong> Please fix any errors noted below.
								</div>
							<?php endif; ?>
							<?php if (isset($success)): ?>
								<div class="alert alert-danger">
									<strong>Success:</strong> Your profile has been saved.
								</div>
							<?php endif; ?>
							<div class="form-long">
								<label for="profile">Profile:</label><br>
								<textarea class="textarea" id="profile" name="profile"><?php echo htmlspecialchars($user->profile); ?></textarea>
								<?php if (form_error('profile') != ''): ?>
									<div class="form-error">
										<?php echo form_error('profile'); ?>
									</div>
								<?php endif; ?>
								<p style="margin-top: 15px;">
                                    <input class="submit" id="view" name="view" type="submit" value="View">
									<input class="submit" id="save" name="save" type="submit" value="Save">
								</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
