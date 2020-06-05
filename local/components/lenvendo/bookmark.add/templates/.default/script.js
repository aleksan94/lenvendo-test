const CBookmarkAdd = 
{
	addUrl: function()
	{
		let obj = $(this);

		let url = $('input[name="URL"]').val();

		if(url.length > 0) {
			$.ajax({
				url: '',
				type: 'POST',
				data: {
					IS_AJAX: 'Y',
					AJAX_ACTION: 'addUrl',
					URL: url
				},
				beforeSend: function() {
					obj.attr('disabled', true);
				},
				success: function(res) {
					let isOk = !!res.status && res.status === 'ok';
					let data = !!res.data ? res.data : false;
					let message = !!res.message ? res.message : false;

					if(isOk) {
						console.log(res);
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
	}
}