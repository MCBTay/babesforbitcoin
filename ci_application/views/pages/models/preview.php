
<div id="page-login" class="page-preview">
    <div id="page-faq">
        <div class="logo-large">
            <h1>
                <a href="<?php echo base_url(); ?>account/login">
                    <img alt="Babes for Bitcoin" src="<?php echo base_url(); ?>assets/img/babes-for-bitcoin-logo-tagline-large.png">
                </a>
            </h1>
        </div>
        <a class="preview-banner-link" style="display:block;" href="<?php echo base_url(); ?>account/register">
            <div id="preview-banner" class="bottom">Sign up to see more of <?php echo $model->display_name; ?></div>
        </a>
        <div class="public-photos">
            <?php foreach ($public as $key => $asset): ?>
                <div class="panel-photo">
                    <a class="fancybox" rel="public" href="<?php echo base_url(); ?>account/register">
                        <div class="watermark-wrap">
                            <img alt="<?php echo $asset->asset_title; ?>" src="<?php echo !empty($asset->filename) ? CDN_URL . 'sml-' . strtolower($asset->filename) : base_url() . 'assets/img/no-photo.png'; ?>">
                            <?php if ($asset->asset_hd): ?>
                                <div class="watermark-hd"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <?php if (!$public): ?>
                <p>No public photos found.</p>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <div class="panel">
            <div class="panel-title">
                <h2>About Me</h2>
            </div>
            <div class="panel-body">
                <div class="panel-box panel-box-borderless">
                    <?php if (!empty($model->profile)): ?>
                        <?php echo nl2br(htmlspecialchars($model->profile)); ?>
                    <?php else: ?>
                        This user does not have a profile set yet.
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <a id="backtomodels" style="display:block;" href="<?php echo base_url(); ?>account/preview">
            <div>Back to Models</div>
        </a>
    </div>
</div>