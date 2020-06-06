<form action="<?echo $APPLICATION->GetCurPage(); ?>" name="form1">
	<?echo bitrix_sessid_post(); ?>

	<input type="hidden" name="lang" value="<?echo LANG ?>">
	<input type="hidden" name="id" value="lenvendo">
	<input type="hidden" name="install" value="Y">

	<input type="hidden" name="step" value="finish">

	<div>
		<input type="checkbox" name="addExamplePage" id="addExamplePage" value="Y" checked>
		<label for="addExamplePage">Добавить страницу bookmark с примером работы компонентов модуля</label>
	</div>
	<div>
		<input type="checkbox" name="addMenu" id="addMenu" value="Y" checked>
		<label for="addMenu">Добавить в верхнее меню пункт "Закладки"?</label>
	</div>

	<div style="margin-top: 10px;">
		<input type="submit" name="inst" value="Установить">
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		checkBookmarkDisabled();
		$('#addExamplePage').on('change', checkBookmarkDisabled);
	});

	let checkBookmarkDisabled = function()
	{
		let addMenuObj = $('#addMenu');

		let addBookmarkIsChecked = $('#addExamplePage').prop('checked');
		addMenuObj.prop('disabled', !addBookmarkIsChecked);
	}
</script>