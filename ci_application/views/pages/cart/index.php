
<div id="page-cart">
	<div class="content-wrapper">
		<div class="content-left">
			<?php echo $this->load->view('templates/navigation-sidebar'); ?>
		</div>
		<div class="content-right copy">
			<h2>Your Cart</h2>
			<?php if (isset($exists)): ?>
				<div class="alert alert-danger">
					<strong>Warning:</strong> You've already purchased that item.
				</div>
			<?php endif; ?>
			<?php foreach ($assets as $asset): ?>
				<div class="panel-photo">
					<div class="watermark-wrap">
						<img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="170">
						<?php if ($asset->asset_type == 5): ?>
							<div class="watermark-hd<?php echo $asset->asset_hd ? ' watermark-hd-video' : ' watermark-video'; ?>"></div>
						<?php elseif ($asset->asset_hd): ?>
							<div class="watermark-hd"></div>
						<?php endif; ?>
					</div>
					<div class="panel-photo-offline text-center"><?php echo $asset->asset_title; ?></div>
					<div class="panel-photo-details text-center">$<?php echo $asset->asset_cost; ?> | &#579;<?php echo $asset->asset_cost_btc; ?></div>
					<a class="button" href="<?php echo base_url(); ?>cart/remove/<?php if ($asset->asset_type = 3 || $asset->asset_type = 4) { echo $asset->photoset_id; } else { echo $asset->asset_id; } ?>">Remove from cart</a>
				</div>
			<?php endforeach; ?>
			<div class="clearfix"></div>
			<h2>Payment</h2>
			<div class="cart-details">
				<dl>
					<dt>Cart Total:</dt>
					<dd>$<?php echo $total; ?></dd>

					<dt>USD Available:</dt>
					<dd>$<?php echo $this->_user->funds_usd; ?></dd>

					<dt>&#579;TC Available:</dt>
					<dd>&#579;<?php echo $this->_user->funds_btc; ?></dd>

					<dt>Total Available:</dt>
					<dd>$<?php echo $this->_user->funds_total; ?>*</dd>
				</dl>
			</div>
			<div class="clearfix"></div>
			<p class="legal">* Total is based on current exchange rates and is subject to change frequently.</p>
			<?php if ($this->_user->funds_total >= $total): ?>
				<form action="<?php echo base_url(); ?>cart" method="post">
					<div class="form-long">
						<?php if ($this->_user->funds_usd < $total): ?>
							<p><strong>Please note:</strong> You do not have enough USD to cover this transaction. By completing this purchase now, you are authorizing us to convert some, or all, of your &#579;TC into USD - an irreversable process.</p>
						<?php endif; ?>
						<input class="submit" id="purchase" name="purchase" onclick="return confirm('Are you sure you want to make this purchase?');" type="submit" value="Make Purchase">
					</div>
				</form>
			<?php else: ?>
				<p>You do not have enough funds to cover this purchase.</p>
				<p><a href="<?php echo base_url(); ?>account/add-funds"><button class="button">Add Funds</button></a></p>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
