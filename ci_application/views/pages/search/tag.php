
<div id="page-models-profile">
	<div class="content-wrapper">
		<div class="content-left content-left-small">
			<div class="copy"><h1>&nbsp;</h1></div>
			<ul class="sidebar-navigation" style="font-size: 0.8em;">
				<?php if ($user_type != 2): ?>
					<li><a<?php echo $category == 'models' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>search/tag/<?php echo $tag_id; ?>">Models [<?php echo count($models); ?>]</a></li>
				<?php endif; ?>
				<?php if ($user_type != 1): ?>
					<li><a<?php echo $category == 'contributors' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>search/tag/<?php echo $tag_id; ?>/contributors">Contributors [<?php echo count($contributors); ?>]</a></li>
				<?php endif; ?>
				<?php if ($user_type != 2): ?>
					<li><a<?php echo $category == 'assets' ? ' class="active"' : ''; ?> href="<?php echo base_url(); ?>search/tag/<?php echo $tag_id; ?>/assets">Assets [<?php echo count($assets); ?>]</a></li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="content-right content-right-large copy">
			<?php $this->load->view('pages/search/tag_' . $category); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
