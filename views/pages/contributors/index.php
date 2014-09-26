
<div id="page-contributors">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<div class="panel">
				<div class="panel-title">
					<h2>Sort By</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>contributors" method="post">
							<div class="form-group">
								<div class="text-left" style="margin-top: 0;">
									<select id="sort" name="sort">
										<option value="default"<?php echo $sort == 'default' ? ' selected="selected"' : ''; ?>>Most Recent Login</option>
										<option value="asset_created"<?php echo $sort == 'asset_created' ? ' selected="selected"' : ''; ?>>Newest Photo/Video</option>
										<option value="name_asc"<?php echo $sort == 'name_asc' ? ' selected="selected"' : ''; ?>>Name Ascending</option>
										<option value="name_desc"<?php echo $sort == 'name_desc' ? ' selected="selected"' : ''; ?>>Name Descending</option>
									</select>
								</div>
							</div>
							<div class="text-right">
								<input class="button" name="filter" type="submit" value="Sort">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="panel-photo-online" style="margin-top: 15px;">*Online Now</div>
		</div>
		<div class="content-right content-right-large copy">
			<?php if ($contributors): ?>
				<?php foreach ($contributors as $contributor): ?>
					<div class="panel-photo">
						<div class="watermark-wrap">
							<a href="<?php echo base_url(); ?>contributors/profile/<?php echo $contributor->user_id; ?>"><img alt="<?php echo isset($contributor->default) ? $contributor->default->filename : ''; ?>" src="<?php echo isset($contributor->default) ? CDN_URL . 'tall-' . strtolower($contributor->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="205"></a>
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
		</div>
		<div class="clearfix"></div>
	</div>
</div>
