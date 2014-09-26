
			<div class="h1-header-nav">
				<h1>Search Results</h1>
			</div>
			<?php if ($models): ?>
				<?php foreach ($models as $model): ?>
					<div class="panel-photo">
						<div class="watermark-wrap">
							<a href="<?php echo base_url(); ?>models/profile/<?php echo $model->user_id; ?>"><img alt="<?php echo isset($model->default) ? $model->default->filename : ''; ?>" src="<?php echo isset($model->default) ? CDN_URL . 'tall-' . strtolower($model->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="205"></a>
							<?php if ($model->user_hd): ?>
								<div class="watermark-hd"></div>
							<?php endif; ?>
						</div>
						<div class="panel-photo-<?php echo $model->online ? 'online' : 'offline'; ?>"><?php echo $model->display_name; ?></div>
						<div class="panel-photo-details"><?php echo $model->num_photos; ?> Photos | <?php echo $model->num_videos; ?> Videos</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p>No results found.</p>
			<?php endif; ?>
