<?
include_once($_SERVER['DOCUMENT_ROOT']."/local/vendor/autoload.php");
include_once(__DIR__."/lib/phpQuery/phpQuery.php");

\Bitrix\Main\Loader::registerAutoLoadClasses(
 	'lenvendo',
 	[
 		'\\Lenvendo\\Office\\Excel' => 'classes/office/excel.php',
 	]
);