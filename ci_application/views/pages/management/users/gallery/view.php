
		<div class="page-header">
			<h2><?php echo $user->display_name ? $user->display_name : 'User # ' . $user->user_id; ?>'s <?php echo $category; ?>s</h2>
		</div>
		<p class="marginbottomlarge">
			<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>" class="btn btn-primary back">Back to user's gallery</a>
			&nbsp;
			<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $user->user_id; ?>/add/<?php echo $type; ?>" class="btn btn-success" role="button">Add <?php echo $category; ?></a>
		</p>
		<div class="container-fluid">
			<div class="row">
				<?php foreach ($assets as $n => $asset): ?>
					<?php if ($n % 4 == 0 && $n != 0): ?>
						</div>
						<div class="row">
					<?php endif; ?>
					<?php if ($n % 4 > 0 && $n % 2 == 0): ?>
						<div class="clearfix visible-xs"></div>
					<?php endif; ?>
					<div class="col-xs-6 col-sm-3">
						<div class="thumbnail">
							<a href="<?php echo base_url(); ?>management/assets/edit/<?php echo $asset->asset_id; ?>">
								<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo $asset->filename ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>">
							</a>
							<div class="caption">
								<h3 class="thumbnail-title"><?php echo $asset->asset_title; ?></h3>
								<p class="thumbnail-details">
									Created <?php echo date('M j Y', $asset->asset_created); ?><br>
									<?php if ($asset->approved): ?>
										<a href="#" class="jpopover" data-container="body" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" data-title="Approved" data-content="<?php echo $asset->approved_by_name; ?> approved this asset&lt;br&gt;on <?php echo date('Y-m-d', $asset->approved_on); ?> at <?php echo date('H:i:s', $asset->approved_on); ?>."><span class="glyphicon glyphicon-ok"></span></a>
									<?php else: ?>
										<span class="glyphicon glyphicon-darkened glyphicon-ok"></span>
									<?php endif; ?>
									<?php if ($asset->default): ?>
										<a href="#" class="jtooltip" data-toggle="tooltip" title="Default"><span class="glyphicon glyphicon-picture"></span></a>
									<?php else: ?>
										<span class="glyphicon glyphicon-darkened glyphicon-picture"></span>
									<?php endif; ?>
									<?php if ($asset->deleted): ?>
										<a href="#" class="jtooltip" data-toggle="tooltip" title="Deleted"><span class="glyphicon glyphicon-trash"></span></a>
									<?php else: ?>
										<span class="glyphicon glyphicon-darkened glyphicon-trash"></span>
									<?php endif; ?>
								</p>
								<p class="thumbnail-cta">
									<a href="<?php echo base_url(); ?>management/assets/edit/<?php if ($asset->asset_type == 3 || $asset->asset_type == 4) { echo $asset->photoset_id; } else { echo $asset->asset_id; } ?>" class="btn btn-primary" role="button">Edit</a>
									<?php if ($asset->approved == 0 && ($asset->asset_type == 1 || $asset->asset_type == 2)): ?>
										&nbsp;
										<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $asset->user_id; ?>/approve/<?php echo $asset->asset_id; ?>" class="btn btn-success" role="button">Approve</a>
									<?php endif; ?>
									<?php if ($asset->asset_type == 3 || $asset->asset_type == 4): ?>
										&nbsp;
										<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $asset->user_id; ?>/add/4/<?php echo $asset->asset_id; ?>" class="btn btn-success" role="button">Add</a>
									<?php endif; ?>
								</p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
