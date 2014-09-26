
		<div class="page-header">
			<h2>Model Payout</h2>
		</div>
		<?php if (isset($success)): ?>
			<div class="alert alert-success">
				Model Payout was processed successfully.
			</div>
		<?php endif; ?>
		<?php if (validation_errors() != ''): ?>
			<div class="alert alert-danger">
				<span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp; <strong>Warning:</strong> <?php echo validation_errors(); ?>
			</div>
		<?php endif; ?>
		<form action="<?php echo base_url(); ?>management/manage/payout" class="form-horizontal" id="model-payout" method="post" role="form">
			<table class="table table-responsive">
				<thead>
					<tr>
						<th><input id="check-all" name="check-all" type="checkbox" value="1"></th>
						<th>ID</th>
						<th>Display Name</th>
						<th>Email</th>
						<th>Payable USD</th>
						<th>Funds USD</th>
						<th>Last Login</th>
						<th>Member Since</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($model_payout as $model): ?>
						<tr>
							<td>
								<input class="payout_models" id="payout_models<?php echo $model->user_id; ?>" name="payout_models[]" type="checkbox" value="<?php echo $model->user_id; ?>">
								<input class="payout_funds" id="payout_funds<?php echo $model->user_id; ?>" name="payout_funds[]" type="hidden" value="<?php echo $model->funds_usd_payable; ?>">
							</td>
							<td><?php echo $model->user_id; ?></td>
							<td><?php echo $model->display_name; ?></td>
							<td><?php echo $model->email; ?></td>
							<td>$<?php echo number_format($model->funds_usd_payable, 2); ?></td>
							<td>$<?php echo $model->funds_usd; ?></td>
							<td><?php echo date('Y-m-d H:i:s', $model->last_login); ?></td>
							<td><?php echo date('Y-m-d H:i:s', $model->created); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div class="form-group">
				<label class="col-sm-2 control-label">Funds Selected:</label>
				<div class="col-sm-10">
					<p class="form-control-static"><strong>$<span id="funds-selected">0.00</span></strong></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Funds Available:</label>
				<div class="col-sm-10">
					<p class="form-control-static"><strong>$<span id="funds-available"><?php echo $funds_available; ?></span></strong></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Funds Remaining:</label>
				<div class="col-sm-10">
					<p class="form-control-static"><strong>$<span class="text-success" id="funds-remaining"><?php echo $funds_available; ?></span></strong></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-large btn-danger" id="payout_submit">Submit Payout</button>
				</div>
			</div>
		</form>
