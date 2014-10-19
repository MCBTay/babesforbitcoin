
<div id="page-models-profile">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<div class="panel-photo">
				<div class="watermark-wrap">
					<a href="<?php echo base_url(); ?>contributors/profile/<?php echo $contributor->user_id; ?>"><img alt="<?php echo isset($contributor->default) ? $contributor->default->filename : ''; ?>" src="<?php echo isset($contributor->default) ? CDN_URL . 'tall-' . strtolower($contributor->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="210" height="254"></a>
					<?php if ($contributor->user_hd): ?>
						<div class="watermark-hd"></div>
					<?php endif; ?>
				</div>
				<div class="panel-photo-<?php echo $contributor->online ? 'online' : 'offline'; ?>"><?php echo $contributor->display_name; ?></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="content-right content-right-large copy">
			<?php foreach ($assets as $asset): ?>
				<?php if ($asset->asset_type == 1): ?>
					<div class="panel-photo">
						<a class="fancybox" rel="group" href="<?php echo !empty($asset->filename) ? CDN_URL . $asset->filename : base_url() . 'assets/img/no-photo.png'; ?>">
							<div class="watermark-wrap">
								<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
								<?php if ($asset->asset_hd): ?>
									<div class="watermark-hd"></div>
								<?php endif; ?>
							</div>
						</a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
