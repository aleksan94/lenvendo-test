<form action="<?echo $APPLICATION->GetCurPage(); ?>" name="form1">
	<?echo bitrix_sessid_post(); ?>

	<input type="hidden" name="lang" value="<?echo LANG ?>">
	<input type="hidden" name="id" value="lenvendo">
	<input type="hidden" name="install" value="Y">

	<input type="hidden" name="step" value="finish">

	<div>
		<div>Идёт установка зависимостей <b>composer</b>...</div>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		let form = $('form[name="form1"]');
		let formData = new FormData(form[0]);
		formData.set('step', 'ajaxInstallComposer');

		$.ajax({
			url: '',
			type: 'POST',
			processData: false,
  			contentType: false,
			data: formData,
			success: function(res) {
				form.submit();
			}
		});
	});
</script>