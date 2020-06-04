<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

\Bitrix\Main\Loader::includeModule('iblock');
\CBitrixComponent::includeComponentClass('lenvendo:bookmark.list');

/**
 * 
 */
class CBookmarkList extends \CBitrixComponent
{
	/**
	 * обработка входящих параметро
	 * @param  array  $arParams [description]
	 * @return array           [description]
	 */
	public function onPrepareComponentParams(array $arParams = []): array
    {
    	return $arParams;
    }	

    /**
     * выполнение компонента
     */
    public function executeComponent()
    {
        $this->prepareResult();

        //\CJSCore::Init(["jquery"]);
        Extension::load('ui.bootstrap4');

    	$this->includeComponentTemplate();
    }

    private function getData()
    {
        $arOrder = [];

        $arFilter = [
            'IBLOCK_CODE' => \CBookmark::IBLOCK_CODE_BOOKMARK,
            'ACTIVE' => 'Y',
        ];

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
            // DETAIL_PAGE_URL
            $row['DETAIL_PAGE_URL'] = str_replace("#ELEMENT_ID#", $row['ID'], $this->arParams['FOLDER'].$this->arParams['URL_TEMPLATES']['item']);

            $arData[] = $row;
        }

        return $arData;
    }

    private function prepareResult()
    {
        $this->arResult = [
            'ITEMS' => $this->getData()
        ];
    }
}