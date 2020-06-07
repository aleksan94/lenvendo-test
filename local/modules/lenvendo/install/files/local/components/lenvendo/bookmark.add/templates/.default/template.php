<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<? $APPLICATION->SetTitle('Добавить закладку'); ?>

<div>
	<div class="mb-2">
		<a href="<?=$arResult['LIST_PAGE_URL']?>"><< Список</a>
	</div>

	<div class="container row d-flex align-items-center">
		<div class="col-3">
			<label for="url"><b>URL:</b></label>
		</div>
		<div class="col-6">
			<input type="text" name="URL" id="url" class="form-control">
		</div>
		<div class="col-3">
			<input type="button" name="add" value="Добавить" class="btn btn-info" onclick="CBookmarkAdd.addUrl.call(this)">
		</div>
	</div>

	<div class="mt-3">
		<div class="container row d-flex align-items-center">
			<div class="col-12">
				<a href="#" id="createPassword" onclick="CBookmarkAdd.togglePasswordBlock.call(this, event)">Задать пароль</a>
			</div>
		</div>

		<div class="mt-2" id="passwordBlock" style="display: none;">
			<div class="container row d-flex align-items-center">
				<div class="col-3">
					<label for="password"><b>Пароль:</b></label>
				</div>
				<div class="col-6">
					<input type="password" name="PASSWORD" id="password" class="form-control">
				</div>
			</div>
			<div class="container row d-flex align-items-center mt-1">
				<div class="col-3">
					<label for="confirmPassword"><b>Подтверждение:</b></label>
				</div>
				<div class="col-6">
					<input type="password" name="CONFIRM_PASSWORD" id="confirmPassword" class="form-control">
				</div>
			</div>
		</div>
	</div>
</div>
