
		<div class="page-header">
			<h2>Assets</h2>
		</div>
		<div class="alert alert-info">
			Total entries: <strong><?php echo $total; ?></strong>
		</div>
		<form action="<?php echo base_url(); ?>management/assets" class="form-inline" id="filter-form" role="form">
			<div class="form-group">
				<label>Filter:</label>
				&nbsp;
				Asset Type:
				<select class="form-control input-sm" id="filter_asset_type" name="filter_asset_type">
					<option value="0"> - Select - </option>
					<?php foreach ($types as $type): ?>
						<option value="<?php echo $type->asset_type_id; ?>"<?php echo $filter_type == $type->asset_type_id ? ' selected="selected"' : ''; ?>><?php echo $type->asset_type_title; ?></option>
					<?php endforeach; ?>
				</select>
				&nbsp;
				Approved:
				<select class="form-control input-sm" id="filter_approved" name="filter_approved">
					<option value="all"> - Select - </option>
					<option value="0"<?php echo $filter_approved === '0' ? ' selected="selected"' : ''; ?>>No</option>
					<option value="1"<?php echo $filter_approved === '1' ? ' selected="selected"' : ''; ?>>Yes</option>
				</select>
				&nbsp;
				Default:
				<select class="form-control input-sm" id="filter_default" name="filter_default">
					<option value="all"> - Select - </option>
					<option value="0"<?php echo $filter_default === '0' ? ' selected="selected"' : ''; ?>>No</option>
					<option value="1"<?php echo $filter_default === '1' ? ' selected="selected"' : ''; ?>>Yes</option>
				</select>
				&nbsp;
				Deleted:
				<select class="form-control input-sm" id="filter_deleted" name="filter_deleted">
					<option value="all"> - Select - </option>
					<option value="0"<?php echo $filter_deleted === '0' ? ' selected="selected"' : ''; ?>>No</option>
					<option value="1"<?php echo $filter_deleted === '1' ? ' selected="selected"' : ''; ?>>Yes</option>
				</select>
			</div>
			<input id="hidden-asset-type" name="hidden-asset-type" type="hidden" value="<?php echo $filter_type; ?>">
			<input id="hidden-asset-default" name="hidden-asset-default" type="hidden" value="<?php echo $filter_default; ?>">
			<input id="hidden-asset-deleted" name="hidden-asset-deleted" type="hidden" value="<?php echo $filter_deleted; ?>">
			<input id="hidden-asset-approved" name="hidden-asset-approved" type="hidden" value="<?php echo $filter_approved; ?>">
			<input id="hidden-asset-sort" name="hidden-asset-sort" type="hidden" value="<?php echo $sort; ?>">
			<input id="hidden-asset-dir" name="hidden-asset-dir" type="hidden" value="<?php echo $dir; ?>">
		</form>
		<table class="table table-responsive">
			<thead>
				<tr>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/assets/<?php echo $filter; ?>/asset_id/<?php echo $sort == 'asset_id' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						ID
						<?php if ($sort == 'asset_id'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/assets/<?php echo $filter; ?>/display_name/<?php echo $sort == 'display_name' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Display Name
						<?php if ($sort == 'display_name'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/assets/<?php echo $filter; ?>/asset_type_title/<?php echo $sort == 'asset_type_title' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Type
						<?php if ($sort == 'asset_type_title'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/assets/<?php echo $filter; ?>/asset_title/<?php echo $sort == 'asset_title' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Title
						<?php if ($sort == 'asset_title'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/assets/<?php echo $filter; ?>/purchased/<?php echo $sort == 'purchased' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Purchased
						<?php if ($sort == 'purchased'): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th class="sortable" onclick="location.href = '<?php echo base_url(); ?>management/assets/<?php echo $filter; ?>/approved/<?php echo $sort == 'approved' && $dir == 'asc' ? 'desc' : 'asc'; ?>';">
						Status
						<?php if ($sort == 'approved' || $sort == ''): ?>
							<span class="glyphicon glyphicon-sort-by-attributes<?php echo $dir == 'asc' ? '' : '-alt'; ?>"></span>
						<?php endif; ?>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($assets as $asset): ?>
					<tr<?php echo $asset->approved ? '' : ' class="danger"'; ?>>
						<td><?php echo $asset->asset_id; ?></td>
						<td>
							<img alt="<?php echo $asset->display_name; ?>" src="<?php echo $asset->admin_thumb ? CDN_URL . $asset->admin_thumb : base_url() . 'assets/img/no-photo.png'; ?>" width="72" height="72">
							<?php echo $asset->display_name; ?>
						</td>
						<td><?php echo $asset->asset_type_title; ?></td>
						<td>
							<img alt="<?php echo $asset->filename; ?>" src="<?php echo $asset->filename ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="72" height="72">
							<?php echo $asset->asset_title; ?>
						</td>
						<td><?php echo $asset->purchased; ?></td>
						<td>
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
						</td>
						<td>
							&bull; <a href="<?php echo base_url(); ?>management/assets/edit/<?php echo $asset->asset_id; ?>" class="edit">Edit</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php /*
		<div class="container-fluid">
			<div class="row">
				<?php foreach ($users as $n => $user): ?>
					<?php if ($n % 4 == 0 && $n != 0): ?>
						</div>
						<div class="row">
					<?php endif; ?>
					<?php if ($n % 4 > 0 && $n % 2 == 0): ?>
						<div class="clearfix visible-xs"></div>
					<?php endif; ?>
					<div class="col-xs-6 col-sm-3">
						<div class="thumbnail">
							<a href="<?php echo base_url(); ?>management/assets/edit/<?php echo $user->user_id; ?>"><img alt="<?php echo $user->display_name; ?>" src="http://placehold.it/255x255"></a>
							<div class="caption">
								<h3 class="thumbnail-title"><?php echo $user->display_name; ?></h3>
								<p class="thumbnail-details">Updated <?php echo date('M j', $user->last_login); ?></p>
								<p class="thumbnail-cta"><a href="<?php echo base_url(); ?>management/assets/edit/<?php echo $user->user_id; ?>" class="btn btn-primary" role="button">View</a></p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		*/ ?>
		<div class="text-center">
			<?php echo $pagination; ?>
		</div>
