
		<div class="page-header">
			<h2>Login</h2>
		</div>
		<?php if (validation_errors() != ''): ?>
			<div class="alert alert-danger">
				<span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp; <strong>Warning:</strong> Please enter a valid email and password.
			</div>
		<?php endif; ?>
		<?php if (isset($error)): ?>
			<div class="alert alert-danger">
				<span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp; <strong>Warning:</strong> <?php echo $error; ?>
			</div>
		<?php endif; ?>
		<form action="<?php echo base_url(); ?>management/account" method="post" role="form">
			<div class="form-group">
				<label for="login-email">Email</label>
				<input class="form-control" id="login-email" name="login-email" type="email" value="<?php echo set_value('login-email', ''); ?>">
			</div>
			<div class="form-group">
				<label for="login-password">Password</label>
				<input class="form-control" id="login-password" name="login-password" type="password">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-large btn-success">Login</button>
			</div>
		</form>
