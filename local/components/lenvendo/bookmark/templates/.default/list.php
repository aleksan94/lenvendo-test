<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$params = [
	'SHOW_PAGINATION' => $arParams['SHOW_PAGINATION'],
	'PAGE_SIZE' => $arParams['PAGE_SIZE'],
	'USE_SORT' => $arParams['USE_SORT'],
];
$params = array_merge($arResult, $params);

$APPLICATION->IncludeComponent(
	'lenvendo:bookmark.list',
	'',
	$params,
	$component
);