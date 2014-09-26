
<div id="page-upload">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2><?php echo isset($asset) ? 'Edit' : 'Upload'; ?> Video</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php if (validation_errors() != ''): ?>
							<div class="alert alert-danger">
								<strong>Warning:</strong> Please fix any errors noted below.
							</div>
						<?php endif; ?>
						<form action="<?php echo base_url(); ?>upload/video<?php echo isset($asset) ? '/' . $asset->asset_id : ''; ?>" enctype="multipart/form-data" id="upload-form" method="post">
							<div class="form-long">
								<p>
									<label for="chosen_title">Video Title:</label><br>
									<input class="inputbox inputbox-short" id="chosen_title" name="chosen_title" type="text" value="<?php echo isset($asset) ? set_value('chosen_title', $asset->asset_title) : set_value('chosen_title'); ?>">
								</p>
								<p>
									<label for="asset_cost">Video Cost (USD):</label>
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
									<?php elseif (isset($asset)): ?>
										<div class="upload-preview-static">
											<img src="<?php echo CDN_URL . $asset->filename; ?>" width="536">
										</div>
									<?php else: ?>
										<div class="upload-preview-static"></div>
										<div class="button-file">
											<span>Upload New Photo</span>
											<input class="asset-upload-video-photo" id="cover_photo" name="cover_photo" type="file">
										</div>
									<?php endif; ?>
								</div>
								<div class="asset-upload" style="margin-top: 15px;">
									<label for="video">Video:</label>
									<?php if (isset($video)): ?>
										<div class="upload-preview-static2" style="margin-top: 5px;"><?php echo $video; ?></div>
										<div class="upload-progress"><div class="progress-bar2"></div></div>
									<?php elseif (isset($asset)): ?>
										<div class="upload-preview-static2" style="margin-top: 5px;">
											<video controls="controls" style="display: block; width: 100%; max-width: 100%; height: auto;">
												<source src="<?php echo CDN_URL . $asset->video; ?>" type="<?php echo $asset->mimetype; ?>">
												<!-- Flash fallback for non-HTML5 browsers without JavaScript -->
												<object data="<?php echo base_url(); ?>assets/js/mediaelement/flashmediaelement.swf" style="display: block; width: 100%; max-width: 100%; height: auto;" type="application/x-shockwave-flash">
													<param name="movie" value="<?php echo base_url(); ?>assets/js/mediaelement/flashmediaelement.swf">
													<param name="flashvars" value="controls=true&amp;file=<?php echo urlencode(CDN_URL . $asset->video); ?>">
													<!-- Image as a last resort -->
													<img class="img-responsive" src="<?php echo CDN_URL . $asset->filename; ?>" title="No video playback capabilities">
												</object>
											</video>
										</div>
										<div class="upload-progress"><div class="progress-bar2"></div></div>
									<?php else: ?>
										<div class="upload-preview-static2" style="margin-top: 5px;"></div>
										<div class="upload-progress"><div class="progress-bar2"></div></div>
										<div class="button-file-static">
											<span>Upload New Video</span>
											<input class="asset-upload-video" id="video" name="video" type="file">
										</div>
									<?php endif; ?>
									<?php if (form_error('uploaded_video') != ''): ?>
										<span class="form-error">
											<?php echo form_error('uploaded_video'); ?>
										</span>
									<?php endif; ?>
								</div>
								<p class="legal" style="margin-top: 15px;">These are videos that are available for purchase by all contributors, please choose private files if you wish to only send this to contributors of your choosing.</p>
								<p style="margin-top: 15px;">
									<?php if (isset($asset)): ?>
										<input class="submit" id="save" name="save" type="submit" value="Save">
									<?php else: ?>
										<input class="submit" id="save-when-ready" name="save" type="submit" value="Save">
									<?php endif; ?>
								</p>
								<p class="legal" style="margin-bottom: 0; margin-top: 15px;">Videos have to be approved before they can be sold or shared.</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
