<form action="<?echo $APPLICATION->GetCurPage(); ?>" name="form1">
	<? foreach($_REQUEST as $key => $value): ?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>">
	<? endforeach; ?>

	<?echo bitrix_sessid_post(); ?>

	<input type="hidden" name="lang" value="<?echo LANG ?>">
	<input type="hidden" name="id" value="lenvendo">
	<input type="hidden" name="install" value="Y">

	<input type="hidden" name="step" value="finish">

	<div>
		<i>
			<div>Идёт установка...</div> 
			<div>Процесс может занять какое-то время</div> 
			<div>Пожалуйста подождите</div>
		</i>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		let form = $('form[name="form1"]');
		let formData = new FormData(form[0]);
		formData.set('step', 'ajaxInstall');

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