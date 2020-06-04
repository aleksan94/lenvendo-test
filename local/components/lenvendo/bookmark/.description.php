<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_BOOKMARK_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_BOOKMARK_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "Y",
	"PATH" => array(
		"ID" => "Lenvendo",
		"CHILD" => array(
			"ID" => "bookmark",
			"NAME" => GetMessage("IBLOCK_BOOKMARK_NAME"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "news_cmpx",
			),
		),
	),
);

?>