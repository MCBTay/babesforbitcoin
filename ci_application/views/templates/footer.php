
			</div>
		</div>
		<script>
			var fee_card    = <?php echo FEE_CARD;    ?>;
			var fee_bank    = <?php echo FEE_BANK;    ?>;
			var fee_btc     = <?php echo FEE_BTC;     ?>;
			var fee_convert = <?php echo FEE_CONVERT; ?>;
			var btc_value   = <?php echo $this->cart_model->btc_to_usd(1); ?>;
		</script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.min.js"><\/script>')</script>
		<script src="<?php echo base_url(); ?>assets/js/plugins.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/mediaelement/mediaelement-and-player.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.jcarousel.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.fancybox.pack.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.Jcrop.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.form.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/main.js"></script>

		<script>
			(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='//www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','UA-XXXXX-X');ga('send','pageview');
		</script>
	</body>
</html>