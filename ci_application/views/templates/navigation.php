
<div id="navigation">
	<div id="logo">
		<h1><a href="<?php echo base_url(); ?>"><img alt="Babes for Bitcoin" src="<?php echo base_url(); ?>assets/img/babes-for-bitcoin-logo-small.png"></a></h1>
	</div>
	<ul id="nav-links">
		<li class="nav-home<?php echo $this->uri->segment(1) == '' ? ' nav-active' : ''; ?>"><a href="<?php echo base_url(); ?>">home</a></li>
		<li class="nav-separator">|</li>
		<?php if ($this->_user->user_type != 1): ?>
			<li class="nav-contributors<?php echo $this->uri->segment(1) == 'contributors' ? ' nav-active' : ''; ?>"><a href="<?php echo base_url(); ?>contributors">fans</a></li>
			<li class="nav-separator">|</li>
		<?php endif; ?>
		<?php /* if ($this->_user->user_type != 2): */?>
			<li class="nav-models<?php echo $this->uri->segment(1) == 'models' ? ' nav-active' : ''; ?>"><a href="<?php echo base_url(); ?>models">models</a></li>
			<li class="nav-separator">|</li>
		<?php /*endif; */?>
		<?php /* if ($this->_user->user_type != 2): ?>
			<li class="nav-featured<?php echo $this->uri->segment(1) == 'featured' ? ' nav-active' : ''; ?>"><a href="<?php echo base_url(); ?>featured">featured</a></li>
			<li class="nav-separator">|</li>
		<?php endif; */ ?>
		<?php /* if ($this->_user->user_type != 1): ?>
			<li class="nav-favorites<?php echo $this->uri->segment(1) == 'favorites' ? ' nav-active' : ''; ?>"><a href="<?php echo base_url(); ?>favorites">favorites</a></li>
			<li class="nav-separator">|</li>
		<?php endif; */ ?>
		<li class="nav-messages<?php echo $this->uri->segment(1) == 'messages' ? ' nav-active' : ''; ?><?php echo $this->_user->unread > 0 ? ' nav-messages-unread' : ''; ?>"><a href="<?php echo base_url(); ?>messages">messages<?php echo $this->_user->unread > 0 ? '<span class="unread-count">' . $this->_user->unread . '</span>' : ''; ?></a></li>
		<li class="nav-separator">|</li>
		<?php if ($this->_user->user_type != 2): ?>
			<li class="nav-my-files<?php echo $this->uri->segment(1) == 'my-files' ? ' nav-active' : ''; ?>"><a href="<?php echo base_url(); ?>my-files">my files</a></li>
			<li class="nav-separator">|</li>
			<?php /*
			<li class="nav-wishlist<?php echo $this->uri->segment(1) == 'wishlist' ? ' nav-active' : ''; ?>"><a href="<?php echo base_url(); ?>wishlist">wishlist</a></li>
			<li class="nav-separator">|</li>
			*/ ?>
		<?php endif; ?>
		<li class="nav-upgrade"><a href="<?php echo base_url(); ?>upcoming-features">upcoming features</a></li>
		<li class="nav-separator">|</li>
		<li class="nav-logout"><a href="<?php echo base_url(); ?>account/logout">logout</a></li>
		<?php if ($this->_user->user_type < 3): ?>
			<li class="nav-separator nav-separator-large"></li>
			<li class="nav-funds"><a href="<?php echo base_url(); ?>account/<?php echo $this->_user->user_type == 2 ? 'earnings' : 'add-funds'; ?>">$<?php echo $this->_user->funds_usd; ?> | &#579;<?php echo $this->_user->funds_btc; ?></a></li>
		<?php endif; ?>
	</ul>
	<div id="search">
		<form action="<?php echo base_url(); ?>" id="search-form" method="post">
			<input id="search-all" name="search-all" placeholder="search..." type="text">
			<div class="advanced-search">
				<a href="<?php echo base_url(); ?>search/advanced">advanced search</a>
			</div>
		</form>
	</div>
	<div class="clearfix"></div>
</div>
<img src="<?php echo base_url(); ?>/assets/img/photoset_maint_banner.png" style="margin-left: auto; width: 100%; margin-right: auto; padding: 0px; margin-top: 20px;">
