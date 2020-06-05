<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<? $APPLICATION->SetTitle(''); ?>

<div class="mb-2">
	<a href="<?=$arResult['LIST_PAGE_URL']?>"><< Список</a>
</div>

<h1><?=$arResult['ITEM']['NAME']?></h1>

<div class="bookmark-item">
	<div class="bookmark-item__row d-flex">
		<div>Дата добавления</div>
		<div><?=$arResult['ITEM']['DATE_CREATE']?></div>
	</div>

	<div class="bookmark-item__row d-flex">
		<div>Favicon</div>
		<div><a href="<?=$arResult['ITEM']['FAVICON']?>" target="_blank"><img src="<?=$arResult['ITEM']['FAVICON']?>" width="16" height="16"></a></div>
	</div>

	<div class="bookmark-item__row d-flex">
		<div>URL страницы</div>
		<div><a href="<?=$arResult['ITEM']['URL']?>"><?=$arResult['ITEM']['URL']?></a></div>
	</div>

	<div class="bookmark-item__row d-flex">
		<div>Заголовок страницы</div>
		<div><?=$arResult['ITEM']['META_TITLE']?></div>
	</div>

	<div class="bookmark-item__row d-flex">
		<div>META Description</div>
		<div><?=$arResult['ITEM']['META_DESCRIPTION']?></div>
	</div>

	<div class="bookmark-item__row d-flex">
		<div>META Keywords</div>
		<div><?=$arResult['ITEM']['META_KEYWORDS']?></div>
	</div>
</div>