
		<div class="page-header">
			<h2><?php echo $user->display_name ? $user->display_name : 'User # ' . $user->user_id; ?>'s Transactions</h2>
		</div>
		<div class="row" id="dashboard">
			<ul class="nav nav-tabs" role="tablist">
				<?php if ($user->user_type == 1): ?>
					<li class="active"><a href="#orders" role="tab" data-toggle="tab">Funds Added</a></li>
					<li><a href="#users_purchases" role="tab" data-toggle="tab">Purchases</a></li>
				<?php elseif ($user->user_type == 2): ?>
					<li class="active"><a href="#users_withdrawals" role="tab" data-toggle="tab">Withdrawals</a></li>
				<?php endif; ?>
				<li><a href="#conversions" role="tab" data-toggle="tab">Conversions</a></li>
			</ul>
			<div class="tab-content" style="padding-top: 15px;">
				<?php if ($user->user_type == 1): ?>
					<div class="tab-pane active" id="orders">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th><small>ID</small></th>
									<th><small>User</small></th>
									<th><small>Method</small></th>
									<th><small>Amount</small></th>
									<th><small>Site Fee</small></th>
									<th><small>Total</small></th>
									<th><small>Confirmation</small></th>
									<th><small>Created</small></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($orders as $order): ?>
									<tr>
										<td><small><?php echo $order->order_id; ?></small></td>
										<td><small><a href="<?php echo base_url(); ?>management/users/view/<?php echo $order->user_id; ?>"><?php echo $order->display_name ? $order->display_name : 'User # ' . $order->user_id; ?></a></small></td>
										<td><small><?php echo $order->method; ?></small></td>
										<td>
											<small>
												<?php if ($order->currency == 'usd'): ?>
													$<?php echo number_format($order->amount, 2); ?>
												<?php else: ?>
													&#579;<?php echo round($order->amount, 6); ?>
												<?php endif; ?>
											</small>
										</td>
										<td>
											<small>
												<?php if ($order->currency == 'usd'): ?>
													$<?php echo number_format($order->fee, 2); ?>
												<?php else: ?>
													&#579;<?php echo round($order->fee, 6); ?>
												<?php endif; ?>
											</small>
										</td>
										<td>
											<small>
												<?php if ($order->currency == 'usd'): ?>
													$<?php echo number_format($order->total, 2); ?>
												<?php else: ?>
													&#579;<?php echo round($order->total, 6); ?>
												<?php endif; ?>
											</small>
										</td>
										<td>
											<small>
												<?php if ($order->method == 'bank'): ?>
													<?php echo $order->transaction_id; ?>
												<?php elseif ($order->method == 'btc' || $order->method == 'btc_usd'): ?>
													<?php if (!empty($order->cb_code)): ?>
														<a href="#" class="jtooltip" data-toggle="tooltip" title="<?php echo $order->cb_code; ?>"><?php echo substr($order->cb_code, 0, 10); ?>...</a>
													<?php endif; ?>
												<?php endif; ?>
											</small>
										</td>
										<td><small><?php echo date('Y-m-d H:i:s', $order->created); ?></small></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<div class="tab-pane" id="users_purchases">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th><small>ID</small></th>
									<th><small>User</small></th>
									<th><small>Asset</small></th>
									<th><small>Price</small></th>
									<th><small>Site Fee</small></th>
									<th><small>Coinbase Code</small></th>
									<th><small>Payout Date</small></th>
									<th><small>Created</small></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($purchases as $purchase): ?>
									<tr>
										<td><small><?php echo $purchase->purchase_id; ?></small></td>
										<td><small><a href="<?php echo base_url(); ?>management/users/view/<?php echo $purchase->user_id; ?>"><?php echo $purchase->display_name ? $purchase->display_name : 'User # ' . $purchase->user_id; ?></a></small></td>
										<td><small><a href="<?php echo base_url(); ?>management/assets/edit/<?php echo $purchase->asset_id; ?>">Asset # <?php echo $purchase->asset_id; ?></a></small></td>
										<td>
											<small>
												<?php if ($purchase->purchase_price_btc > 0): ?>
													&#579;<?php echo round($purchase->purchase_price_btc, 6); ?>
												<?php else: ?>
													$<?php echo number_format($purchase->purchase_price_usd, 2); ?>
												<?php endif; ?>
											</small>
										</td>
										<td><small>$<?php echo number_format($purchase->site_usd, 2); ?></small></td>
										<td>
											<small>
													<?php if (!empty($purchase->cb_code)): ?>
														<a href="#" class="jtooltip" data-toggle="tooltip" title="<?php echo $purchase->cb_code; ?>"><?php echo substr($purchase->cb_code, 0, 10); ?>...</a>
													<?php endif; ?>
											</small>
										</td>
										<td>
											<small>
												<?php if ($purchase->payout_date): ?>
													<?php echo date('Y-m-d H:i:s', $purchase->payout_date); ?>
												<?php endif; ?>
											</small>
										</td>
										<td><small><?php echo date('Y-m-d H:i:s', $purchase->purchase_created); ?></small></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php elseif ($user->user_type == 2): ?>
					<div class="tab-pane active" id="users_withdrawals">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th><small>ID</small></th>
									<th><small>User</small></th>
									<th><small>Amount</small></th>
									<th><small>Funds Remaining</small></th>
									<th><small>Site Fee</small></th>
									<th><small>Transaction ID</small></th>
									<th><small>Refunded</small></th>
									<th><small>Created</small></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($withdrawals as $withdrawal): ?>
									<tr>
										<td><small><?php echo $withdrawal->withdrawal_id; ?></small></td>
										<td><small><a href="<?php echo base_url(); ?>management/users/view/<?php echo $withdrawal->user_id; ?>"><?php echo $withdrawal->display_name ? $withdrawal->display_name : 'User # ' . $withdrawal->user_id; ?></a></small></td>
										<td>
											<small>
												<?php if ($withdrawal->currency == 'btc'): ?>
													&#579;<?php echo round($withdrawal->withdrawal_amount, 6); ?>
												<?php else: ?>
													$<?php echo number_format($withdrawal->withdrawal_amount, 2); ?>
												<?php endif; ?>
											</small>
										</td>
										<td>
											<small>
												<?php if ($withdrawal->currency == 'btc'): ?>
													&#579;<?php echo round($withdrawal->funds_btc_remaining, 6); ?>
												<?php else: ?>
													$<?php echo number_format($withdrawal->funds_usd_remaining, 2); ?>
												<?php endif; ?>
											</small>
										</td>
										<td><small>$<?php echo number_format($withdrawal->site_fee, 2); ?></small></td>
										<td>
											<small>
													<?php if (!empty($withdrawal->transaction_id)): ?>
														<a href="#" class="jtooltip" data-toggle="tooltip" title="<?php echo $withdrawal->transaction_id; ?>"><?php echo substr($withdrawal->transaction_id, 0, 10); ?>...</a>
													<?php endif; ?>
											</small>
										</td>
										<td><small><?php echo $withdrawal->refunded ? 'Yes' : 'No'; ?></small></td>
										<td><small><?php echo date('Y-m-d H:i:s', $withdrawal->withdrawal_created); ?></small></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
				<div class="tab-pane" id="conversions">
					<table class="table table-responsive">
						<thead>
							<tr>
								<th><small>ID</small></th>
								<th><small>User</small></th>
								<th><small>Coinbase Code</small></th>
								<th><small>BTC Out</small></th>
								<th><small>USD In</small></th>
								<th><small>Site Fee</small></th>
								<th><small>Payout Date</small></th>
								<th><small>Created</small></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($conversions as $conversion): ?>
								<tr>
									<td><small><?php echo $conversion->conversion_id; ?></small></td>
									<td><small><a href="<?php echo base_url(); ?>management/users/view/<?php echo $conversion->user_id; ?>"><?php echo $conversion->display_name ? $conversion->display_name : 'User # ' . $conversion->user_id; ?></a></small></td>
									<td>
										<small>
												<?php if (!empty($conversion->cb_code)): ?>
													<a href="#" class="jtooltip" data-toggle="tooltip" title="<?php echo $conversion->cb_code; ?>"><?php echo substr($conversion->cb_code, 0, 10); ?>...</a>
												<?php endif; ?>
										</small>
									</td>
									<td><small>&#579;<?php echo $conversion->btc_out; ?></small></td>
									<td><small>$<?php echo $conversion->usd_in; ?></small></td>
									<td><small>$<?php echo $conversion->site_fee; ?></small></td>
									<td>
										<small>
											<?php if ($conversion->payout_date): ?>
												<?php echo date('Y-m-d H:i:s', $conversion->payout_date); ?>
											<?php endif; ?>
										</small>
									</td>
									<td><small><?php echo date('Y-m-d H:i:s', $conversion->created); ?></small></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
