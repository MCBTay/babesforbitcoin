
<div id="page-models-profile">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<div class="panel-photo">
				<div class="watermark-wrap">
					<a href="<?php echo base_url(); ?>models/public/<?php echo $model->user_id; ?>"><img alt="<?php echo isset($model->default) ? $model->default->filename : ''; ?>" src="<?php echo isset($model->default) ? CDN_URL . 'tall-' . strtolower($model->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="210" height="254"></a>
					<?php if ($model->user_hd): ?>
						<div class="watermark-hd"></div>
					<?php endif; ?>
				</div>
				<div class="panel-photo-<?php echo $model->online ? 'online' : 'offline'; ?>"><?php echo $model->display_name; ?></div>
			</div>
			<div class="clearfix"></div>
			<ul class="model-stats">
				<li><a href="<?php echo base_url(); ?>models/photosets/<?php echo $model->user_id; ?>"><?php echo $model->assets[3]; ?> Photosets</a></li>
				<li><a href="<?php echo base_url(); ?>models/videos/<?php echo $model->user_id; ?>"><?php echo $model->assets[5]; ?> Videos</a></li>
				<li><a href="<?php echo base_url(); ?>my-files/model/<?php echo $model->user_id; ?>"><?php echo $owned; ?> Owned</a></li>
			</ul>
			<div class="clearfix"></div>
			<?php /*
			<div class="model-buttons">
				<p><a href="javascript:alert('This feature is coming soon!');"><button class="button"><span class="jshown">Add to Favorites</span><span class="jhidden">Coming Soon</span></button></a></p>
				<p><a href="javascript:alert('This feature is coming soon!');"><button class="button"><span class="jshown">Invite to Chat</span><span class="jhidden">Coming Soon</span></button></a></p>
			</div>
			*/ ?>
		</div>
		<div class="content-right content-right-large copy">
			<div class="h1-header-nav h1-header-nav-js">
				<h2 class="normal">My Files of <?php echo $model->display_name ? $model->display_name : 'Model # ' . $model->user_id; ?></h2>
				<h3><a<?php echo $show == 'videos' ? '' : ' class="active"'; ?> href="javascript:void(0);" id="show-purchased-photos">Photos</a> | <a<?php echo $show == 'videos' ? ' class="active"' : ''; ?> href="javascript:void(0);" id="show-purchased-videos">Videos</a></h3>
			</div>
			<div id="purchased-photos"<?php echo $show == 'videos' ? ' style="display: none;"' : ''; ?>>
				<h3 class="normal">Individual Photos</h3>
				<?php foreach ($photos as $asset): ?>
					<div class="panel-photo">
						<a class="fancybox" rel="private1" href="<?php echo !empty($asset->filename) ? CDN_URL . $asset->filename : base_url() . 'assets/img/no-photo.png'; ?>">
							<div class="watermark-wrap">
								<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
								<?php if ($asset->asset_hd): ?>
									<div class="watermark-hd"></div>
								<?php endif; ?>
							</div>
						</a>
						<?php /* <a class="button fancybox" rel="private2" href="<?php echo CDN_URL . $asset->filename; ?>">View Photo</a> */ ?>
					</div>
				<?php endforeach; ?>
				<?php foreach ($photosets as $asset): ?>
					<div class="clearfix"></div>
					<h3 class="normal" style="margin-top: 0;"><?php echo $asset->asset_title; ?></h3>
					<div class="panel-photo">
						<a class="fancybox" rel="photoset<?php echo $asset->asset_id; ?>_1" href="<?php echo !empty($asset->filename) ? CDN_URL . $asset->filename : base_url() . 'assets/img/no-photo.png'; ?>">
							<div class="watermark-wrap">
								<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
								<?php if ($asset->asset_hd): ?>
									<div class="watermark-hd"></div>
								<?php endif; ?>
							</div>
						</a>
						<?php /* <a class="button fancybox" rel="photoset<?php echo $asset->asset_id; ?>_2" href="<?php echo CDN_URL . $asset->filename; ?>">View Photo</a> */ ?>
					</div>
					<?php foreach ($asset->photos as $photo): ?>
						<div class="panel-photo">
							<a class="fancybox" rel="photoset<?php echo $asset->asset_id; ?>_1" href="<?php echo !empty($photo->filename) ? CDN_URL . $photo->filename : base_url() . 'assets/img/no-photo.png'; ?>">
								<div class="watermark-wrap">
									<img alt="<?php echo $photo->asset_title; ?>" src="<?php echo !empty($photo->filename) ? CDN_URL . 'sml-' . strtolower($photo->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
									<?php if ($photo->asset_hd): ?>
										<div class="watermark-hd"></div>
									<?php endif; ?>
								</div>
							</a>
							<?php /* <a class="button fancybox" rel="photoset<?php echo $asset->asset_id; ?>_2" href="<?php echo CDN_URL . $photo->filename; ?>">View Photo</a> */ ?>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
			<div id="purchased-videos"<?php echo $show == 'videos' ? '' : ' style="display: none;"'; ?>>
				<?php foreach ($videos as $asset): ?>
					<div class="panel-photo">
						<a class="open-fancybox" href="#video<?php echo $asset->asset_id; ?>">
							<div class="watermark-wrap">
								<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
								<div class="watermark-hd<?php echo $asset->asset_hd ? ' watermark-hd-video' : ' watermark-video'; ?>"></div>
							</div>
						</a>
						<div class="panel-photo-offline text-center"><?php echo $asset->asset_title; ?></div>
						<?php /* <a class="button open-fancybox" href="#video<?php echo $asset->asset_id; ?>">Open Video</a> */ ?>
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
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
