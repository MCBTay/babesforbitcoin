
			<div class="h1-header-nav">
				<h1>Search Results</h1>
				<?php if (isset($tag_id)): ?>
					<?php if ($type == 3): ?>
						<h3>Photosets | <a href="<?php echo base_url(); ?>search/tag/<?php echo $tag_id; ?>/assets/5">Videos</a></h3>
					<?php elseif ($type == 5) : ?>
						<h3><a href="<?php echo base_url(); ?>search/tag/<?php echo $tag_id; ?>/assets/3">Photosets</a> | Videos</h3>
					<?php endif; ?>
				<?php else: ?>
					<?php if ($type == 3): ?>
						<h3>Photosets | <a href="<?php echo base_url(); ?>search/results/<?php echo $keyword; ?>/assets/5">Videos</a></h3>
					<?php elseif ($type == 5) : ?>
						<h3><a href="<?php echo base_url(); ?>search/results/<?php echo $keyword; ?>/assets/3">Photosets</a> | Videos</h3>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php if ($assets): ?>
				<?php $results = FALSE; ?>
				<?php foreach ($assets as $asset): ?>
					<?php if ($type == $asset->asset_type): ?>
						<?php $results = TRUE; ?>
						<div class="panel-photo">
							<div class="watermark-wrap">
								<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
								<?php if ($asset->asset_type == 5): ?>
									<div class="watermark-hd<?php echo $asset->asset_hd ? ' watermark-hd-video' : ' watermark-video'; ?>"></div>
								<?php elseif ($asset->asset_hd): ?>
									<div class="watermark-hd"></div>
								<?php endif; ?>
							</div>
							<div class="panel-photo-offline text-center"><?php echo $asset->asset_title; ?></div>
							<div class="panel-photo-details text-center">$<?php echo $asset->asset_cost; ?> | &#579;<?php echo $asset->asset_cost_btc; ?></div>
							<a class="button" href="<?php echo base_url(); ?>cart/add/<?php echo $asset->asset_id; ?>">Buy</a>
							<?php /*
							<a class="button" href="<?php echo base_url(); ?>wishlist">Add to Wishlist</a>
							*/ ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if (!$results): ?>
					<p>No results found.</p>
				<?php endif; ?>
			<?php else: ?>
				<p>No results found.</p>
			<?php endif; ?>
