
		<div class="page-header">
			<h2>Sort Front Page Models</h2>
		</div>
		<?php if (isset($success)): ?>
			<div class="alert alert-success">
				Sort order was successfully saved.
			</div>
		<?php endif; ?>
		<form action="<?php echo base_url(); ?>management/manage/frontpage" class="form-horizontal" id="featured-sort" method="post" role="form">
			<div id="featured-order">
				<?php foreach ($models as $sort => $model): ?>
					<div class="well well-sm">
						<h4>
							<span class="glyphicon glyphicon-sort"></span>&nbsp;
							<img alt="<?php echo $model->display_name; ?>" src="<?php echo $model->admin_thumb ? CDN_URL . $model->admin_thumb : base_url() . 'assets/img/no-photo.png'; ?>" width="72" height="72">&nbsp;
							<?php echo $model->display_name; ?> (User ID # <?php echo $model->user_id; ?>)
						</h4>
						<input name="sort[]" type="hidden" value="<?php echo $model->user_id; ?>">
					</div>
				<?php endforeach; ?>
			</div>
			<?php /*
			<button type="submit" class="btn btn-large btn-success">Save</button>
			*/ ?>
		</form>
