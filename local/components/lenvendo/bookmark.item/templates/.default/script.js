$(document).ready(function() {
	let modalID = '#bookmarkDeleteModal';
	let modal = $(modalID);

	$('#deleteBookmark').on('click', function(e) {
		e.preventDefault();

		let id = $(this).data('id');
		modal.find('[name="ID"]').remove();
		modal.prepend('<input type="hidden" name="ID" value="'+id+'" />');

		modal.modal('show');
	});

	// перед открытием модалки
	modal.on('show.bs.modal', function(e) {
		$(this).find('[name="PASSWORD"]').val(''); // очищаем поля пароля
	});

	// нажатие на кнопку "Удалить" в модалке
	modal.find('#bookmarkDeleteModal__deleteBtn').on('click', function(e) {
		e.preventDefault();

		let obj = $(this);

		let id = modal.find('[name="ID"]').val();
		let password = modal.find('[name="PASSWORD"]').val();

		$.ajax({
			url: '',
			type: 'POST',
			data: {
				IS_AJAX: 'Y',
				AJAX_ACTION: 'deleteBookmark',
				ID: id,
				PASSWORD: password,
			},
			beforeSend: function() {
				obj.prop('disabled', true);
			},
			success: function(res) {
				obj.prop('disabled', false);

				let isOk = !!res.status && res.status === 'ok';
				let data = !!res.data ? res.data : false;
				let message = !!res.message ? res.message : false;

				if(isOk) {
					alert('Закладка успешно удалена');
					modal.modal('hide');
					modal.modal('dispose');

					let url = !!data && !!data['LIST_PAGE_URL'] && data['LIST_PAGE_URL'].length > 0 ? data['LIST_PAGE_URL'] : false;
					if(!!url)
						window.location.href = url;
				}
				else {
					alert(message);
				}
			}
		});
	});
});