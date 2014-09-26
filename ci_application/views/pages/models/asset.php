
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
			<div class="model-stats">
				<dl>
					<dt><a href="<?php echo base_url(); ?>models/photosets/<?php echo $model->user_id; ?>"><?php echo $model->assets[3]; ?></a></dt>
						<dd><a href="<?php echo base_url(); ?>models/photosets/<?php echo $model->user_id; ?>">Photosets</a></dd>
					<dt><a href="<?php echo base_url(); ?>models/videos/<?php echo $model->user_id; ?>"><?php echo $model->assets[5]; ?></a></dt>
						<dd><a href="<?php echo base_url(); ?>models/videos/<?php echo $model->user_id; ?>">Videos</a></dd>
					<dt><a href="<?php echo base_url(); ?>my-files/model/<?php echo $model->user_id; ?>"><?php echo $owned; ?></a></dt>
						<dd><a href="<?php echo base_url(); ?>my-files/model/<?php echo $model->user_id; ?>">Owned</a></dd>
				</dl>
			</div>
			<div class="clearfix"></div>
			<?php /*
			<div class="model-buttons">
				<p><a href="javascript:alert('This feature is coming soon!');"><button class="button"><span class="jshown">Add to Favorites</span><span class="jhidden">Coming Soon</span></button></a></p>
				<p><a href="javascript:alert('This feature is coming soon!');"><button class="button"><span class="jshown">Invite to Chat</span><span class="jhidden">Coming Soon</span></button></a></p>
			</div>
			*/ ?>
		</div>
		<div class="content-right content-right-large copy">
			<?php if ($asset->asset_type == 5): ?>
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
			<?php elseif (!empty($asset->filename)): ?>
				<a class="fancybox" rel="photoset<?php echo $asset->asset_id; ?>" href="<?php echo !empty($asset->filename) ? CDN_URL . $asset->filename : base_url() . 'assets/img/no-photo.png'; ?>">
					<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo CDN_URL . $asset->filename; ?>" width="745">
				</a>
			<?php endif; ?>
			<?php if (isset($asset->photos)): ?>
				<?php foreach ($asset->photos as $key => $photo): ?>
					<div class="panel-photo" style="padding: 21px <?php echo $key % 2 ? '0' : '1px'; ?> 0 <?php echo $key % 4 ? '21px' : '0'; ?>;">
						<div class="watermark-wrap">
							<img alt="<?php echo $photo->asset_title; ?>" src="<?php echo !empty($photo->filename) ? CDN_URL . 'sml-' . strtolower($photo->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
							<?php if ($photo->asset_hd): ?>
								<div class="watermark-hd"></div>
							<?php endif; ?>
						</div>
						<a class="button fancybox" rel="photoset<?php echo $asset->asset_id; ?>" href="<?php echo !empty($photo->filename) ? CDN_URL . $photo->filename : base_url() . 'assets/img/no-photo.png'; ?>">Enlarge</a>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
