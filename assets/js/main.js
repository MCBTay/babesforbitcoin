$(function() {

	/*************************
	 * Upload form
	 ************************/

	$('.asset-upload-public').on('change', function() {
		$('#upload-form').ajaxSubmit({
			target:         '.upload-preview',
			url:            '/upload/ajax_upload_public',
			uploadProgress: function(event, position, total, percentComplete) {
				$('.progress-bar').css('width', percentComplete + '%');
			},
			success:        function() {
				$('.progress-bar').css('width', '0%');
				$('.button-file').remove();
				$('.upload-preview img').Jcrop({
					onSelect:    showCoords,
					onChange:    showCoords,
					aspectRatio: 385 / 465,
					minSize:     [210],
					//maxSize:     [385, 465],
					setSelect:   [0, 0, 385, 465]
				});
			},
			resetForm:      true
		});
		return false;
	});

	$('.asset-upload-private').on('change', function() {
		$('#upload-form').ajaxSubmit({
			target:         '.upload-preview-static',
			url:            '/upload/ajax_upload_private',
			uploadProgress: function(event, position, total, percentComplete) {
				$('.progress-bar').css('width', percentComplete + '%');
			},
			success:        function() {
				$('.progress-bar').css('width', '0%');
				$('.button-file').remove();
			},
			resetForm:      true
		});
		return false;
	});

	$('.asset-upload-photoset').on('change', function() {
		$('#upload-form').ajaxSubmit({
			target:         '.upload-preview-static',
			url:            '/upload/ajax_upload_photoset',
			uploadProgress: function(event, position, total, percentComplete) {
				$('.progress-bar').css('width', percentComplete + '%');
			},
			success:        function() {
				$('.progress-bar').css('width', '0%');
				$('.button-file').remove();
			},
			resetForm:      false
		});
		return false;
	});

	$('.asset-upload-photoset-photo').on('change', function() {
		$('#upload-form').ajaxSubmit({
			url:            '/upload/ajax_upload_photoset_photo',
			uploadProgress: function(event, position, total, percentComplete) {
				$('.progress-bar2').css('width', percentComplete + '%');
			},
			success:        function(response) {
				$('.upload-preview-static2').append(response);
				$('.progress-bar2').css('width', '0%');
				$('.button-file-static').reset();
			},
			resetForm:      false
		});
		return false;
	});

	$('.asset-upload-video-photo').on('change', function() {
		$('#upload-form').ajaxSubmit({
			target:         '.upload-preview-static',
			url:            '/upload/ajax_upload_video_photo',
			uploadProgress: function(event, position, total, percentComplete) {
				$('.progress-bar').css('width', percentComplete + '%');
			},
			success:        function() {
				$('.progress-bar').css('width', '0%');
				$('.button-file').remove();
			},
			resetForm:      false
		});
		return false;
	});

	$('.asset-upload-video').on('change', function() {
		$('#upload-form').ajaxSubmit({
			target:         '.upload-preview-static2',
			url:            '/upload/ajax_upload_video',
			uploadProgress: function(event, position, total, percentComplete) {
				$('.progress-bar2').css('width', percentComplete + '%');
			},
			success:        function() {
				$('.progress-bar2').css('width', '0%');
				$('.button-file-static').remove();
			},
			resetForm:      false
		});
		return false;
	});

	$('#save-when-ready').click(function() {
		var uploaded_video = $('#uploaded_video').val();

		if (uploaded_video != undefined) {
			return true;
		} else {
			alert('Please wait for your video to finish processing.\n\nYou may continue using the site by opening the home link in a new tab.');
			return false;
		}
	});

	/*************************
	 * Send a Message form
	 ************************/

	$('.copy').on('submit', '.send_message', function(e) {
		e.preventDefault();

		var that = this;

		var action = $(that).prop('action');

		$.ajax({
			url:      action + 'ajax/send_message',
			dataType: 'json',
			type:     'post',
			data: {
				message:    $('.message', that).val(),
				user_id_to: $('.user_id_to', that).val(),
				parent_id:  $('.parent_id', that).val()
			},
			success:  function(j) {
				if (j.success == true) {
					location.href = action + 'messages';
				} else if (j.special == true) {
					$(that).prepend('<div class="alert alert-danger"><strong>Warning:</strong> Your account must be approved to send messages.</div>');
				} else {
					$(that).prepend('<div class="alert alert-danger"><strong>Warning:</strong> There was a problem sending your message.</div>');
				}
			}
		});
	});

	/*************************
	 * Show messages
	 ************************/

	$('.show-messages').click(function() {
		// Show loading icon
		$('#messages-loader').html('<img alt="Loading" src="/assets/img/ajax-loader.gif"> Loading ...');

		// Change active class
		$('.show-messages').removeClass('active');
		$(this).addClass('active');

		// Remove new tag
		$(this).removeClass('unread');

		$.ajax({
			url:      '/ajax/get_messages',
			dataType: 'json',
			type:     'post',
			data: {
				message_id: $(this).prop('id').substring(8)
			},
			success:  function(j) {
				$('#messages-loader').html(j.html);
			},
			error: function(a, b, c) {
				console.log(a);
				console.log(b);
				console.log(c);
			}
		});
	});

	$('.show-messages').first().trigger('click');

	/*************************
	 * Send to Contributor
	 ************************/

	$('.send-contrib').click(function(e) {
		e.preventDefault();

		var asset_id = $(this).prop('id').substring(12);
		var html     = '<div class="panel-photo">';
		html        += $(this).parent('.panel-photo').html();
		html        += '<a class="button-remove" href="javascript:void(0);" id="remove' + asset_id + '">Remove</a>';
		html        += '<input class="gifts" name="gifts[]" type="hidden" value="' + asset_id + '">';
		html        += '</div>';
		var amount   = $('#send-photos .panel-photo').length + 1;

		if (amount % 5 == 0) {
			html += '<div class="clearfix"></div>';
		}

		$('#send-photos').append(html);
		$('#send-photos .button').remove();
		$('.sub-send-contrib' + asset_id).hide();
		$('#hidden_send').show();
		$(this).hide();
	});

	$('#send-photos').on('click', '.button-remove', function(e) {
		e.preventDefault();

		var asset_id = $(this).prop('id').substring(6);
		var amount   = $('#send-photos .panel-photo').length;

		if (amount == 1) {
			$('#hidden_send').hide();
		}

		$('.sub-send-contrib' + asset_id).show();
		$('#send-contrib' + asset_id).show();
		$(this).parent().remove();
	});

	/*************************
	 * jShowHide
	 ************************/

	$('.copy').on('click', '.jshowhide .jshow button', function(e) {
		e.preventDefault();
		$(this).closest('.jshow').hide();
		$(this).closest('.jshow').siblings('.jhide').slideDown();
		$(this).closest('.jshowhide').siblings('.home-message-delete').hide();
	});

	$('.copy').on('click', '.jshowhide .jhide button.button-cancel', function(e) {
		e.preventDefault();
		$(this).closest('.jhide').slideUp(400, function()
		{
			$(this).closest('.jhide').siblings('.jshow').show();
			$(this).closest('.jshowhide').siblings('.home-message-delete').show();
		});
	});

	$('.input-group-showhide').change(function() {
		var type = $(this).val();

		if (type == 2) {
			$('.input-group-model').show();
		} else {
			$('.input-group-model').hide();
		}
	});

	/*************************
	 * Accordion
	 ************************/

	$('.accordion').accordion();

	$('.accordion-closed').accordion({
		active:      false,
		collapsible: true,
		heightStyle: "content"
	});

	$('#how-can-i-turn-bitcoin-into-usd').click(function() {
		$('#about-bitcoin').accordion('option', 'active', 2);

		$('html, body').animate({
			scrollTop: $('#about-bitcoin').offset().top + (2 * 45)
		});
	});

	/*************************
	 * Add/Remove Tags
	 ************************/

	$('#fetish-tags').on('mouseenter', '.buy-button', function() {
		$('.jshown', this).hide();
		$('.jhidden', this).show();
	}).on('mouseleave', '.buy-button', function() {
		$('.jhidden', this).hide();
		$('.jshown', this).show();
	}).on('click', '.buy-button', function() {
		// Get fetish id
		var fetish_id = $(this).attr('id').substring(7);
		var fetish    = $('.jshown', this).text();

		// Add back to select
		$('#fetishes').append('<option value="' + fetish_id + '">' + fetish + '</option>');
		$('#fetishes option').sort(function(a, b) {
			if (a.innerHTML == 'Choose Tag') {
				return -1;
			} else {
				return (a.innerHTML > b.innerHTML) ? 1 : -1;
			}
		}).appendTo('#fetishes');

		// Remove from list of chosen fetishes
		$(this).remove();

		// Remove from hidden inputs
		$('#form-group-fetishes input[value="' + fetish_id + '"]').remove();
	});

	$('#fetishes').change(function() {
		if (this.value != 0) {
			// Get the chosen tag's title
			var jtitle = $('option[value="' + this.value + '"]', this).text();

			// Append the tag to the list of chosen fetishes
			$('#fetish-tags').append('<a class="buy-button" id="fetish_' + this.value + '" href="javascript:void(0);"><span class="jshown">' + jtitle + '</span><span class="jhidden">Remove Tag</span></a>');

			// Save the hidden input field for this so we can use it on the server side when POSTed
			$('#form-group-fetishes').append('<input name="tags[]" type="hidden" value="' + this.value + '">');

			// Remove chosen tag from the list
			$('option[value="' + this.value + '"]', this).remove();
		}
	});

	/*************************
	 * jCarousel
	 ************************/

	$('.jcarousel').jcarousel({
		wrap: 'both'
	});

	$('.carousel-prev').click(function() {
		$('.jcarousel').jcarousel('scroll', '-=1');
	});

	$('.carousel-next').click(function() {
		$('.jcarousel').jcarousel('scroll', '+=1');
	});

	/*************************
	 * fancyBox
	 ************************/

	$('.fancybox').fancybox({
		nextEffect: 'none',
		prevEffect: 'none',
		beforeShow: function() {
			var alt = this.element.find('img').attr('alt');
			this.inner.find('img').attr('alt', alt);
			this.title = alt;
		}
	});

	$('.open-fancybox').fancybox();

	/*************************
	 * Jcrop
	 ************************/

	$('.upload-preview img').Jcrop({
		onSelect:    showCoords,
		onChange:    showCoords,
		aspectRatio: 385 / 465,
		//minSize:     [385, 465],
		//maxSize:     [385, 465],
		setSelect:   [0, 0, 385, 465]
	});

	/*************************
	 * Choice show/hide
	 ************************/

	$('.choices').change(function() {
		var choice_id  = $(this).prop('id').substring(8);

		// Hide whichever one is already open
		$('.choice-block').hide();

		// Show the current choice
		$('#choice-' + choice_id).show();
	});

	/*************************
	 * Fees
	 ************************/

	$('#amount_card').change(function() {
		var amount = parseFloat($(this).val());

		if (isNaN(amount)) {
			$('#amount_card_fee').val('');
			$('#amount_card_total').val('');
		} else {
			amount    = amount.toFixed(2);
			var fee   = (amount * fee_card).toFixed(2);
			var total = (parseFloat(amount) + parseFloat(fee)).toFixed(2);

			$(this).val(amount);
			$('#amount_card_fee').val(fee);
			$('#amount_card_total').val(total);
		}
	});

	$('#amount_bank').change(function() {
		var amount = parseFloat($(this).val());

		if (isNaN(amount)) {
			$('#amount_bank_fee').val('');
			$('#amount_bank_total').val('');
		} else {
			amount    = amount.toFixed(2);
			var fee   = (amount * fee_bank).toFixed(2);
			var total = (parseFloat(amount) + parseFloat(fee)).toFixed(2);

			$(this).val(amount);
			$('#amount_bank_fee').val(fee);
			$('#amount_bank_total').val(total);
		}
	});

	$('#amount_btc_usd').change(function() {
		var amount = parseFloat($(this).val());

		if (isNaN(amount)) {
			$('#amount_btc_total').val('');
		} else {
			amount    = amount.toFixed(2);
			var fee   = (amount * fee_btc).toFixed(2);
			var total = (parseFloat(amount) + parseFloat(fee)).toFixed(2);
			var btc   = (total / btc_value).toFixed(6);

			$(this).val(amount);
			$('#amount_btc_total').val(btc);
		}
	});

	/*************************
	 * Photos / Videos
	 ************************/

	$('.h1-header-nav-js #show-purchased-photos').click(function() {
		$('.h1-header-nav-js a').removeClass('active');
		$(this).addClass('active');
		$('#purchased-videos').hide();
		$('#purchased-photos').show();
	});

	$('.h1-header-nav-js #show-purchased-videos').click(function() {
		$('.h1-header-nav-js a').removeClass('active');
		$(this).addClass('active');
		$('#purchased-photos').hide();
		$('#purchased-videos').show();
	});

	/*************************
	 * Change View
	 ************************/

	$('.usd-wrapper input').change(function() {
		var usd = $(this).val();
		var btc = (usd / btc_value).toFixed(6);

		$(this).siblings('.calculate-btc').children('.calculate-btc-value').html(btc);
	});

	$('.btc-wrapper input').change(function() {
		var btc = $(this).val();
		if (isNaN(btc)) {
			var usd = (0).toFixed(2);
		} else {
			var usd = ((btc - (btc * fee_convert)) * btc_value).toFixed(2);
		}

		$(this).siblings('.calculate-usd').children('.calculate-usd-value').html(usd);
	});

	/*************************
	 * Change View
	 ************************/

	$('#change-view-link').mouseenter(function()
	{
		$('.change-view-active').hide();
		$('#change-view-hover').show();
	}).mouseleave(function() {
		$('#change-view-hover').hide();
		$('.change-view-active').show();
	}).click(function() {
		var active = $('.change-view-active').attr('id');
		$('.change-view').removeClass('change-view-active');
		if (active == 'change-view-usd') {
			$('#change-view-btc').addClass('change-view-active');
		} else if (active == 'change-view-btc') {
			$('#change-view-individual').addClass('change-view-active');
		} else {
			$('#change-view-usd').addClass('change-view-active');
		}
	});

	/*************************
	 * Search Autocomplete
	 ************************/

	$.widget('custom.catcomplete', $.ui.autocomplete, {
		_renderMenu: function(ul, items) {
			var that = this,
				currentCategory = "";
			$.each(items, function(index, item) {
				if (item.category != currentCategory) {
					ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
					currentCategory = item.category;
				}
				that._renderItemData(ul, item);
			});
		}
	});
	$('#search-all').bind('keydown', function(event) {
		// Don't navigation away from the field on tab when selecting an item
		if (event.keyCode === $.ui.keyCode.TAB && $(this).data('ui-autocomplete').menu.active)
		{
			event.preventDefault();
		}
	}).catcomplete({
		source: function(request, response) {
			$.getJSON('/ajax/all', {
				term: request.term
			}, response);
		},
		search: function() {
			// Custom minlength
			var term = this.value;
			if (term.length < 2) {
				return false;
			}
		},
		focus: function() {
			// Prevent value inserted on focus
			return false;
		},
		select: function(event, ui) {
			this.value = '';
			// Send to user view page
			var action = $('#search-form').prop('action');
			if (ui.item.category == 'Tags') {
				location.href = action + 'search/tag/' + ui.item.id;
			} else if (ui.item.category == 'Contributors') {
				location.href = action + 'contributors/profile/' + ui.item.id;
			} else {
				location.href = action + 'models/profile/' + ui.item.id;
			}
			return false;
		}
	});

});

function showCoords(c) {
	$('#coords-x1').val(c.x);
	$('#coords-y1').val(c.y);
	$('#coords-x2').val(c.x2);
	$('#coords-y2').val(c.y2);
	$('#coords-w').val(c.w);
	$('#coords-h').val(c.h);
}