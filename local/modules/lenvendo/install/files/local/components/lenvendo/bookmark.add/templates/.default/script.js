const CBookmarkAdd = 
{
	addUrl: function()
	{
		let obj = $(this);

		let url = $('input[name="URL"]').val();

		let data = {
			IS_AJAX: 'Y',
			AJAX_ACTION: 'addUrl',
			URL: url
		}

		let passwordBlock = $('#passwordBlock');
		let isShow = passwordBlock.is(':visible');
		if(isShow) {
			let password = $('input[name="PASSWORD"]').val();
			let confirmPassword = $('input[name="CONFIRM_PASSWORD"]').val();

			data['PASSWORD'] = password;
			data['CONFIRM_PASSWORD'] = confirmPassword;
		}

		if(url.length > 0) {
			$.ajax({
				url: '',
				type: 'POST',
				data: data,
				beforeSend: function() {
					obj.prop('disabled', true);
				},
				success: function(res) {
					obj.prop('disabled', false);

					let isOk = !!res.status && res.status === 'ok';
					let data = !!res.data ? res.data : false;
					let message = !!res.message ? res.message : false;

					if(isOk) {
						let url = !!data && !!data['DETAIL_PAGE_URL'] && data['DETAIL_PAGE_URL'].length > 0 ? data['DETAIL_PAGE_URL'] : false;
						if(!!url)
							window.location.href = url;
					}
					else {
						alert(message);
					}
				}
			});
		}
		else {
			alert('Необходимо указать URL');
		}
	},

	togglePasswordBlock: function(e)
	{
		e.preventDefault();

		let obj = $(this);

		let passwordBlock = $('#passwordBlock');
		let isShow = passwordBlock.is(':visible');

		if(isShow)
			passwordBlock.hide();
		else
			passwordBlock.show();
	}
}