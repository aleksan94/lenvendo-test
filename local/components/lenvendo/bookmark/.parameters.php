<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = [
	"PARAMETERS" => [
		"VARIABLE_ALIASES" => [
			"ELEMENT_ID" => ["NAME" => GetMessage("BOOKMARK_ELEMENT_ID_DESC")],
		],
		"SEF_MODE" => [
			"list" => [
				"NAME" => GetMessage("BOOKMARK_LIST_DESC"),
				"DEFAULT" => "index.php",
				"VARIABLES" => [],
			],
			"item" => [
				"NAME" => GetMessage("BOOKMARK_ITEM_DESC"),
				"DEFAULT" => "#ELEMENT_ID#/",
				"VARIABLES" => ['ELEMENT_ID'],
			],
		],
	],
];