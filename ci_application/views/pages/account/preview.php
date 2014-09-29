
<div id="page-login" class="page-preview">
	<div id="page-faq">
		<div class="logo-large">
			<h1>
                <a href="<?php echo base_url(); ?>account/login">
                    <img alt="Babes for Bitcoin" src="<?php echo base_url(); ?>assets/img/babes-for-bitcoin-logo-tagline-large.png">
                </a>
            </h1>
		</div>
        <div id="preview-banner" class="top">Newest Models</div>
        <div class="content-center content-center-large copy">
            <?php if ($models): ?>
                <?php $count = 1; ?>
                <?php foreach ($models as $model): ?>
                    <div class="panel-photo <?php if ($count % 5 == 1) { echo 'first'; } ?>">
                        <div class="watermark-wrap">
                            <a href="<?php echo base_url(); ?>models/preview/<?php echo $model->user_id; ?>"><img alt="<?php echo isset($model->default) ? $model->default->filename : ''; ?>" src="<?php echo isset($model->default) ? CDN_URL . 'tall-' . strtolower($model->default->filename) : base_url() . 'assets/img/no-photo.png'; ?>" width="170" height="205"></a>
                            <?php if ($model->user_hd): ?>
                                <div class="watermark-hd"></div>
                            <?php endif; ?>
                        </div>
                        <div class="panel-photo-<?php echo $model->online ? 'online' : 'offline'; ?>"><?php echo $model->display_name; ?></div>
                    </div>
                    <?php $count++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <a class="preview-banner-link" style="display:block;" href="<?php echo base_url(); ?>account/register">
            <div id="preview-banner" class="bottom">Sign up is free, click to join!</div>
        </a>
	</div>
</div>
