<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$params = [
	'SHOW_PAGINATION' => $arParams['SHOW_PAGINATION'],
	'PAGE_SIZE' => $arParams['PAGE_SIZE'],
	'USE_SORT' => $arParams['USE_SORT'],
	'SHOW_EXPORT_TO_EXCEL' => $arParams['SHOW_EXPORT_TO_EXCEL'],
];
$params = array_merge($arResult, $params);

$APPLICATION->IncludeComponent(
	'lenvendo:bookmark.list',
	'',
	$params,
	$component
);