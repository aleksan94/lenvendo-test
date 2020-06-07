<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$params = [
	
];
$params = array_merge($arResult, $params);

$APPLICATION->IncludeComponent(
	'lenvendo:bookmark.add',
	'',
	$params,
	$component
);