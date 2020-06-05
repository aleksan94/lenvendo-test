<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\Loader::includeModule('iblock');

/**
 * 
 */
class CBookmark extends \CBitrixComponent
{
	// символьный код ИБ закладки
	const IBLOCK_CODE_BOOKMARK = 'bookmark';

	// пути по умолчанию для ЧПУ
	private $arDefaultUrlTemplates404 = [
	    'list' => 'index.php',
	    'item' => '#ELEMENT_ID#/',
        'add' => 'add/',
	];
	// псевдонимы по умолчанию ля ЧПУ
	private $arDefaultVariableAliases404 = [];

	// псевдонимы в режиме НЕ ЧПУ
	private $arDefaultVariableAliases = [];

	// списки переменных
	private $arComponentVariables = [
		'ELEMENT_ID'
	];

	// результирующие шаблоны путей
	private $arUrlTemplates = [];

	// псевдонимы переменных
	private $arVariableAliases = [];

	// переменные
	private $arVariables = [];

	// выводимая страницы
	private $componentPage = '';

	/**
	 * обработка входящих параметров
	 * @param  array  $arParams [description]
	 * @return array           [description]
	 */
	public function onPrepareComponentParams(array $arParams = []): array
    {
    	// для пустых урлов ставим index.php
    	$arParams['SEF_URL_TEMPLATES'] = array_map(function($el) { return empty($el) ? 'index.php' : $el; }, $arParams['SEF_URL_TEMPLATES']);
    	return $arParams;
    }	

    /**
     * выполнение компонента
     */
    public function executeComponent()
    {
    	$this	->	prepareVariableAliases()
    			->	prepareUrlTemplates()
    			->	initComponentVariables()
    			->	prepareComponentPage()
    			->	prepareResult()
    			->	includeComponentTemplateWithPage();
    }

    /**
     * Проверка режима ЧПУ
     * @return boolean
     */
    private function isSefMode()
    {
    	return $this->arParams['SEF_MODE'] === 'Y';
    }

    /**
     * Подготовка псевдонимов переменных
     * @return CBookmark
     */
    private function prepareVariableAliases(): \CBookmark
    {
    	$arVariableAliases = \CComponentEngine::MakeComponentVariableAliases(
			$this->isSefMode() ? $this->arDefaultVariableAliases404 : $this->arDefaultVariableAliases, 
			$this->arParams["VARIABLE_ALIASES"]
		);

    	$this->arVariableAliases = $arVariableAliases;

		return $this;
    }

    /**
     * Подготовка шаблонов путей
     * @return CBookmark
     */
    private function prepareUrlTemplates(): \CBookmark
    {
    	global $APPLICATION;

    	if($this->isSefMode()) {
    		$arUrlTemplates = \CComponentEngine::MakeComponentUrlTemplates(
			    $this->arDefaultUrlTemplates404, 
			    $this->arParams['SEF_URL_TEMPLATES']
			);
    	}
    	else {
    		$arVariableAliases = $this->arVariableAliases;
    		$arUrlTemplates = [
    			"list" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
				"item" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["ELEMENT_ID"]."=#ELEMENT_ID#"),
                "add" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?add=Y"),
    		];
    	}

    	$this->arUrlTemplates = $arUrlTemplates;

    	return $this;
    }    

    /**
     * Получение страницы выводимой компонента
     * @return CBookmark
     */
    protected function prepareComponentPage(): \CBookmark
    {
    	if($this->isSefMode()) {
    		$componentPage = \CComponentEngine::ParseComponentPath(
			    $this->arParams['SEF_FOLDER'],
			    $this->arUrlTemplates,
			    $this->arVariables
			);
    	}
    	else {
    		if(isset($this->arVariables["ELEMENT_ID"]) && intval($this->arVariables["ELEMENT_ID"]) > 0)
				$componentPage = 'item';
			else 
				$componentPage = 'list';
    	}

    	$this->componentPage = $componentPage;

    	return $this;
    }

    /**
     * Подготовка переменных
     * @return CBookmark
     */
    private function initComponentVariables(): \CBookmark
    {
    	\CComponentEngine::initComponentVariables($this->componentPage, $this->arComponentVariables, $this->arVariableAliases, $this->arVariables);
    	return $this;
    }

    /**
     * Формирования результирующего массива
     * @return CBookmark
     */
    private function prepareResult(): \CBookmark
    {
    	$this->arResult = [
			"FOLDER" => $this->isSefMode() ? $this->arParams['SEF_FOLDER'] : '',
			"URL_TEMPLATES" => $this->arUrlTemplates,
			"VARIABLES" => $this->arVariables,
			"ALIASES" => $this->arVariableAliases
    	];

    	return $this;
    }

    /**
     * Подключение шаблона компонента
     * @return CBookmark
     */
    private function includeComponentTemplateWithPage(): \CBookmark
    {
    	$this->includeComponentTemplate($this->componentPage);
    	return $this;
    }

    public function getData($id = false)
    {
        $arOrder = [];

        $arFilter = [
            'IBLOCK_CODE' => self::IBLOCK_CODE_BOOKMARK,
            'ACTIVE' => 'Y',
        ];
        if($id) $arFilter['ID'] = $id;

        $arSelect = [
            'ID',
            'NAME',
            'DATE_CREATE',
            'PROPERTY_FAVICON',
            'PROPERTY_URL',
            'PROPERTY_META_TITLE',
            'PROPERTY_META_KEYWORDS',
            'PROPERTY_META_DESCRIPTION',
        ];

        $arData = [];
        $res = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while($row = $res->Fetch()) {
            // убираем лишние поля
            $row = array_filter(
                $row, 
                function($key) { 
                    return !preg_match("/^PROPERTY\_.+\_VALUE\_ID$/i", $key); 
                }, 
                ARRAY_FILTER_USE_KEY
            );
            // переформировываем ключи
            $row = array_combine(
                array_map(
                    function($key) {
                        preg_match("/^PROPERTY\_(.+)\_VALUE$/i", $key, $matches);
                        return $matches[1] ? $matches[1] : $key;
                    },
                    array_keys($row),
                    $row
                ), 
                $row
            );

            $arData[] = $row;
        }

        return $id ? reset($arData) : $arData;
    }
}