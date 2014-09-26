
<div id="page-models-profile">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<div class="panel">
				<div class="panel-title">
					<h2>Filters</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>my-files" method="post">
							<div class="form-group">
								<h3>Type</h3>
								<div class="text-right">
									<label>Photosets <input name="type[]" type="checkbox" value="3"<?php echo in_array('3', $type) ? ' checked="checked"' : ''; ?>></label>
									<label>Videos <input name="type[]" type="checkbox" value="5"<?php echo in_array('5', $type) ? ' checked="checked"' : ''; ?>></label>
								</div>
							</div>
							<div class="form-group">
								<h3>Nudity</h3>
								<div class="text-right">
									<label>None <input name="tags[]" type="checkbox" value="1"<?php echo in_array('1', $tags) ? ' checked="checked"' : ''; ?>></label>
									<label>Implied <input name="tags[]" type="checkbox" value="2"<?php echo in_array('2', $tags) ? ' checked="checked"' : ''; ?>></label>
									<label>Topless <input name="tags[]" type="checkbox" value="3"<?php echo in_array('3', $tags) ? ' checked="checked"' : ''; ?>></label>
									<label>Full <input name="tags[]" type="checkbox" value="4"<?php echo in_array('4', $tags) ? ' checked="checked"' : ''; ?>></label>
								</div>
							</div>
							<div class="form-group">
								<h3>Hair</h3>
								<div class="text-right">
									<label>Blonde <input name="tags[]" type="checkbox" value="5"<?php echo in_array('5', $tags) ? ' checked="checked"' : ''; ?>></label>
									<label>Brunette <input name="tags[]" type="checkbox" value="6"<?php echo in_array('6', $tags) ? ' checked="checked"' : ''; ?>></label>
									<label>Red <input name="tags[]" type="checkbox" value="7"<?php echo in_array('7', $tags) ? ' checked="checked"' : ''; ?>></label>
									<label>Other <input name="tags[]" type="checkbox" value="8"<?php echo in_array('8', $tags) ? ' checked="checked"' : ''; ?>></label>
								</div>
							</div>
							<div class="form-group" id="form-group-fetishes">
								<h3>Fetishes</h3>
								<div class="text-left" style="margin-top: 15px;">
									<div id="fetish-tags">
										<?php foreach ($fetishes as $fetish): ?>
											<?php if (in_array($fetish->tag_id, $tags)): ?>
												<a class="buy-button" id="fetish_<?php echo $fetish->tag_id; ?>" href="javascript:void(0);"><span class="jshown" style="display: inline;"><?php echo $fetish->fetish; ?></span><span class="jhidden" style="display: none;">Remove Tag</span></a>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<select id="fetishes" name="fetish_options">
										<option value="0">Choose Tag</option>
										<?php foreach ($fetishes as $fetish): ?>
											<?php if (!in_array($fetish->tag_id, $tags)): ?>
												<option value="<?php echo $fetish->tag_id; ?>"><?php echo $fetish->fetish; ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
									<?php foreach ($fetishes as $fetish): ?>
										<?php if (in_array($fetish->tag_id, $tags)): ?>
											<input name="tags[]" type="hidden" value="<?php echo $fetish->tag_id; ?>">
										<?php endif; ?>
									<?php endforeach; ?>
							</div>
							<div class="text-right">
								<input class="button" name="filter" type="submit" value="Filter">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="content-right content-right-large copy">
			<?php if ($models): ?>
				<?php foreach ($models as $model): ?>
					<div class="panel-photo">
						<div class="watermark-wrap">
							<a href="<?php echo base_url(); ?>my-files/model/<?php echo $model->user_id; ?>"><img alt="<?php echo isset($model->default) ? $model->default->filename : ''; ?>" src="<?php echo isset($model->default) ? CDN_URL . 'tall-' . strtolower($model->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="205"></a>
							<?php if ($model->user_hd): ?>
								<div class="watermark-hd"></div>
							<?php endif; ?>
						</div>
						<div class="panel-photo-<?php echo $model->online ? 'online' : 'offline'; ?>"><?php echo $model->display_name; ?></div>
						<div class="panel-photo-details"><?php echo $model->owned_photos; ?> Photos | <?php echo $model->owned_videos; ?> Videos</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
