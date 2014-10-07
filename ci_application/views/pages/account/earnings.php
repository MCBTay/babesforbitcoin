
<div id="page-my-earnings">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<h2 class="normal">My Earnings</h2>
			<div class="cart-details">
				<dl>
					<dt>USD Funds</dt>
					<dd>$<?php echo $this->_user->funds_usd; ?></dd>

					<dt>&#579;TC Funds</dt>
					<dd>&#579;<?php echo $this->_user->funds_btc; ?></dd>
				</dl>
			</div>
			<div class="clearfix"></div>
			<?php if (validation_errors() != ''): ?>
				<div class="alert alert-danger">
					<strong>Warning:</strong> Please fix any errors noted below.
				</div>
			<?php endif; ?>
			<?php if (isset($error)): ?>
				<div class="alert alert-danger">
					<strong>Error:</strong> There was a problem processing your request.<br><?php echo $error; ?>
				</div>
				<p class="legal">Please contact us if this problem persists.</p>
			<?php endif; ?>
			<?php if (isset($transaction_id)): ?>
				<div class="alert alert-danger">
					<strong>Success:</strong> Your transaction ID: <?php echo $transaction_id; ?>
				</div>
			<?php endif; ?>
			<div class="panel" style="margin-top: 15px;">
				<div class="panel-title">
					<h2>Withdrawal USD</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<p>USD payouts require no action on your part. Payouts are made on the 15th of each month, or the next banking day thereafter for weekends and holidays. You will only receive payout if your USD Funds are greater than or equal to $20.00.</p>
						<?php if ($this->_user->funds_usd >= 20): ?>
							<p>You are currently eligible for our monthly USD payout.</p>
						<?php else: ?>
							<p>You are not currently eligible for our monthly USD payout.</p>
						<?php endif; ?>
						<p class="legal">Only funds that were received by the 9th of each month are eligible for payout.</p>
						<p class="legal">In order to receive cash payouts, you'll need to make an account at <a href="https://www.dwolla.com/register" target="_blank">dwolla.com</a>, please sign up with the same email you are using for this site.</p>
						<?php /* if ($this->_user->funds_usd >= 1): ?>
							<form action="<?php echo base_url(); ?>account/earnings" method="post">
								<div class="form-long">
									<p>
										<input class="inputbox" id="amount" name="amount" value="<?php echo $this->_user->funds_usd; ?>">
										<?php if (form_error('amount') != ''): ?>
											<div class="form-error">
												<?php echo form_error('amount'); ?>
											</div>
										<?php endif; ?>
									</p>
									<p class="legal">
										A $1.00 fee will be subtracted from amounts over $10.00 to cover bank fees.
									</p>
									<p>
										<input class="submit" id="withdrawal" name="withdrawal" type="submit" value="Make Withdrawal">
									</p>
								</div>
								<input name="action" type="hidden" value="withdrawal_usd">
							</form>
						<?php else: ?>
							<p>You must have at least $1.00 to make a USD withdrawal.</p>
						<?php endif; */ ?>
					</div>
				</div>
			</div>
			<div class="panel" style="margin-top: 15px;">
				<div class="panel-title">
					<h2>Withdrawal &#579;TC</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php if ($this->_user->funds_btc >= 0.000001): ?>
							<form action="<?php echo base_url(); ?>account/earnings" method="post">
								<div class="form-long">
									<p>
										<input class="inputbox" id="amount_btc" name="amount_btc" value="<?php echo $this->_user->funds_btc; ?>">
										<?php if (form_error('amount_btc') != ''): ?>
											<div class="form-error">
												<?php echo form_error('amount_btc'); ?>
											</div>
										<?php endif; ?>
									</p>
									<p class="legal">
										There are no fees for bitcoin transactions above 0.001 &#579;TC.
									</p>
									<p class="legal">
										In order to make &#579;TC withdrawals, you'll need to make an account at <a href="https://coinbase.com/signup" target="_blank">coinbase.com</a>, please sign up with the same email you are using for this site.
									</p>
									<p>
										<input class="submit" id="withdrawal_btc" name="withdrawal_btc" type="submit" value="Make Withdrawal">
									</p>
								</div>
								<input name="action" type="hidden" value="withdrawal_btc">
							</form>
						<?php else: ?>
							<p>You must have at least &#579;0.000001 to make a &#579;TC withdrawal.</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="panel" style="margin-top: 15px;">
				<div class="panel-title">
					<h2>Convert &#579;TC to USD</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php if ($this->_user->funds_btc >= 0.000001): ?>
							<form action="<?php echo base_url(); ?>account/earnings" method="post">
								<div class="form-long">
									<p>
										<span class="btc-wrapper">
											<input class="inputbox inputbox-short" id="convert" name="convert" type="text" value="<?php echo $this->_user->funds_btc; ?>">
											<span class="btc-input">&#579;</span>
											<span class="calculate-usd">| $<span class="calculate-usd-value"><?php echo $this->cart_model->btc_to_usd($this->_user->funds_btc - ($this->_user->funds_btc * FEE_CONVERT)); ?></span></span>
										</span>
										<?php if (form_error('convert') != ''): ?>
											<div class="form-error">
												<?php echo form_error('convert'); ?>
											</div>
										<?php endif; ?>
									</p>
									<p class="legal">
										There is a 2% conversion fee to exchange &#579;TC for USD.<br>
										Your exact exchange rate may vary. Latest estimate: &#579;1 = $<?php echo $rate; ?>.
									</p>
									<p>
										<input class="submit" id="convert_btc" onclick="return confirm('Are you sure you want to make this conversion?\n\nTHIS TRANSACTION IS IRREVERSIBLE!');" name="convert_btc" type="submit" value="Make Conversion">
									</p>
								</div>
								<input name="action" type="hidden" value="convert_btc">
							</form>
						<?php else: ?>
							<p>You must have at least &#579;0.000001 to make a USD conversion.</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
            <div class="panel" style="margin-top: 15px;">
                <div class="panel-title">
                    <h2>Recent Transactions</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-box panel-box-borderless">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th><b>Buyer</b></th>
                                <th><b>Asset</b></th>
                                <th><b>Commission</b></th>
                                <th><b>Purchase Price</b></th>
                                <th><b>Purchase Time</b></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($sales): ?>
                                <?php $counter = 0; ?>
                                <?php foreach ($sales as $sale): ?>
                                    <tr <?php if ($counter % 2) echo 'class=odd'; ?>>
                                        <td><small><?php echo $sale->display_name ? $sale->display_name : 'User # ' . $sale->user_id; ?></small></td>
                                        <td><small>
                                                <?php
                                                $asset = $this->assets_model->get_asset($sale->asset_id);
                                                echo $asset->asset_title ? $asset->asset_title : "Asset #" . $sale->asset_id;
                                                ?>
                                            </small></td>
                                        <td>
                                            <small><b>
                                                    <?php if ($sale->model_btc > 0): ?>
                                                        &#579;<?php echo round($sale->model_btc, 6); ?>
                                                    <?php else: ?>
                                                        $<?php echo number_format($sale->model_usd, 2); ?>
                                                    <?php endif; ?>
                                                </b></small>
                                        </td>
                                        <td>
                                            <small>
                                                <?php if ($sale->purchase_price_btc > 0): ?>
                                                    &#579;<?php echo round($sale->purchase_price_btc, 6); ?>
                                                <?php else: ?>
                                                    $<?php echo number_format($sale->purchase_price_usd, 2); ?>
                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td><small><?php echo date('F j, Y, g:i a', $sale->purchase_created); ?></small></td>
                                    </tr>
                                    <?php $counter++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; font-weight: bold;"><small>No transactions found.</small></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <a id="viewalltrans" style="display:block;" href="<?php echo base_url(); ?>account/transactions">
                            <div>View All Transactions</div>
                        </a>
                    </div>
                </div>
            </div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
