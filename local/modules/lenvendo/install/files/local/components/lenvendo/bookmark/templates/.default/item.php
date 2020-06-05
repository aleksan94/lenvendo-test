<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
	'lenvendo:bookmark.item',
	'',
	[
		'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID']
	],
	$component
);