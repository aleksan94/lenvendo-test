<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$params = [
	'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID']
];
$params = array_merge($arResult, $params);

$APPLICATION->IncludeComponent(
	'lenvendo:bookmark.item',
	'',
	$params,
	$component
);