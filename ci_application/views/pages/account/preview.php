
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
    <div id="popup">
        <p>This website contains age-restricted materials.</p>
        <p>If you are under the age of 18 years, or under the age of majority in the location from where you are accessing this website you do not have authorization or permission to enter this website or access any of its materials.</p>
        <p>If you are over the age of 18 years or over the age of majority in the location from where you are accessing this website by entering the website you hereby agree to comply with all the <a href="<?php echo base_url(); ?>account/tos">TERMS AND CONDITIONS</a>.</p>
        <p>You also acknowledge and agree that you are not offended by nudity and explicit depictions of sexual activity.</p>
        <p>By clicking on the "Enter" button, and by entering this website you agree with all the above and certify under penalty of perjury that you are an adult.</p>
        <a id="accept" style="display:block;" onclick="hide_popup()">
            <div>I agree, enter.</div>
        </a>
        <a id="decline" style="display:block;" href="<?php echo base_url(); ?>account/login">
            <div>I disagree, exit.</div>
        </a>
    </div>
</div>
<script type="text/javascript">
    function show_popup()
    {
        document.getElementById('popup').style.display = 'block';
        $('#page-faq').css({ opacity: 0.5 });
    }

    function hide_popup()
    {
        document.getElementById('popup').style.display = 'none';
        $('#page-faq').css({ opacity: 1.0 });
    }

    window.onload = show_popup;
</script>