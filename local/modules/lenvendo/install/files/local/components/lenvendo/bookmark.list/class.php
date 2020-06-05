<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

\CBitrixComponent::includeComponentClass('lenvendo:bookmark');

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

    public function getData($id = false)
    {
        $arData = \CBookmark::getData();

        foreach($arData as $key => $val) {
            $arData[$key]['DETAIL_PAGE_URL'] = str_replace("#ELEMENT_ID#", $val['ID'], $this->arParams['FOLDER'].$this->arParams['URL_TEMPLATES']['item']);
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