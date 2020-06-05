<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

\CBitrixComponent::includeComponentClass('lenvendo:bookmark');

/**
 * 
 */
class CBookmarkList extends \CBitrixComponent
{
    private $elementID = 0;

	/**
	 * обработка входящих параметров
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

        Extension::load('ui.bootstrap4');

    	$this->includeComponentTemplate();
    }

    public function getData()
    {
        $elementID = (int)$this->arParams['ELEMENT_ID'];
        $arData = \CBookmark::getData($elementID);
        return $arData;
    }

    private function prepareResult()
    {
        $this->arResult = [
            'LIST_PAGE_URL' => $this->arParams['FOLDER'].$this->arParams['URL_TEMPLATES']['list'],
            'ITEM' => $this->getData(),
        ];
    }
}