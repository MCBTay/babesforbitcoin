
		<div class="page-header">
			<h2>Site Statistics</h2>
		</div>
		<div class="row" id="dashboard">
			<div class="col-xs-12 col-sm-8 col-lg-9">
				<table class="table table-responsive">
					<thead>
						<tr>
							<th><small>&nbsp;</small></th>
							<th><small>Day</small></th>
							<th><small>Week</small></th>
							<th><small>Month</small></th>
							<th><small>Lifetime</small></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><small><strong>Epoch</strong></small></td>
							<td><small>$<?php echo number_format($income['epoch']['day'], 2); ?></small></td>
							<td><small>$<?php echo number_format($income['epoch']['week'], 2); ?></small></td>
							<td><small>$<?php echo number_format($income['epoch']['month'], 2); ?></small></td>
							<td><small>$<?php echo number_format($income['epoch']['lifetime'], 2); ?></small></td>
						</tr>
						<tr>
							<td><small><strong>Dwolla</strong></small></td>
							<td><small>$<?php echo number_format($income['dwolla']['day'], 2); ?></small></td>
							<td><small>$<?php echo number_format($income['dwolla']['week'], 2); ?></small></td>
							<td><small>$<?php echo number_format($income['dwolla']['month'], 2); ?></small></td>
							<td><small>$<?php echo number_format($income['dwolla']['lifetime'], 2); ?></small></td>
						</tr>
						<tr>
							<td><small><strong>Coinbase</strong></small></td>
							<td><small>$<?php echo number_format($income['coinbase']['day'], 2); ?> | &#579;<?php echo number_format($income['coinbase']['day_btc'], 6); ?></small></td>
							<td><small>$<?php echo number_format($income['coinbase']['week'], 2); ?> | &#579;<?php echo number_format($income['coinbase']['week_btc'], 6); ?></small></td>
							<td><small>$<?php echo number_format($income['coinbase']['month'], 2); ?> | &#579;<?php echo number_format($income['coinbase']['month_btc'], 6); ?></small></td>
							<td><small>$<?php echo number_format($income['coinbase']['lifetime'], 2); ?> | &#579;<?php echo number_format($income['coinbase']['lifetime_btc'], 6); ?></small></td>
						</tr>
						<tr>
							<td><small><strong>Total</strong></small></td>
							<td><small>$<?php echo number_format($income['total']['day'], 2); ?> | &#579;<?php echo number_format($income['total']['day_btc'], 6); ?></small></td>
							<td><small>$<?php echo number_format($income['total']['week'], 2); ?> | &#579;<?php echo number_format($income['total']['week_btc'], 6); ?></small></td>
							<td><small>$<?php echo number_format($income['total']['month'], 2); ?> | &#579;<?php echo number_format($income['total']['month_btc'], 6); ?></small></td>
							<td><small>$<?php echo number_format($income['total']['lifetime'], 2); ?> | &#579;<?php echo number_format($income['total']['lifetime_btc'], 6); ?></small></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-xs-12 col-sm-4 col-lg-3">
				<?php $this->load->view('pages/management/statistics/side', $data); ?>
			</div>
		</div>
