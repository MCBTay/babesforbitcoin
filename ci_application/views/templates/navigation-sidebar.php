
			<h3 class="display-name"><?php echo $this->_user->display_name ? $this->_user->display_name : 'User # ' . $this->_user->user_id; ?></h3>
			<?php if ($this->uri->segment(1) == 'upload' && $this->uri->segment(2) == 'public'): ?>
				<a href="<?php echo base_url(); ?>">
			<?php else: ?>
				<a href="<?php echo base_url(); ?>upload/public">
			<?php endif; ?>
			
				<img alt="<?php echo $this->_user->display_name; ?>" src="<?php echo isset($this->_user->default) ? CDN_URL . 'tall-' . strtolower($this->_user->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="385">
			</a>
			<ul class="sidebar-navigation">
				<?php if ($this->_user->user_type == 2): ?>
					<li><a<?php echo $this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'earnings' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>account/earnings">My Earnings</a></li>
					<li><a<?php echo $this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'profile' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>account/profile">Edit | View Profile</a></li>
					<li><a<?php echo $this->uri->segment(1) == 'upload' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>upload">Upload Photos | Videos</a></li>
					<li><a<?php echo $this->uri->segment(1) == 'manage-my-files' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>manage-my-files">Manage My Files</a></li>
					<?php /*
					<li><a<?php echo $this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'referrals' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>account/referrals">Retouch and Referrals</a></li>
					*/ ?>
				<?php endif; ?>
				<?php if ($this->_user->user_type == 1): ?>
					<li><a<?php echo $this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'add-funds' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>account/add-funds">Add Funds</a></li>
					<li><a<?php echo $this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'profile' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>account/profile">Edit | View Profile</a></li>
					<?php /*
					<li class="side-nav-upgrade"><a<?php echo $this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'upgrade' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>account/upgrade">Upgrade<span class="icon"></span></a></li>
					*/ ?>
				<?php endif; ?>
				<li><a<?php echo $this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'preferences' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>account/preferences">Preferences</a></li>
				<?php /*
				<li><a<?php echo $this->uri->segment(1) == 'account' && $this->uri->segment(2) == 'visitors' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>account/visitors">Profile Visitors</a></li>
				<li class="side-nav-spacer"></li>
				<?php if ($this->_user->user_type == 2): ?>
					<li><a href="<?php echo base_url(); ?>favorites">My Favorites</a></li>
				<?php else: ?>
					<li><a href="<?php echo base_url(); ?>wishlist">My Wishlist</a></li>
				<?php endif; ?>
			</ul>
			<div id="wishlist-carousel">
				<div class="jcarousel">
					<ul>
						<li>
							<div class="watermark-wrap">
								<img alt="wishlist photo" src="<?php echo base_url(); ?>assets/img/example-wishlist-sidebar.jpg" width="150" height="150">
								<div class="watermark-hd"></div>
							</div>
							<p>Long text here (# of Photos)</p>
							<a class="button-wishlist" href="#"><button class="button"><span class="jshown">$50 | 0.090&#579;</span><span class="jhidden">Buy</span></button></a>
						</li>
						<li>
							<div class="watermark-wrap">
								<img alt="wishlist photo" src="<?php echo base_url(); ?>assets/img/example-wishlist-sidebar.jpg" width="150" height="150">
								<div class="watermark-hd watermark-hd-video"></div>
							</div>
							<p>Set Title (Length of Film)</p>
							<a class="button-wishlist" href="#"><button class="button"><span class="jshown">$50 | 0.090&#579;</span><span class="jhidden">Buy</span></button></a>
						</li>
						<li>
							<div class="watermark-wrap">
								<img alt="wishlist photo" src="<?php echo base_url(); ?>assets/img/example-wishlist-sidebar.jpg" width="150" height="150">
								<div class="watermark-hd"></div>
							</div>
							<p>Set/Video Title (Length of Film)</p>
							<a class="button-wishlist" href="#"><button class="button"><span class="jshown">$50 | 0.090&#579;</span><span class="jhidden">Buy</span></button></a>
						</li>
						<li>
							<div class="watermark-wrap">
								<img alt="wishlist photo" src="<?php echo base_url(); ?>assets/img/example-wishlist-sidebar.jpg" width="150" height="150">
								<div class="watermark-hd watermark-video"></div>
							</div>
							<p>Set/Video Title (Length of Film)</p>
							<a class="button-wishlist" href="#"><button class="button"><span class="jshown">$50 | 0.090&#579;</span><span class="jhidden">Buy</span></button></a>
						</li>
					</ul>
				</div>
				<a class="carousel-prev" href="javascript:void(0);"></a>
				<a class="carousel-next" href="javascript:void(0);"></a>
			</div>
			*/ ?>
