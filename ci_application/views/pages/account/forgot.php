
<div id="page-login">
	<div class="logo-large">
		<h1><img alt="Babes for Bitcoin" src="<?php echo base_url(); ?>assets/img/babes-for-bitcoin-logo-large.png"></h1>
	</div>
	<div class="login-form">
		<form action="<?php echo base_url(); ?>account/forgot" method="post">
			<h2 class="normal">Reset Your Password</h2>
			<?php if (validation_errors() != ''): ?>
				<div class="alert alert-danger">
					<strong>Warning:</strong> Please enter a valid email.
				</div>
			<?php endif; ?>
			<?php if (isset($success)): ?>
				<div class="alert alert-danger">
					<strong>Success:</strong> Check your email for a link to reset your password.
				</div>
			<?php endif; ?>
			<div class="input-group">
				<label for="email">Email:</label>
				<input id="email" name="login-email" type="email" value="<?php echo set_value('login-email');; ?>">
			</div>
			<div class="input-group input-group-submit">
				<input id="login" name="login" type="submit" value="Reset">
			</div>
		</form>
	</div>
	<div class="asset-stats">
		<dl>
			<dt><?php echo $models; ?></dt>
				<dd>Models</dd>
			<dt><?php echo $photos; ?></dt>
				<dd>Photos</dd>
			<dt><?php echo $videos; ?></dt>
				<dd>Videos</dd>
		</dl>
		<div class="clearfix"></div>
	</div>
</div>
