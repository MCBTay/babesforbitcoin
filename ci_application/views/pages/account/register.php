
<div id="page-login">
	<div class="logo-large">
		<h1><img alt="Babes for Bitcoin" src="<?php echo base_url(); ?>assets/img/babes-for-bitcoin-logo-large.png"></h1>
	</div>
	<div class="login-form">
		<form action="<?php echo base_url(); ?>account/register" method="post">
			<?php if (validation_errors() != ''): ?>
				<div class="alert alert-danger">
					<strong>Warning:</strong> Please fix any errors noted below.
				</div>
			<?php endif; ?>
			<?php if (isset($exists)): ?>
				<div class="alert alert-danger">
					<strong>Warning:</strong> It appears you already have an account. <a href="<?php echo base_url(); ?>account/login">Click here to login.</a>
				</div>
			<?php endif; ?>
			<div class="input-group">
				<label for="user_type">Account Type:</label>
				<select class="input-group-showhide" id="user_type" name="user_type">
					<option value="1"<?php echo set_select('user_type', '1'); ?>>Contributor</option>
					<option value="2"<?php echo set_select('user_type', '2'); ?>>Model</option>
				</select>
				<?php if (form_error('user_type') != ''): ?>
					<div class="form-error">
						<?php echo form_error('user_type'); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="input-group">
				<label for="display_name">Display Name:</label>
				<input id="display_name" maxlength="15" name="display_name" type="text" value="<?php echo set_value('display_name'); ?>">
				<?php if (form_error('display_name') != ''): ?>
					<div class="form-error">
						<?php echo form_error('display_name'); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="input-group">
				<label for="email">Email:</label>
				<input id="email" name="email" type="email" value="<?php echo isset($email) ? $email : ''; ?>">
				<?php if (form_error('email') != ''): ?>
					<div class="form-error">
						<?php echo form_error('email'); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="input-group">
				<label for="password">Password:</label>
				<input id="password" name="password" type="password">
				<?php if (form_error('password') != ''): ?>
					<div class="form-error">
						<?php echo form_error('password'); ?>
					</div>
				<?php endif; ?>
				<div class="form-error">
					<p class="legal" style="margin: 3px 0 0;">Passwords must be at least 8 characters with 1 letter and 1 number.</p>
				</div>
			</div>
			<div class="input-group">
				<label for="confirm-password">Confirm Password:</label>
				<input id="confirm-password" name="confirm-password" type="password">
				<?php if (form_error('confirm-password') != ''): ?>
					<div class="form-error">
						<?php echo form_error('confirm-password'); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="input-group">
				<label for="date_of_birth">Date of Birth:</label>
				<input id="date_of_birth" maxlength="10" name="date_of_birth" placeholder="MM/DD/YYYY" type="text" value="<?php echo set_value('date_of_birth'); ?>">
				<?php if (form_error('date_of_birth') != ''): ?>
					<div class="form-error">
						<?php echo form_error('date_of_birth'); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="input-group input-group-model">
				<label for="accept_btc">Accept Bitcoin:</label>
				<select id="accept_btc" name="accept_btc">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</div>
			<div class="input-group">
				<label style="font-size: 0.875em; height: 24px; padding: 5px; text-align: left; width: 370px;"><input id="agree_terms" name="agree_terms" style="width: auto;" type="checkbox" value="1" <?php echo set_checkbox('agree_terms', '1'); ?>> I have read and agree to the <a href="<?php echo base_url(); ?>account/tos" style="color: #009bea;">terms of service</a>.</label>
				<?php if (form_error('agree_terms') != ''): ?>
					<div class="form-error">
						<?php echo form_error('agree_terms'); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="input-group input-group-submit">
				<input id="register" name="register" type="submit" value="Register">
			</div>
		</form>
	</div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>