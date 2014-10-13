
		<div class="page-header">
			<h2>Edit Asset # <?php echo $asset->asset_id; ?></h2>
		</div>
		<?php if (validation_errors() != ''): ?>
			<div class="alert alert-danger">
				<span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp; <strong>Warning:</strong> Error saving user. Please see the errors below.
			</div>
		<?php endif; ?>
		<?php if ($success): ?>
			<div class="alert alert-success">
				Asset # <?php echo $asset->asset_id; ?> was successfully saved.
			</div>
		<?php endif; ?>
		<p class="marginbottomlarge">
			<a href="<?php echo base_url(); ?>management/assets" class="btn btn-primary back">Back to list of assets</a>
			&nbsp;
			<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $asset->user_id; ?>" class="btn btn-default">View user gallery</a>
			<?php if ($asset->asset_type == 4): ?>
				&nbsp;
				<a href="<?php echo base_url(); ?>management/assets/edit/<?php echo $asset->photoset_id; ?>" class="btn btn-default">View full photoset</a>
			<?php else: ?>
				&nbsp;
				<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $asset->user_id; ?>/view/<?php echo $asset->asset_type; ?>" class="btn btn-default">Previous page</a>
			<?php endif; ?>
		</p>
		<?php $this->load->view('pages/management/assets/edit-form', array('asset' => $asset)); ?>
		<?php if ($asset->is_cover_photo && ($asset->asset_type == 4 || $asset->asset_type == 3)): ?>
			<div class="page-header">
				<h3>Photoset # <?php echo $asset->photoset_id; ?> Photos</h3>
			</div>
			<p class="marginbottomlarge">
				<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $asset->user_id; ?>/add/4/<?php echo $asset->asset_id; ?>" class="btn btn-success" role="button">Add Photo</a>
			</p>
			<div class="container-fluid">
				<div class="row">
					<?php foreach ($asset->subphotos as $n => $subphoto): ?>
						<?php if ($n % 4 == 0 && $n != 0): ?>
							</div>
							<div class="row">
						<?php endif; ?>
						<?php if ($n % 4 > 0 && $n % 2 == 0): ?>
							<div class="clearfix visible-xs"></div>
						<?php endif; ?>
						<div class="col-xs-6 col-sm-3">
							<div class="thumbnail">
								<a href="<?php echo base_url(); ?>management/assets/edit/<?php echo $subphoto->asset_id; ?>">
									<img alt="<?php echo $subphoto->asset_title; ?>" src="<?php echo $subphoto->filename ? CDN_URL . 'sml-' . strtolower($subphoto->filename) : base_url() . 'assets/img/no-photo.png'; ?>">
								</a>
								<div class="caption">
									<h3 class="thumbnail-title"><?php echo $subphoto->asset_title; ?></h3>
									<p class="thumbnail-details">
										Created <?php echo date('M j Y', $subphoto->asset_created); ?><br>
										<?php if ($subphoto->approved): ?>
											<a href="#" class="jpopover" data-container="body" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" data-title="Approved" data-content="<?php echo $subphoto->approved_by_name; ?> approved this asset&lt;br&gt;on <?php echo date('Y-m-d', $subphoto->approved_on); ?> at <?php echo date('H:i:s', $subphoto->approved_on); ?>."><span class="glyphicon glyphicon-ok"></span></a>
										<?php else: ?>
											<span class="glyphicon glyphicon-darkened glyphicon-ok"></span>
										<?php endif; ?>
										<?php if ($subphoto->default): ?>
											<a href="#" class="jtooltip" data-toggle="tooltip" title="Default"><span class="glyphicon glyphicon-picture"></span></a>
										<?php else: ?>
											<span class="glyphicon glyphicon-darkened glyphicon-picture"></span>
										<?php endif; ?>
										<?php if ($subphoto->deleted): ?>
											<a href="#" class="jtooltip" data-toggle="tooltip" title="Deleted"><span class="glyphicon glyphicon-trash"></span></a>
										<?php else: ?>
											<span class="glyphicon glyphicon-darkened glyphicon-trash"></span>
										<?php endif; ?>
									</p>
									<p class="thumbnail-cta"><a href="<?php echo base_url(); ?>management/assets/edit/<?php echo $subphoto->asset_id; ?>" class="btn btn-primary" role="button">Edit</a></p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
