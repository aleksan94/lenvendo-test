<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = [
	"GROUPS" => [
		"PAGINATION_SETTINGS" => array(
			"SORT" => 110,
			"NAME" => GetMessage("BOOKMARK_GROUP_PAGINATION_SETTINGS"),
		),
	],
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
			"add" => [
				"NAME" => GetMessage("BOOKMARK_ITEM_ADD_DESC"),
				"DEFAULT" => "add/",
				"VARIABLES" => [],
			],
		],

		"SHOW_PAGINATION" => [
			"PARENT" => "PAGINATION_SETTINGS",
			"NAME" => GetMessage("BOOKMARK_SHOW_PAGINATION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
		],
		"PAGE_SIZE" => [
			"PARENT" => "PAGINATION_SETTINGS",
			"NAME" => GetMessage("BOOKMARK_PAGINATION_PAGE_SIZE"),
			"TYPE" => "STRING",
			"DEFAULT" => "20",
		],
	],
];