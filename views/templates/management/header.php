<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Management | <?php echo SITE_TITLE; ?></title>
		<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/css/bootstrap-theme.min.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/js/mediaelement/mediaelementplayer.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/css/management.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body role="document">

		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container container-relative">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo base_url(); ?>management">Management</a>
				</div>
				<?php if (!isset($hide_nav)): ?>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li<?php echo $this->uri->segment(2) == '' ? ' class="active"' : ''; ?>>
								<a href="<?php echo base_url(); ?>management">Dashboard</a>
							</li>
							<li<?php echo $this->uri->segment(2) == 'users' ? ' class="active"' : ''; ?>>
								<a href="<?php echo base_url(); ?>management/users">Users</a>
							</li>
							<li<?php echo $this->uri->segment(2) == 'assets' ? ' class="active"' : ''; ?>>
								<a href="<?php echo base_url(); ?>management/assets">Assets</a>
							</li>
							<?php if ($this->_user->user_type == 4): ?>
								<li class="dropdown<?php echo $this->uri->segment(2) == 'statistics' ? ' active' : ''; ?>">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Statistics <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li<?php echo $this->uri->segment(2) == 'statistics' && $this->uri->segment(3) == '' ? ' class="active"' : ''; ?>>
											<a href="<?php echo base_url(); ?>management/statistics">Users</a>
										</li>
										<li<?php echo $this->uri->segment(2) == 'statistics' && $this->uri->segment(3) == 'site' ? ' class="active"' : ''; ?>>
											<a href="<?php echo base_url(); ?>management/statistics/site">Site</a>
										</li>
									</ul>
								</li>
							<?php endif; ?>
							<li class="dropdown<?php echo $this->uri->segment(2) == 'manage' ? ' active' : ''; ?>">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Management <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li<?php echo $this->uri->segment(2) == 'manage' && $this->uri->segment(3) == 'frontpage' ? ' class="active"' : ''; ?>>
										<a href="<?php echo base_url(); ?>management/manage/frontpage">Front Page</a>
									</li>
									<li<?php echo $this->uri->segment(2) == 'manage' && $this->uri->segment(3) == 'ips' ? ' class="active"' : ''; ?>>
										<a href="<?php echo base_url(); ?>management/manage/ips">Manage IP's</a>
									</li>
									<?php if ($this->_user->user_type == 4): ?>
										<li<?php echo $this->uri->segment(2) == 'manage' && $this->uri->segment(3) == 'payout' ? ' class="active"' : ''; ?>>
											<a href="<?php echo base_url(); ?>management/manage/payout">Model Payout</a>
										</li>
									<?php endif; ?>
								</ul>
							</li>
						</ul>
					</div>
					<div class="navbar-search">
						<form action="<?php echo base_url(); ?>management/users/view" class="form-inline" id="search-form" role="form">
							<input class="form-control" name="search" id="search" placeholder="Find a user" type="text">
						</form>
					</div>
					<div class="text-right navbar-logout">
						<h2><a href="<?php echo base_url(); ?>management/account/logout" class="btn btn-danger">Logout</a></h2>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="container" role="main">
