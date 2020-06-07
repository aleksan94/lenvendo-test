$(document).ready(function() {
	$('body').on('click', '.bookmark-list__sort', function() {
		let key = $(this).data('key');
		let value = $(this).hasClass('asc') ? 'desc' : 'asc';

		let data = {
			IS_AJAX: 'Y',
			AJAX_ACTION: 'refreshBookmarkList',
			SORT: {}
		};
		data['SORT'][key] = value;

		$.ajax({
			url: '',
			type: 'POST',
			data: data,
			success: function(res) {
				$('.bookmark-list').empty().append(res);
			}
		});
	});
});

const BookmarkList = {
	exportToExcel: function() {
		/*$.ajax({
			url: '',
			type: 'POST',
			data: {
				IS_AJAX: 'Y',
				AJAX_ACTION: 'exportToExcel',
			},
		});*/
		$.post('', { IS_AJAX: 'Y', AJAX_ACTION: 'exportToExcel' });
	}
}