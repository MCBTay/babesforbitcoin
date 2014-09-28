
<div id="page-login">
	<div class="logo-large">
		<h1><img alt="Babes for Bitcoin" src="<?php echo base_url(); ?>assets/img/babes-for-bitcoin-logo-large.png"></h1>
	</div>
	<div class="login-form">
		<form action="<?php echo base_url(); ?>account/login" method="post">
			<?php if (validation_errors() != ''): ?>
				<div class="alert alert-danger">
					<strong>Warning:</strong> Please enter a valid email and password.
				</div>
			<?php endif; ?>
			<?php if (isset($error)): ?>
				<div class="alert alert-danger">
					<strong>Warning:</strong> <?php echo $error; ?>
				</div>
			<?php endif; ?>
			<div class="input-group">
				<label for="email">Email:</label>
				<input id="email" name="login-email" type="email" value="<?php echo set_value('login-email');; ?>">
			</div>
			<div class="input-group">
				<label for="password">Password:</label>
				<input id="password" name="login-password" type="password">
			</div>
			<div class="input-group input-group-submit">
				<p class="forgot-password"><a href="<?php echo base_url(); ?>account/forgot">Forgot Password</a></p>
				<input id="login" name="login" type="submit" value="Login">
			</div>
		</form>
	</div>
	<div class="login-navigation">
                <a href="<?php echo base_url(); ?>account/faq">FAQ</a>
                &nbsp;|&nbsp;
                <a href="<?php echo base_url(); ?>account/preview">Preview</a>
                &nbsp;|&nbsp;
                <?php if ($this->uri->segment(2) == 'login'): ?>
                        <a class="highlight" href="<?php echo base_url(); ?>account/register">Sign Up</a>
                <?php else: ?>
                        <a href="<?php echo base_url(); ?>account/login">Login</a>
                <?php endif; ?>
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
