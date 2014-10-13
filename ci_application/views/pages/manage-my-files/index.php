
<div id="page-my-files">
	<div class="content-wrapper">
		<?php if (isset($send)): ?>
			<h2>Send to Fan</h2>
			<?php if (!$this->_user->user_approved): ?>
				<div class="alert alert-danger">
					<strong>Warning:</strong> Your account must be approved before you can send files to fans.
				</div>
			<?php endif; ?>
			<form action="<?php echo base_url(); ?>manage-my-files/send/<?php echo $contributor_id; ?>" method="post">
				<div id="send-photos"></div>
				<div class="clearfix"></div>
				<div class="form-long">
					<p>
						Fan: <?php echo $contrib->display_name ? $contrib->display_name : 'Fan # ' . $contrib->user_id; ?>
					</p>
					<p class="legal">
						Only approved files are available to be sent to fans. Approval typically takes about an hour.
					</p>
					<p id="hidden_send" style="display: none;">
						<input class="submit" id="send" name="send" type="submit" value="Send">
					</p>
				</div>
			</form>
		<?php else: ?>
			<div class="h1-header-nav h1-header-nav-js">
				<h2 class="normal">Manage My Files</h2>
				<h3><a href="<?php echo base_url(); ?>upload"><button class="button">Upload New Files</button></a></h3>
			</div>
			<?php if (isset($sent)): ?>
				<div class="alert alert-danger">
					<strong>Success:</strong> Your files have been sent successfully.
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_user->user_approved || !isset($send)): ?>
			<div class="accordion-closed">
				<?php if (!isset($send)): ?>
					<h3>Public Photos</h3>
					<div>
						<?php foreach ($public as $asset): ?>
							<div class="panel-photo">
								<a class="fancybox" rel="public" href="<?php echo !empty($asset->filename) ? CDN_URL . $asset->filename : base_url() . 'assets/img/no-photo.png'; ?>">
									<div class="watermark-wrap">
										<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
										<?php if ($asset->asset_hd): ?>
											<div class="watermark-hd"></div>
										<?php endif; ?>
									</div>
								</a>
								<a class="button" href="<?php echo base_url(); ?>manage-my-files/set_default/<?php echo $asset->asset_id; ?>">Set as Profile Photo</a>
								<a class="button" href="<?php echo base_url(); ?>manage-my-files/remove/<?php echo $asset->asset_id; ?>">Remove from site</a>
							</div>
						<?php endforeach; ?>
						<?php if (!$public): ?>
							<p>No public photos found.</p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<h3>Private Photos</h3>
				<div>
					<?php foreach ($private as $asset): ?>
						<?php if ($asset->approved || !isset($send)): ?>
							<div class="panel-photo">
								<a class="fancybox" rel="private" href="<?php echo !empty($asset->filename) ? CDN_URL . $asset->filename : base_url() . 'assets/img/no-photo.png'; ?>">
									<div class="watermark-wrap">
										<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
										<?php if ($asset->asset_hd): ?>
											<div class="watermark-hd"></div>
										<?php endif; ?>
									</div>
								</a>
								<?php if (isset($send)): ?>
									<a class="button send-contrib" id="send-contrib<?php echo $asset->asset_id; ?>" href="<?php echo base_url(); ?>manage-my-files/send/<?php echo $asset->asset_id; ?>">Send to Fan</a>
								<?php else: ?>
									<a class="button" href="<?php echo base_url(); ?>manage-my-files/remove/<?php echo $asset->asset_id; ?>">Remove from site</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php if (!$private): ?>
						<p>No private photos found.</p>
					<?php endif; ?>
				</div>
				<h3>Photosets</h3>
				<div>
					<?php foreach ($photosets as $asset): ?>
						<?php if ($asset->approved || !isset($send)): ?>
							<div class="clearfix"></div>
							<h3 style="margin-top: 0;"><?php echo $asset->asset_title; ?></h3>
							<?php if (!$asset->approved): ?>
								<p class="legal">This photoset will need to be approved before you can share/sell it.</p>
							<?php endif; ?>
							<?php if (!isset($send)): ?>
								<p class="legal">If you wish to delete this photoset, please <a href="<?php echo base_url(); ?>contact/delete_photoset/<?php echo $asset->asset_id; ?>" style="color: #c3c3c3;">click here</a>.</p>
								<div class="manage-asset">
									<?php if (empty($asset->filename)): ?>
										<a class="button" href="<?php echo base_url(); ?>upload/photoset/<?php echo $asset->asset_id; ?>">Add Cover Photo</a>
									<?php endif; ?>
									<a class="button" href="<?php echo base_url(); ?>upload/photoset/<?php echo $asset->photoset_id; ?>">Edit Set</a>
								</div>
							<?php endif; ?>
							<div class="panel-photo">
                                <?php if ($asset->is_cover_photo): ?>
                                    <a class="fancybox" rel="photoset<?php echo $asset->asset_id; ?>" href="<?php echo !empty($asset->filename) ? CDN_URL . $asset->filename : base_url() . 'assets/img/no-photo.png'; ?>">
                                        <div class="watermark-wrap">
                                            <img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
                                            <?php if ($asset->asset_hd): ?>
                                                <div class="watermark-hd"></div>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endif; ?>
								<?php if (isset($send)): ?>
									<div class="panel-photo-offline text-center">Includes entire set</div>
									<a class="button send-contrib" id="send-contrib<?php echo $asset->asset_id; ?>" href="<?php echo base_url(); ?>maange-my-files/send/<?php echo $asset->asset_id; ?>">Send to Fan</a>
								<?php else: ?>
									<div class="panel-photo-offline text-center"><?php if ($asset->approved): ?><span class="unapproved">*</span><?php endif; ?><?php echo $asset->asset_title; ?></div>
									<div class="panel-photo-details text-center">$<?php echo $asset->asset_cost; ?> | &#579;<?php echo $asset->asset_cost_btc; ?></div>
								<?php endif; ?>
							</div>
							<?php foreach ($asset->photos as $photo): ?>
                                <?php if (!$photo->is_cover_photo): ?>
                                    <div class="panel-photo">
                                        <a class="fancybox" rel="photoset<?php echo $asset->asset_id; ?>" href="<?php echo !empty($photo->filename) ? CDN_URL . $photo->filename : base_url() . 'assets/img/no-photo.png'; ?>">
                                            <div class="watermark-wrap">
                                                <img alt="<?php echo $photo->asset_title; ?>" src="<?php echo !empty($photo->filename) ? CDN_URL . 'sml-' . strtolower($photo->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
                                                <?php if ($photo->asset_hd): ?>
                                                    <div class="watermark-hd"></div>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                        <div class="panel-photo-offline text-center">&nbsp;</div>
                                        <?php if (isset($send)): ?>
                                            <a class="button send-contrib sub-send-contrib<?php echo $asset->asset_id; ?>" id="send-contrib<?php echo $photo->asset_id; ?>" href="<?php echo base_url(); ?>manage-my-files/send/<?php echo $photo->asset_id; ?>">Send to Fan</a>
                                        <?php else: ?>
                                            <div class="panel-photo-details text-center">&nbsp;</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php if (!$photosets): ?>
						<p>No photosets found.</p>
					<?php endif; ?>
				</div>
				<h3>Videos</h3>
				<div>
					<?php foreach ($videos as $asset): ?>
						<?php if ($asset->approved || !isset($send)): ?>
							<div class="panel-photo">
								<a class="open-fancybox" href="#video<?php echo $asset->asset_id; ?>">
									<div id="video<?php echo $asset->asset_id; ?>" style="display: none;">
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
									<div class="watermark-wrap">
										<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
										<div class="watermark-hd<?php echo $asset->asset_hd ? ' watermark-hd-video' : ' watermark-video'; ?>"></div>
									</div>
								</a>
								<div class="panel-photo-offline text-center"><?php if (!$asset->approved): ?><span class="unapproved">*</span><?php endif; ?><?php echo $asset->asset_title; ?></div>
								<?php if (isset($send)): ?>
									<a class="button send-contrib" id="send-contrib<?php echo $asset->asset_id; ?>" href="<?php echo base_url(); ?>manage-my-files/send/<?php echo $asset->asset_id; ?>">Send to Fan</a>
								<?php else: ?>
									<div class="panel-photo-details text-center">$<?php echo $asset->asset_cost; ?> | &#579;<?php echo $asset->asset_cost_btc; ?></div>
									<a class="button" href="<?php echo base_url(); ?>upload/video/<?php echo $asset->asset_id; ?>">Edit Title &amp; Price</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php if (!$videos): ?>
						<p>No videos found.</p>
					<?php endif; ?>
				</div>
			</div>
			<p class="legal">Files with a blue star (<span class="unapproved">*</span>) are awaiting approval and not available to be sold or shared.</p>
		<?php endif; ?>
		<div class="clearfix"></div>
	</div>
</div>
