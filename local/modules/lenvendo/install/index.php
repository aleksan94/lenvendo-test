<?

Class Lenvendo extends \CModule
{
	public $MODULE_ID = "lenvendo";
	public $MODULE_VERSION = "1.0.0";
	public $MODULE_VERSION_DATE = "2020.06.05";
	public $MODULE_NAME = "Lenvendo";
	public $MODULE_DESCRIPTION = "Модуль закладок для тестового задания Lenvando";

	private $ROOT_FOLDER = "";

	const IBLOCK_CODE = 'bookmark';
	const IBLOCK_NAME = 'Закладки';

	function __construct()
	{

	}

	function DoInstall()
	{
		global $APPLICATION, $step;

		if($step === 'ajaxInstallComposer') {
			self::composerRequireInstall();
			die();
		}
		
		if(!$step) {
			\Bitrix\Main\UI\Extension::load('jquery');
			$APPLICATION->IncludeAdminFile("Установка модуля lenvendo", self::getDir()."/step.php");
		}
		else if($step === 'installComposer')
		{
			\Bitrix\Main\UI\Extension::load('jquery');
			$APPLICATION->IncludeAdminFile("Установка модуля lenvendo", self::getDir()."/installComposer.php");
		}
		else if($step === 'finish') {
			// если не отмечен пункт установки примера страницы, то не копируем эти файлы
			$this->copyFiles( $_REQUEST['addExamplePage'] !== 'Y' );
			// установка структуры ИБ
			$this->updateIBlock();
			// добавляем пункт в верхнее меню, если отмечена галочка
			if($_REQUEST['addExamplePage'] === 'Y' && $_REQUEST['addMenu'] === 'Y') 
				$this->addMenu();

			\RegisterModule($this->MODULE_ID);	
		}
	}

	function DoUninstall()
	{
		global $APPLICATION;

		if($_REQUEST['step'] !== 'finish') {
			$APPLICATION->IncludeAdminFile("Удаление модуля lenvendo", self::getDir()."/unstep.php");
		}
		else {
			// удаляем данные инфоблока
			if($_REQUEST['deleteIBlock'] === 'Y')
				$this->deleteIBlock();
			// удаляем файлы компонентов и, если отмечена галочка, директорию bookmark
			$this->removeFolders($_REQUEST['deleteExampleFolder'] === 'Y');			
			// удаляем пункт меню, если установлена галочка
			if($_REQUEST['deleteMenu'])
				$this->deleteMenu();
			
			\UnRegisterModule($this->MODULE_ID);
		}
	}

	/**
	 * Где лежит модуль? В bitrix или local?
	 * @return String | bool
	 */
	private function getRootFolder()
	{
		if(!$this->ROOT_FOLDER) {
			$avaliable = ['bitrix', 'local'];

			$folder = dirname(dirname(dirname(self::getDir())));

			$this->ROOT_FOLDER = in_array(basename($folder), $avaliable) ? $folder : false;
		}

		return $this->ROOT_FOLDER;
	}

	/**
	 * Проверка доступов папок на запись
	 * @return bool
	 */
	private function checkPermission($folder)
	{
		if(!file_exists($folder)) return false;

		$perms = substr(sprintf('%o', fileperms($folder)), -4);
		if($perms !== '0777') 
			return chmod($folder, 0777);
	}

	/**
	 * Проверка и создание недостающих папок
	 * @return bool
	 */
	private function checkFolders()
	{
		$rootFolder = self::getRootFolder();
		$filesFolder = $rootFolder."/modules/".$this->MODULE_ID."/install/files";

		$local = $filesFolder."/local";
		$root = $filesFolder."/root";

		$localFolders = glob($local."/*", GLOB_ONLYDIR);
		$localFolders = array_map(
			function($el) use ($rootFolder, $local) {
				return $rootFolder.str_replace($local, "", $el);
			}, 
			$localFolders
		);

		$docRoot = $_SERVER['DOCUMENT_ROOT'];
		$rootFolders = glob($root."/*", GLOB_ONLYDIR);
		$rootFolders = array_map(
			function($el) use ($docRoot, $root) {
				return $docRoot.str_replace($root, "", $el);
			}, 
			$rootFolders
		);

		$arFolders = array_merge($localFolders, $rootFolders);

		foreach($arFolders as $folder) {
			if(!file_exists($folder)) mkdir($folder, 0777, true);
			else $this->checkPermission($folder);
		}
	}

	private function copyFiles($excludeRoot = false)
	{
		$rootFolder = self::getRootFolder();
		$filesFolder = $rootFolder."/modules/".$this->MODULE_ID."/install/files";

		$local = $filesFolder."/local";
		$root = $filesFolder."/root";

		$this->checkFolders();
		\CopyDirFiles($local, $rootFolder, true, true);
		if(!$excludeRoot) \CopyDirFiles($root, $_SERVER['DOCUMENT_ROOT'], true, true);
	}

	private function createPage()
	{

	}

	private function addMenu()
	{
		$menuFile = "/.top.menu.php";
		$menuItem = [
			self::IBLOCK_NAME, 
			"/".self::IBLOCK_CODE."/", 
			Array(), 
			Array(), 
			"" 
		];
		$siteID = 's1';

		self::AddMenuItem($menuFile, $menuItem, $siteID);
	}

	private function deleteMenu()
	{
		$menuFile = "/.top.menu.php";
		$menuLink = '/bookmark/';
		$siteID = 's1';

		self::DeleteMenuItem($menuFile, $menuLink, $siteID);
	}

	private function AddMenuItem($menuFile, $menuItem, $siteID, $pos = -1) {
	    if (\CModule::IncludeModule("fileman")) {
	        $arResult = \CFileMan::GetMenuArray($_SERVER['DOCUMENT_ROOT'].$menuFile);
	        $arMenuItems = $arResult["aMenuLinks"];
	        $menuTemplate = $arResult["sMenuTemplate"];

	        $bFound = false;
	        foreach($arMenuItems as $item) {
	            if($item[1] == $menuItem[1]) {
	                $bFound = true;
	                break;
	            }
	        }

	        if(!$bFound) {
	            if($pos < 0 || $pos >= count($arMenuItems)) 
	                $arMenuItems[] = $menuItem;
	            else {
	                for($i = count($arMenuItems); $i > $pos; $i--)
	                    $arMenuItems[$i] = $arMenuItems[$i - 1];

	                $arMenuItems[$pos] = $menuItem;
	            }

	            \CFileMan::SaveMenu(array($siteID, $menuFile), $arMenuItems, $menuTemplate);
	        }
	    }
	}

	private function DeleteMenuItem($menuFile, $menuLink, $siteID) {
	    if (\CModule::IncludeModule("fileman")) {
	        $arResult = \CFileMan::GetMenuArray($_SERVER['DOCUMENT_ROOT'].$menuFile);
	        $arMenuItems = $arResult["aMenuLinks"];
	        $menuTemplate = $arResult["sMenuTemplate"];

	        foreach($arMenuItems as $key => $item) {
	            if($item[1] == $menuLink) unset($arMenuItems[$key]);
	        }

	        \CFileMan::SaveMenu(array($siteID, $menuFile), $arMenuItems, $menuTemplate);
	    }
	}

	public function checkExampleMenuExists()
	{
		\CModule::IncludeModule("fileman");

		$menu = $_SERVER['DOCUMENT_ROOT']."/.top.menu.php";
		$arResult = \CFileMan::GetMenuArray($menu);
		$aMenuLinks = $arResult['aMenuLinks'];
		$links = array_column($aMenuLinks, 1);

		return in_array('/'.self::IBLOCK_CODE.'/', $links);
	}

	private function updateIBlock()
	{
		\Bitrix\Main\Loader::includeModule('iblock');

		$iblockTypeID = \CIBlockType::GetList([], ["=ID" => self::IBLOCK_CODE])->Fetch()['ID'];

	   	if(!$iblockTypeID) {
	      	// создаём нужный тип, если нет
	      	$arFields = [
				"ID" => self::IBLOCK_CODE,
				"SECTIONS" => "N",
				"IN_RSS" => "N",
				"SORT" => 10,
				"LANG" => [
	            	"ru" => [
	            		"NAME" => self::IBLOCK_NAME
	            	]
	            ]
	        ];

	      	$obBlocktype = new \CIBlockType();
	      	$iblockTypeID = $obBlocktype->Add($arFields);
	    }

	    // если существует тип ИБ, создаем сам ИБ
	    if($iblockTypeID) {
			$oBlock = new \CIBlock();

			$iblockID = $oBlock::GetList([], ['CODE' => self::IBLOCK_CODE])->Fetch()['ID'];

			// добавляем/обновляем ИБ
			$arFields = [
				'ACTIVE' => 'Y',
				'NAME' => self::IBLOCK_NAME,
				'CODE' => self::IBLOCK_CODE,
				'IBLOCK_TYPE_ID' => $iblockTypeID,
				'SITE_ID' => 's1'
			];
			if(!$iblockID) {
				$iblockID = $oBlock->Add($arFields);
			}
			else {
				$oBlock->Update($iblockID, $arFields);
			}

			if($iblockID) {
				// права на запись
				\CIBlock::SetPermission($IBLOCK_ID, ["1"=>"X", "2"=>"W"]);

				// добавляем/обновляем св-ва
				$oBlockProperty = new \CIBlockProperty();

				$arProperties = [];
				$res = $oBlockProperty::GetList([], ['IBLOCK_ID' => $iblockID]);
				while($row = $res->Fetch()) {
					$arProperties[$row['ID']] = $row;
				}

				$arCodes = array_combine(array_column($arProperties, 'ID'), array_column($arProperties, 'CODE'));

				$arUpdateProperties = [
					[
						'NAME' => 'favicon',
						'CODE' => 'FAVICON',
					],
					[
						'NAME' => 'URL страницы',
						'CODE' => 'URL',
					],
					[
						'NAME' => 'Заголовок страницы',
						'CODE' => 'META_TITLE',
					],
					[
						'NAME' => 'META Description',
						'CODE' => 'META_DESCRIPTION',
					],
					[
						'NAME' => 'META Keywords',
						'CODE' => 'META_KEYWORDS',
					],
					[
						'NAME' => 'Пароль',
						'CODE' => 'PASSWORD',
					],
				];

				foreach($arUpdateProperties as $prop) {
					$code = $prop['CODE'];
					$name = $prop['NAME'];
					$type = $prop['TYPE'] ? $prop['TYPE'] : 'S';

					$arFields = [
						'ACTIVE' => 'Y',
						'IBLOCK_ID' => $iblockID,
						'PROPERTY_TYPE' => $type,
						'CODE' => $code,
						'NAME' => $name,
					];

					if($propID = array_search($code, $arCodes)) {
						$oBlockProperty->Update($propID, $arFields);
					}
					else {
						$oBlockProperty->Add($arFields);	
					}
				}
			}
	    }
	}

	private function getDir()
	{
		return str_replace("\\", "/", __DIR__);
	}

	private function removeFolders($deleteExample = true)
	{
		$rootFolder = self::getRootFolder();

		$arFolders = [
			$rootFolder."/components/".$this->MODULE_ID,
		];
		if($deleteExample)
			$arFolders[] = $_SERVER['DOCUMENT_ROOT']."/".self::IBLOCK_CODE;

		foreach($arFolders as $folder) {
			\Bitrix\Main\IO\Directory::deleteDirectory($folder);
		}
	}

	private function deleteIBlock()
	{
		\Bitrix\Main\Loader::includeModule('iblock');

		$iblockID = \CIBlock::GetList([], ['CODE' => self::IBLOCK_CODE])->Fetch()['ID'];

		if($iblockID) 
			\CIBlock::Delete($iblockID);
	}

	private function composerRequireInstall()
	{		
		$res = exec('php '.self::getDir().'/composer.phar require -n phpoffice/phpspreadsheet -d '.$_SERVER['DOCUMENT_ROOT'].'/local');
	}

	private function composerRequireUninstall()
	{
		exec('php '.self::getDir().'/composer.phar remove -n phpoffice/phpspreadsheet -d '.$_SERVER['DOCUMENT_ROOT'].'/local');	
	}
}
