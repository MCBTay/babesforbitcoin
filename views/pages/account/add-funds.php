
<div id="page-preferences">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<div class="panel">
				<div class="panel-title">
					<h2>Add Funds</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<form action="<?php echo base_url(); ?>account/add-funds" method="post">
							<?php if (isset($status)): ?>
								<div class="alert alert-danger">
									<?php if ($status == 'Completed' || $status == 'completed'): ?>
										<strong>Success:</strong> Your funds will be added momentarily.
									<?php else: ?>
										<strong>Warning:</strong> Your transaction was not completed.
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php if (validation_errors() != ''): ?>
								<div class="alert alert-danger">
									<strong>Warning:</strong> Please fix any errors noted below.
								</div>
							<?php endif; ?>
							<?php if (isset($success)): ?>
								<div class="alert alert-danger">
									<strong>Success:</strong> Your funds have been added.
								</div>
							<?php endif; ?>
							<?php if (isset($embed_html)): ?>
								<h3 style="font-weight: normal">Click the button below to make your payment.</h3>
								<?php echo $embed_html; ?>
							<?php else: ?>
								<h3 style="font-weight: normal">What payment method would you like to use?</h3>
								<div class="input-group">
									<label class="input-checkbox"><input class="choices" id="choices-card" name="funding_type" type="radio" value="card"> Credit Card (via Epoch)</label>
									<label class="input-checkbox"><input class="choices" id="choices-bank" name="funding_type" type="radio" value="bank"> Bank Account (via Dwolla) - 15% Discount</label>
									<label class="input-checkbox"><input class="choices" id="choices-btc" name="funding_type" type="radio" value="btc"> Bitcoin (via Coinbase) - 15% Discount</label>
								</div>
								<div class="clearfix"></div>
								<div class="funding-type choice-block" id="choice-card">
									<h3 class="input-group-header">Add Funds via Credit Card</h3>
									<div class="input-group">
										<label class="input-checkbox"><input id="amount_card1" name="amount_card" type="radio" value="1"> $1.19 = 1 credit</label>
										<label class="input-checkbox"><input id="amount_card5" name="amount_card" type="radio" value="5"> $5.99 = 5 credits</label>
										<label class="input-checkbox"><input id="amount_card10" name="amount_card" type="radio" value="10"> $11.99 = 10 credits</label>
										<label class="input-checkbox"><input id="amount_card15" name="amount_card" type="radio" value="15"> $17.99 = 15 credits</label>
										<label class="input-checkbox"><input id="amount_card20" name="amount_card" type="radio" value="20"> $23.99 = 20 credits</label>
										<label class="input-checkbox"><input id="amount_card50" name="amount_card" type="radio" value="50"> $59.99 = 50 credits</label>
										<?php if (form_error('amount_card') != ''): ?>
											<div class="form-error">
												<?php echo form_error('amount_card'); ?>
											</div>
										<?php endif; ?>
									</div>
									<div class="clearfix"></div>
									<div class="input-group input-group-submit">
										<input id="add" name="add" type="submit" value="Add Funds">
									</div>
									<h3 class="input-group-header">Credit Card Billing Support</h3>
									<p><a href="http://www.epoch.com" target="_blank">Click here for billing support.</a></p>
									<p><a href="http://www.epoch.com" target="_blank">Please visit Epoch.com, our authorized sales agent</a></p>
								</div>
								<div class="funding-type choice-block" id="choice-bank">
									<h3 class="input-group-header">Add Funds via Bank Account</h3>
									<div class="input-group">
										<label for="amount_bank">Amount (USD):</label>
										<input id="amount_bank" maxlength="7" name="amount_bank" type="text">
										<?php if (form_error('amount_bank') != ''): ?>
											<div class="form-error">
												<?php echo form_error('amount_bank'); ?>
											</div>
										<?php endif; ?>
									</div>
									<div class="input-group">
										<label for="amount_bank_fee">Fee Amount (USD):</label>
										<input id="amount_bank_fee" maxlength="7" name="amount_bank_fee" readonly type="text">
									</div>
									<div class="input-group">
										<label for="amount_bank_total">Total (USD):</label>
										<input id="amount_bank_total" maxlength="7" name="amount_bank_total" readonly type="text">
									</div>
									<div class="input-group">
										<p class="legal text-right" style="margin: 10px 0;">
											You will be redirected to Dwolla to complete this transaction.
										</p>
									</div>
									<div class="input-group input-group-submit">
										<input id="add" name="add" type="submit" value="Add Funds">
									</div>
									<h3 class="input-group-header">Bank Account Billing Support</h3>
									<p><a href="mailto:billing@babesforbitcoin.com">Click here for billing support.</a></p>
								</div>
								<div class="funding-type choice-block" id="choice-btc">
									<h3 class="input-group-header">Add Funds via Bitcoin</h3>
									<div class="input-group">
										<label for="amount_btc_usd">Amount (USD):</label>
										<input id="amount_btc_usd" maxlength="7" name="amount_btc_usd" type="text">
										<?php if (form_error('amount_btc_usd') != ''): ?>
											<div class="form-error">
												<?php echo form_error('amount_btc_usd'); ?>
											</div>
										<?php endif; ?>
									</div>
									<div class="input-group">
										<label for="amount_btc_total">Total (&#579;TC):</label>
										<input id="amount_btc_total" maxlength="8" name="amount_btc_total" readonly type="text">
									</div>
									<div class="input-group">
										<label class="input-checkbox"><input checked id="convert_to_usd_false" name="convert_to_usd" type="radio" value="0"> Leave funds as &#579;TC</label>
										<label class="input-checkbox"><input id="convert_to_usd_true" name="convert_to_usd" type="radio" value="1"> Convert &#579;TC to USD (Currently &#579;1 = $<?php echo $rate; ?>)</label>
									</div>
									<div class="input-group">
										<p class="legal text-right" style="margin: 10px 0;">
											You will be redirected to Coinbase to complete this transaction.
										</p>
									</div>
									<div class="input-group input-group-submit">
										<input id="add" name="add" type="submit" value="Add Funds">
									</div>
									<h3 class="input-group-header">Bitcoin Billing Support</h3>
									<p><a href="mailto:billing@babesforbitcoin.com">Click here for billing support.</a></p>
								</div>
							<?php endif; ?>
							<div class="clearfix"></div>
						</form>
					</div>
				</div>
			</div>
			<div class="panel" style="margin-top: 15px;">
				<div class="panel-title">
					<h2>Convert &#579;TC to USD:</h2>
				</div>
				<div class="panel-body">
					<div class="panel-box panel-box-borderless">
						<?php if (isset($convert)): ?>
							<div class="alert alert-danger">
								<strong>Success:</strong> Your funds have been converted.
							</div>
						<?php endif; ?>
						<?php if ($this->_user->funds_btc >= 0.000001): ?>
							<form action="<?php echo base_url(); ?>account/add-funds" method="post">
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
		</div>
		<div class="clearfix"></div>
	</div>
</div>
