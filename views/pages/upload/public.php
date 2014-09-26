
<div id="page-upload">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Upload Public Photo</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>upload/public" enctype="multipart/form-data" id="upload-form" method="post">
							<div class="form-long">
								<div class="asset-upload">
									<label for="public_photo">Photo:</label>
									<div class="upload-progress"><div class="progress-bar"></div></div>
									<div class="upload-preview"></div>
									<div class="button-file">
										<span>Upload New Photo</span>
										<input class="asset-upload-public" id="public_photo" name="public_photo" type="file">
									</div>
								</div>
								<p style="margin-top: 15px;">
									<label for="default">
										<input id="default" name="default" type="checkbox" value="1"> Make this my profile photo
									</label>
								</p>
								<p class="legal" style="margin-top: 15px;">These photos are visible to everyone, nudity is allowed, but not recommended.</p>
								<p>
									<input class="submit" id="save" name="save" type="submit" value="Save">
								</p>
							</div>
							<input id="coords-x1" name="coords-x1" type="hidden" value="">
							<input id="coords-y1" name="coords-y1" type="hidden" value="">
							<input id="coords-x2" name="coords-x2" type="hidden" value="">
							<input id="coords-y2" name="coords-y2" type="hidden" value="">
							<input id="coords-w" name="coords-w" type="hidden" value="">
							<input id="coords-h" name="coords-h" type="hidden" value="">
						</form>
					</div>
				</div>
			</div>
			<div class="panel" style="margin-top: 15px;">
				<div class="panel-title">
					<h2>Your Public Photos</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php foreach ($public as $key => $asset): ?>
							<div class="panel-photo"<?php echo $key % 3 ? '' : ' style="padding-left: 0;"'; ?>>
								<a class="fancybox" rel="public" href="<?php echo !empty($asset->filename) ? CDN_URL . $asset->filename : base_url() . 'assets/img/no-photo.png'; ?>">
									<div class="watermark-wrap">
										<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
										<?php if ($asset->asset_hd): ?>
											<div class="watermark-hd"></div>
										<?php endif; ?>
									</div>
								</a>
								<a class="button" href="<?php echo base_url(); ?>upload/set_default/<?php echo $asset->asset_id; ?>">Set as Profile Photo</a>
								<a class="button" href="<?php echo base_url(); ?>upload/remove/<?php echo $asset->asset_id; ?>">Remove from site</a>
							</div>
						<?php endforeach; ?>
						<?php if (!$public): ?>
							<p>No public photos found.</p>
						<?php endif; ?>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
