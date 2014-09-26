<html>
	<head>
		<title>New Photo<?php echo $photos == 1 ? '' : 's'; ?> | <?php echo SITE_TITLE; ?></title>
	</head>
	<body>
		<p style="font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 16px;">You have received <?php echo $photos; ?> new photo<?php echo $photos == 1 ? '' : 's'; ?> to your account at <a href="<?php echo base_url(); ?>">BabesForBitcoin.com</a> from <?php echo $display_name; ?></p>
		<p style="font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10px;">If you wish to stop receiving these notifications, please <a href="<?php echo base_url(); ?>">click here</a>.</p>
	</body>
</html>