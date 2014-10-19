
		<form action="<?php echo base_url(); ?>management/assets/edit/<?php if ($asset->asset_id) { echo $asset->asset_id; } else { echo 'photoset/' . $asset->photoset_id; } ?>" class="form-horizontal" method="post" role="form">
			<div class="panel panel-<?php echo $asset->approved ? 'success' : 'danger'; ?>">
				<div class="panel-heading">
					<strong>Asset</strong> # <?php if ($asset->asset_id) { echo $asset->asset_id; } else { echo $asset->photoset_id; } ?>
					 &nbsp;
					<strong>User</strong> # <?php echo $asset->user_id; ?> (<a href="<?php echo base_url(); ?>management/users/gallery/<?php echo $asset->user_id; ?>"><?php echo $asset->display_name; ?></a>)
					 &nbsp; <strong>Type</strong> <?php echo $asset->asset_type_title; ?>
				</div>
				<div class="panel-body">
					<div class="form-group<?php echo form_error('asset_title') != '' ? ' has-error' : ''; ?> has-feedback">
						<div class="col-sm-12">
							<input class="form-control" name="asset_title" placeholder="Enter a title" type="text" value="<?php echo $asset->asset_title; ?>">
							<?php if (form_error('asset_title') != ''): ?>
								<span class="glyphicon glyphicon-remove form-control-feedback"></span>
								<div class="form-error">
									<?php echo form_error('asset_title'); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php if (!$asset->asset_type || $asset->asset_type == 5): ?>
						<div class="form-group<?php echo form_error('asset_cost') != '' ? ' has-error' : ''; ?> has-feedback" style="padding-top: 15px;">
							<div class="col-sm-12">
								<label for="asset_cost">Cost</label>
								<input class="form-control" name="asset_cost" placeholder="Enter a cost" type="text" value="<?php echo $asset->asset_cost; ?>">
								<?php if (form_error('asset_cost') != ''): ?>
									<span class="glyphicon glyphicon-remove form-control-feedback"></span>
									<div class="form-error">
										<?php echo form_error('asset_cost'); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<?php if ($asset->filename && !$asset->video): ?>
							<div class="col-sm-12">
								<img alt="<?php echo basename($asset->filename); ?>" src="<?php echo CDN_URL . $asset->filename; ?>" style="margin: 15px auto 5px; max-width: 100%;">
							</div>
                        <?php endif; ?>
                        <?php if ($asset->cover_photo && $asset->cover_photo->filename && !$asset->video): ?>
                            <div class="col-sm-12">
                                <img alt="<?php echo basename($asset->cover_photo->filename); ?>" src="<?php echo CDN_URL . $asset->cover_photo->filename; ?>" style="margin: 15px auto 5px; max-width: 100%;">
                            </div>
						<?php endif; ?>
						<?php if ($asset->video): ?>
							<div class="col-sm-12" style="padding-top: 15px;">
								<video controls="controls" <?php echo $asset->filename ? 'poster="' . CDN_URL . $asset->filename . '"' : ''; ?> preload="none" style="display: block; width: 100%; max-width: 100%; height: auto;">
									<source src="<?php echo CDN_URL . $asset->video; ?>" type="<?php echo $asset->mimetype; ?>">
									<!-- Flash fallback for non-HTML5 browsers without JavaScript -->
									<object data="<?php echo base_url(); ?>assets/js/mediaelement/flashmediaelement.swf" style="display: block; width: 100%; max-width: 100%; height: auto;" type="application/x-shockwave-flash">
										<param name="movie" value="<?php echo base_url(); ?>assets/js/mediaelement/flashmediaelement.swf">
										<param name="flashvars" value="controls=true&amp;file=<?php echo CDN_URL . urlencode($asset->video); ?>">
										<!-- Image as a last resort -->
										<?php if ($asset->filename): ?>
											<img class="img-responsive" src="<?php echo CDN_URL . $asset->filename; ?>" title="No video playback capabilities">
										<?php endif; ?>
									</object>
								</video>
								<?php /*
								<video style="display: block; width: 100%; max-width: 100%; height: auto;" controls>
									<source src="<?php echo CDN_URL . $asset->video; ?>" type="video/mp4">
								</video>
								*/ ?>
							</div>
						<?php endif; ?>
						<div class="col-sm-12" style="padding-top: 15px;">
							<?php if ($asset->asset_type == 1): ?>
								<input type="hidden" name="default" value="<?php echo $asset->default; ?>">
								<button type="submit" class="btn btn-sm btn-<?php echo $asset->default ? 'danger' : 'success'; ?>" name="default" value="<?php echo $asset->default ? 0 : 1; ?>"><?php echo $asset->default ? 'Unset as Default' : 'Set as Default'; ?></button>
							<?php endif; ?>
							<input type="hidden" name="deleted" value="<?php echo $asset->deleted; ?>">
							<button type="submit" class="btn btn-sm btn-<?php echo $asset->deleted ? 'success' : 'danger'; ?>" name="deleted" value="<?php echo $asset->deleted ? 0 : 1; ?>"><?php echo $asset->deleted ? 'Undelete Asset' : 'Delete Asset'; ?></button>
							<input type="hidden" name="approved" value="<?php echo $asset->approved; ?>">
							<button type="submit" class="btn btn-sm btn-<?php echo $asset->approved ? 'danger' : 'success'; ?>" name="approved" value="<?php echo $asset->approved ? 0 : 1; ?>"><?php echo $asset->approved ? 'Unapprove Asset' : 'Approve Asset'; ?></button>
							<input type="hidden" name="asset_hd" value="<?php echo $asset->asset_hd; ?>">
							<button type="submit" class="btn btn-sm btn-<?php echo $asset->asset_hd ? 'danger' : 'success'; ?>" name="asset_hd" value="<?php echo $asset->asset_hd ? 0 : 1; ?>"><?php echo $asset->asset_hd ? 'Unset as HD' : 'Set as HD'; ?></button>
							<?php if ($asset->asset_type == 5): ?>
								<a href="<?php echo CDN_URL . $asset->video; ?>"><button class="btn btn-sm btn-default" type="button">Download</button></a>
							<?php endif; ?>
						</div>
					</div>
					<div class="form-group margintopsmall">
						<div class="col-sm-12">
							<label for="tags">Tags</label>
							<input class="form-control" name="tags" id="tags" placeholder="Enter tags" type="text" value="<?php echo $asset->tags; ?>">
							<span class="help-block" style="margin-bottom: 0;">Enter a comma-delimited list of tags for this asset.</span>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="pull-left">
						<p class="text-muted">
							<em>Created on <?php echo date('Y-m-d H:i:s', $asset->asset_created); ?></em>
							<?php if ($asset->approved): ?>
								<br><em>Approved by <?php echo $asset->approved_by_name; ?> on <?php echo date('Y-m-d H:i:s', $asset->approved_on); ?></em>
							<?php endif; ?>
						</p>
					</div>
					<div class="pull-right">
						<button type="button" class="btn btn-large btn-danger" data-toggle="modal" data-target="#jModal<?php if ($asset->asset_id) { echo $asset->asset_id; } else { echo $asset->photoset_id; } ?>">Delete</button>
						&nbsp;
						<button type="submit" class="btn btn-large btn-success">Save</button>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="modal fade" id="jModal<?php if ($asset->asset_id) { echo $asset->asset_id; } else { echo $asset->photoset_id; }?>">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Are you sure?</h4>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to PERMANENTLY delete this asset?</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<a href="<?php echo base_url(); ?>management/assets/delete/<?php if ($asset->asset_id) { echo $asset->asset_id; } else { echo $asset->photoset_id; } ?>" class="btn btn-danger" role="button">Delete</a>
						</div>
					</div>
				</div>
			</div>
		</form>
