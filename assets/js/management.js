$(function() {

	// Split function (used by autocomplete)
	function split(val) {
		return val.split(/,\s*/);
	}

	// Extract Last function (used by autocomplete)
	function extractLast(term) {
		return split(term).pop();
	}

	// Enable tooltips
	$('.jtooltip').tooltip();

	// Enable popovers
	$('.jpopover').popover();

	// Enable calendars on inputs
	$('.datetimepicker').datetimepicker();

	// Convert video and audio tags to MediaElement.js
	$('video, audio').mediaelementplayer(/* Options */);

	// Sortable content for front page models
	$('#featured-order').sortable({
		placeholder: 'well placeholder',
		update: function(event, ui) {
			$('#featured-sort').submit();
		}
	}).disableSelection();

	// Find User Search Box
	$('#search').bind('keydown', function(event) {
		// Don't navigation away from the field on tab when selecting an item
		if (event.keyCode === $.ui.keyCode.TAB && $(this).data('ui-autocomplete').menu.active)
		{
			event.preventDefault();
		}
	}).autocomplete({
		source: function(request, response) {
			$.getJSON('/ajax/users', {
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
			location.href = action + '/' + ui.item.id;
			return false;
		}
	});

	// Tags autocomplete
	$('#tags').bind('keydown', function(event) {
		// Don't navigation away from the field on tab when selecting an item
		if (event.keyCode === $.ui.keyCode.TAB && $(this).data('ui-autocomplete').menu.active)
		{
			event.preventDefault();
		}
	}).autocomplete({
		source: function(request, response) {
			$.getJSON('/ajax/tags', {
				term: extractLast(request.term)
			}, response);
		},
		search: function() {
			// Custom minlength
			var term = extractLast(this.value);
			if (term.length < 2) {
				return false;
			}
		},
		focus: function() {
			// Prevent value inserted on focus
			return false;
		},
		select: function(event, ui) {
			var terms = split(this.value);
			// Remove the current input
			terms.pop();
			// Add the selected item
			terms.push(ui.item.value);
			// Add placeholder to get the comma-and-space at the end
			terms.push('');
			this.value = terms.join(', ');
			return false;
		}
	});

	// Enable pretty upload buttons
	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
		numFiles = input.get(0).files ? input.get(0).files.length : 1,
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
	});

	// Add chosen file on pretty upload buttons
	$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
		var input = $(this).parents('.input-group').find(':text'),
		log = numFiles > 1 ? numFiles + ' files selected' : label;

		if (input.length) {
			input.val(log);
		} else {
			if (log) alert(log);
		}
	});

	// Users List - Filter by User Type
	$('#filter_user_type').change(function() {
		var action   = $('#filter-form').prop('action');
		var type     = $(this).val();
		var approved = $('#hidden-approved').val();
		var disabled = $('#hidden-disabled').val();
		var lockout  = $('#hidden-lockout').val();
		var sort     = $('#hidden-sort').val();
		var dir      = $('#hidden-dir').val();

		location.href = action + '/' + type + '.' + disabled + '.' + lockout + '.' + approved + '/' + sort + '/' + dir;
	});

	// Users List - Filter by Approved
	$('#filter_user_approved').change(function() {
		var action   = $('#filter-form').prop('action');
		var type     = $('#hidden-type').val();
		var approved = $(this).val();
		var disabled = $('#hidden-disabled').val();
		var lockout  = $('#hidden-lockout').val();
		var sort     = $('#hidden-sort').val();
		var dir      = $('#hidden-dir').val();

		location.href = action + '/' + type + '.' + disabled + '.' + lockout + '.' + approved + '/' + sort + '/' + dir;
	});

	// Users List - Filter by Disabled
	$('#filter_disabled').change(function() {
		var action   = $('#filter-form').prop('action');
		var type     = $('#hidden-type').val();
		var approved = $('#hidden-approved').val();
		var disabled = $(this).val();
		var lockout  = $('#hidden-lockout').val();
		var sort     = $('#hidden-sort').val();
		var dir      = $('#hidden-dir').val();

		location.href = action + '/' + type + '.' + disabled + '.' + lockout + '.' + approved + '/' + sort + '/' + dir;
	});

	// Users List - Filter by Disabled
	$('#filter_lockout').change(function() {
		var action   = $('#filter-form').prop('action');
		var type     = $('#hidden-type').val();
		var approved = $('#hidden-approved').val();
		var disabled = $('#hidden-disabled').val();
		var lockout  = $(this).val();
		var sort     = $('#hidden-sort').val();
		var dir      = $('#hidden-dir').val();

		location.href = action + '/' + type + '.' + disabled + '.' + lockout + '.' + approved + '/' + sort + '/' + dir;
	});

	// Assets List - Filter by Asset Type
	$('#filter_asset_type').change(function() {
		var action   = $('#filter-form').prop('action');
		var type     = $(this).val();
		var profile  = $('#hidden-asset-default').val();
		var deleted  = $('#hidden-asset-deleted').val();
		var approved = $('#hidden-asset-approved').val();
		var sort     = $('#hidden-asset-sort').val();
		var dir      = $('#hidden-asset-dir').val();

		location.href = action + '/' + type + '.' + profile + '.' + deleted + '.' + approved + '/' + sort + '/' + dir;
	});

	// Assets List - Filter by Default
	$('#filter_default').change(function() {
		var action   = $('#filter-form').prop('action');
		var type     = $('#hidden-asset-type').val();
		var profile  = $(this).val();
		var deleted  = $('#hidden-asset-deleted').val();
		var approved = $('#hidden-asset-approved').val();
		var sort     = $('#hidden-asset-sort').val();
		var dir      = $('#hidden-asset-dir').val();

		location.href = action + '/' + type + '.' + profile + '.' + deleted + '.' + approved + '/' + sort + '/' + dir;
	});

	// Assets List - Filter by Deleted
	$('#filter_deleted').change(function() {
		var action   = $('#filter-form').prop('action');
		var type     = $('#hidden-asset-type').val();
		var profile  = $('#hidden-asset-default').val();
		var deleted  = $(this).val();
		var approved = $('#hidden-asset-approved').val();
		var sort     = $('#hidden-asset-sort').val();
		var dir      = $('#hidden-asset-dir').val();

		location.href = action + '/' + type + '.' + profile + '.' + deleted + '.' + approved + '/' + sort + '/' + dir;
	});

	// Assets List - Filter by Approved
	$('#filter_approved').change(function() {
		var action   = $('#filter-form').prop('action');
		var type     = $('#hidden-asset-type').val();
		var profile  = $('#hidden-asset-default').val();
		var deleted  = $('#hidden-asset-deleted').val();
		var approved = $(this).val();
		var sort     = $('#hidden-asset-sort').val();
		var dir      = $('#hidden-asset-dir').val();

		location.href = action + '/' + type + '.' + profile + '.' + deleted + '.' + approved + '/' + sort + '/' + dir;
	});

	// Model Payout - Calculate Funds
	function calculate_funds() {
		var selected  = 0;
		var available = parseFloat($('#funds-available').html());
		var remaining = available;
		var funds_id  = 0;
		var funds_usd = 0;

		$('.payout_models').each(function() {
			if (this.checked) {
				funds_id   = $(this).prop('id').substring(13);
				funds_usd  = parseFloat($('#payout_funds' + funds_id).val());
				selected  += funds_usd;
				remaining -= funds_usd;
			}
		});

		$('#funds-selected').html(selected.toFixed(2));
		$('#funds-remaining').html(remaining.toFixed(2));

		if (remaining >= 0) {
			$('#funds-remaining').removeClass('text-danger').addClass('text-success');
		} else {
			$('#funds-remaining').removeClass('text-success').addClass('text-danger');
		}

		if (selected > 0 && remaining >= 0) {
			$('#payout_submit').removeClass('btn-danger').addClass('btn-success');
		} else {
			$('#payout_submit').removeClass('btn-success').addClass('btn-danger');
		}
	}

	// Model Payout - Check All
	$('#model-payout #check-all').change(function() {
		if (this.checked) {
			$('#model-payout .payout_models').prop('checked', true);
			calculate_funds();
		} else {
			$('#model-payout .payout_models').prop('checked', false);
			calculate_funds();
		}
	});

	// Model Payout - Calculate Funds Selected/Remaining
	$('.payout_models').change(function() {
		calculate_funds();
	});

	// Model Payout - Can only submit if funds selected and less then/equal to available
	$('#model-payout').submit(function() {
		var funds_selected  = parseFloat($('#funds-selected').html());
		var funds_available = parseFloat($('#funds-available').html());

		if (funds_selected > funds_available) {
			alert('You do not have enough funds available to cover this payout.');
			return false;
		} else if (funds_selected == 0) {
			alert('You must select at least one model to payout funds to.');
			return false;
		} else {
			return true;
		}
	});

});