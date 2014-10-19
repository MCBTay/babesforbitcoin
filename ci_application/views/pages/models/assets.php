
<div id="page-models-profile">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<div class="panel-photo">
				<div class="watermark-wrap">
					<a href="<?php echo base_url(); ?>models/profile/<?php echo $model->user_id; ?>"><img alt="<?php echo isset($model->default) ? $model->default->filename : ''; ?>" src="<?php echo isset($model->default) ? CDN_URL . 'tall-' . strtolower($model->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="210" height="254"></a>
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
            <?php foreach ($assets as $key => $asset): ?>
                <?php echo $key % 4 ? '' : '<div class="clearfix"></div>'; ?>
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
                <?php else: ?>
                    <div class="panel-photo">
                        <div class="watermark-wrap">
                            <img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
                            <?php if ($asset->asset_type == 5): ?>
                                <div class="watermark-hd<?php echo $asset->asset_hd ? ' watermark-hd-video' : ' watermark-video'; ?>"></div>
                            <?php elseif ($asset->asset_hd): ?>
                                <div class="watermark-hd"></div>
                            <?php endif; ?>
                        </div>
                        <?php if ($asset->asset_type >= 3): ?>
                            <div class="panel-photo-offline text-center"><?php echo $asset->asset_title; ?></div>
                        <?php endif; ?>
                        <?php if (isset($asset->asset_extra) && !empty($asset->asset_extra)): ?>
                            <div class="panel-photo-details text-center"><?php echo $asset->asset_extra; ?></div>
                        <?php else: ?>
                            <div class="panel-photo-details text-center">&nbsp;</div>
                        <?php endif; ?>
                        <div class="panel-photo-details text-center">$<?php echo $asset->asset_cost; ?> | &#579;<?php echo $asset->asset_cost_btc; ?></div>
                        <?php if ($asset->owned): ?>
                            <a class="button" href="<?php echo base_url(); ?>my-files/model/<?php echo $asset->user_id; ?><?php echo $asset->asset_type == 5 ? '/videos' : ''; ?>"><?php echo $asset->asset_type == 5 ? 'Open Video' : 'View Photos'; ?></a>
                        <?php else: ?>
                            <a class="button" href="<?php echo base_url(); ?>cart/add/<?php if ($asset_type = 3) { echo $asset->photoset_id; } else { echo $asset->asset_id; } ?>">Buy</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
