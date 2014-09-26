
<div id="page-upload">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2><?php echo isset($asset) ? 'Edit' : 'Upload'; ?> Photoset</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php if (validation_errors() != ''): ?>
							<div class="alert alert-danger">
								<strong>Warning:</strong> Please fix any errors noted below.
							</div>
						<?php endif; ?>
						<form action="<?php echo base_url(); ?>upload/photoset<?php echo isset($asset) ? '/' . $asset->asset_id : ''; ?>" enctype="multipart/form-data" id="upload-form" method="post">
							<div class="form-long">
								<p>
									<label for="photoset_title">Photoset Title:</label><br>
									<input class="inputbox inputbox-short" id="photoset_title" name="photoset_title" type="text" value="<?php echo isset($asset) ? set_value('photoset_title', $asset->asset_title) : set_value('photoset_title'); ?>">
								</p>
								<p>
									<label for="asset_cost">Photoset Cost (USD):</label>
									<span class="usd-wrapper">
										<input class="inputbox inputbox-short" id="asset_cost" name="asset_cost" type="text" value="<?php echo isset($asset) ? set_value('asset_cost', $asset->asset_cost) : set_value('asset_cost'); ?>">
										<span class="usd-input">$</span>
										<span class="calculate-btc">| &#579;<span class="calculate-btc-value"><?php echo number_format($this->cart_model->usd_to_btc(isset($asset) ? set_value('asset_cost', $asset->asset_cost) : set_value('asset_cost')), 6, '.', ''); ?></span></span>
									</span>
									<?php if (form_error('asset_cost') != ''): ?>
										<br>
										<span class="form-error">
											<?php echo form_error('asset_cost'); ?>
										</span>
									<?php endif; ?>
								</p>
								<div class="asset-upload">
									<label for="cover_photo">Cover Photo:</label>
									<div class="upload-progress"><div class="progress-bar"></div></div>
									<?php if (isset($cover_photo)): ?>
										<div class="upload-preview-static"><?php echo $cover_photo; ?></div>
									<?php elseif (isset($asset) && !empty($asset->filename)): ?>
										<div class="upload-preview-static">
											<img src="<?php echo CDN_URL . $asset->filename; ?>" width="536">
										</div>
									<?php else: ?>
										<div class="upload-preview-static"></div>
										<div class="button-file">
											<span>Upload New Photo</span>
											<input class="asset-upload-photoset" id="cover_photo" name="cover_photo" type="file">
										</div>
									<?php endif; ?>
								</div>
								<div class="asset-upload" style="margin-top: 15px;">
									<label for="photoset_photo">Photoset Photos:</label>
									<div class="upload-preview-static2" style="margin-top: 5px;">
										<?php if (isset($asset) && isset($asset->photos)): ?>
											<?php foreach ($asset->photos as $photo): ?>
												<p style="margin-bottom: 15px; margin-top: 0;">
													<img src="<?php echo CDN_URL . $photo->filename; ?>" width="536">
												</p>
											<?php endforeach; ?>
										<?php endif; ?>
										<?php echo isset($photoset_photos) ? $photoset_photos : ''; ?>
									</div>
									<div class="upload-progress"><div class="progress-bar2"></div></div>
									<div class="button-file-static">
										<span>Upload Another</span>
										<input class="asset-upload-photoset-photo" id="photoset_photo" multiple name="photoset_photo[]" type="file">
									</div>
									<?php if (form_error('child_uploaded_photo[]') != ''): ?>
										<span class="form-error">
											<?php echo form_error('child_uploaded_photo[]'); ?>
										</span>
									<?php endif; ?>
								</div>
								<p class="legal" style="margin-top: 15px;">These are sets that are available for purchase by all contributors, please choose private files if you wish to only send this to contributors of your choosing.</p>
								<p style="margin-top: 15px;">
									<input class="submit" id="save" name="save" type="submit" value="Save">
								</p>
								<p class="legal" style="margin-bottom: 0; margin-top: 15px;">Photosets have to be approved before they can be sold or shared.</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
