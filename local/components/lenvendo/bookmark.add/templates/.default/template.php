<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<? $APPLICATION->SetTitle('Добавить закладку'); ?>

<div>
	<div class="mb-2">
		<a href="<?=$arResult['LIST_PAGE_URL']?>"><< Список</a>
	</div>
	<div class="d-flex align-items-center justify-content-between">
		<div>
			<label for="url"><b>URL:</b></label>
		</div>
		<div>
			<input type="text" name="URL" id="url" class="form-control">
		</div>
		<div>
			<input type="button" name="add" value="Добавить" class="btn btn-info" onclick="CBookmarkAdd.addUrl.call(this)">
		</div>
	</div>
</div>
