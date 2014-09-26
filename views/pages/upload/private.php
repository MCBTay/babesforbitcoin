
<div id="page-upload">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Upload Private Photo</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>upload/private" enctype="multipart/form-data" id="upload-form" method="post">
							<div class="form-long">
								<div class="asset-upload">
									<label for="private_photo">Photo:</label>
									<div class="upload-progress"><div class="progress-bar"></div></div>
									<div class="upload-preview-static"></div>
									<div class="button-file">
										<span>Upload New Photo</span>
										<input class="asset-upload-private" id="private_photo" multiple name="private_photo[]" type="file">
									</div>
								</div>
								<p class="legal" style="margin-top: 15px;">This is for photos and videos that you can send directly to contributors, only you can see these.</p>
								<p style="margin-top: 15px;">
									<input class="submit" id="save" name="save" type="submit" value="Save">
								</p>
								<p class="legal" style="margin-bottom: 0; margin-top: 15px;">Private Photos have to be approved before they can be shared.</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
