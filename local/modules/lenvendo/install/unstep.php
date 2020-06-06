<form action="<?echo $APPLICATION->GetCurPage(); ?>" name="form1">
	<?echo bitrix_sessid_post(); ?>

	<input type="hidden" name="lang" value="<?echo LANG ?>">
	<input type="hidden" name="id" value="lenvendo">
	<input type="hidden" name="uninstall" value="Y">

	<input type="hidden" name="step" value="finish">

	<div>
		<input type="checkbox" name="deleteIBlock" id="deleteIBlock" value="Y">
		<label for="deleteIBlock">Удалить инфоблок <b>"Закладки"</b> (<i style="color: red;">будут также удалены все сохраненные данные!</i>)?</label>
	</div>

	<? if( file_exists($_SERVER['DOCUMENT_ROOT']."/bookmark") ): ?>
	<div>
		<input type="checkbox" name="deleteExampleFolder" id="deleteExampleFolder" value="Y">
		<label for="deleteExampleFolder">Удалить директорию <b>bookmark</b>?</label>
	</div>
	<? endif; ?>

	<? if(\Lenvendo::checkExampleMenuExists()): ?>
	<div>
		<input type="checkbox" name="deleteMenu" id="deleteMenu" value="Y">
		<label for="deleteMenu">Удалить пункт меню <b>"Закладки"</b>?</label>
	</div>
	<? endif; ?>

	<div style="margin-top: 10px;">
		<input type="submit" name="del" value="Удалить">
	</div>
</form>