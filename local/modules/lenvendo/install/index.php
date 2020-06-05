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
		$this->copyFiles();
		$this->updateIBlock();
		$this->addMenu();

		\RegisterModule($this->MODULE_ID);
	}

	function DoUninstall()
	{
		$this->removeFolders();
		$this->deleteIBlock();
		\UnRegisterModule($this->MODULE_ID);
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

	private function copyFiles()
	{
		$rootFolder = self::getRootFolder();
		$filesFolder = $rootFolder."/modules/".$this->MODULE_ID."/install/files";

		$local = $filesFolder."/local";
		$root = $filesFolder."/root";

		$this->checkFolders();
		\CopyDirFiles($local, $rootFolder, true, true);
		\CopyDirFiles($root, $_SERVER['DOCUMENT_ROOT'], true, true);
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

			// добавляем/обновляем св-ва
			if($iblockID) {
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

	private function removeFolders()
	{
		$rootFolder = self::getRootFolder();

		$arFolders = [
			$rootFolder."/components/".$this->MODULE_ID,
			$_SERVER['DOCUMENT_ROOT']."/".self::IBLOCK_CODE,
		];

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
}
