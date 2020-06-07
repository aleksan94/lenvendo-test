<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Закладки");
?><?$APPLICATION->IncludeComponent(
	"lenvendo:bookmark", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/bookmark/",
		"SHOW_PAGINATION" => "Y",
		"PAGE_SIZE" => "20",
		"USE_SORT" => "Y",
		"SHOW_EXPORT_TO_EXCEL" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"list" => "index.php",
			"item" => "#ELEMENT_ID#/",
			"add" => "add/",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>