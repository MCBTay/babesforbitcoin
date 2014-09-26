
			<div class="h1-header-nav">
				<h1>Search Results</h1>
			</div>
			<?php if ($contributors): ?>
				<?php foreach ($contributors as $contributor): ?>
					<div class="panel-photo">
						<div class="watermark-wrap">
							<a href="<?php echo base_url(); ?>contributors/profile/<?php echo $contributor->user_id; ?>"><img alt="<?php echo isset($contributor->default) ? $contributor->default->filename : ''; ?>" src="<?php echo isset($contributor->default) ? CDN_URL . 'sml-' . strtolower($contributor->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="205"></a>
							<?php if ($contributor->user_hd): ?>
								<div class="watermark-hd"></div>
							<?php endif; ?>
						</div>
						<div class="panel-photo-<?php echo $contributor->online ? 'online' : 'offline'; ?>"><?php echo $contributor->display_name; ?></div>
						<div class="panel-photo-details"><?php echo $contributor->num_photos; ?> Photos</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p>No results found.</p>
			<?php endif; ?>
