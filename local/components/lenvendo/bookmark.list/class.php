<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

\CBitrixComponent::includeComponentClass('lenvendo:bookmark');
\CBitrixComponent::includeComponentClass('lenvendo:bookmark.add');

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

        if(\CBookmarkAdd::isAjax()) {
            $action = \CBookmarkAdd::getAjaxAction();

            if($action === 'refreshBookmarkList') {
                global $APPLICATION;

                $APPLICATION->RestartBuffer();
                //$APPLICATION->ShowCSS();
                //$APPLICATION->ShowHeadScripts();
                $this->includeComponentTemplate();
                die();
            }

            die();
        }

        //\CJSCore::Init(["jquery"]);
        Extension::load('ui.bootstrap4');

    	$this->includeComponentTemplate();
    }

    public function getData()
    {
        $arData = \CBookmark::getData(false, $this->getCurPage(), $this->getPageSize(), $this->getSort());

        foreach($arData as $key => $val) {
            $arData[$key]['DETAIL_PAGE_URL'] = str_replace("#ELEMENT_ID#", $val['ID'], $this->arParams['FOLDER'].$this->arParams['URL_TEMPLATES']['item']);
        }

        return $arData;
    }

    private function prepareResult()
    {
        $this->arResult = [
            'ADD_PAGE_URL' => $this->arParams['FOLDER'].$this->arParams['URL_TEMPLATES']['add'],
            'ITEMS' => $this->getData(),
            'COUNT_ITEMS' => \CBookmark::getCountItems(),
            'PAGE_SIZE' => $this->getPageSize(),
            'SORT_KEY' => reset( array_keys( $this->getSort() ) ),
            'SORT' => reset( $this->getSort() ),
        ];
    }

    public function getCurPage()
    {
        $pageKey = $this->getPageKey();
        return ($p = (int)$_GET[$pageKey]) ? $p : 1;
    }

    public function getPageKey()
    {
        return ($k = $this->$arParams['PAGE_KEY']) ? $k : 'page';
    }

    public function getPageSize()
    {
        return ($s = (int)$this->arParams['PAGE_SIZE']) ? $s : 5;
    }

    public function getSort()
    {
        $availableSort = ['ASC', 'DESC'];
        $availableKey = ['DATE_CREATE', 'PROPERTY_URL', 'NAME'];

        $defaultSort = ['DATE_CREATE' => 'DESC'];

        $sort = $_REQUEST['SORT'];

        $key = reset( array_keys($sort) );
        $value = reset( $sort );

        if($key && $value && in_array(strtoupper($key), $availableKey) && in_array(strtoupper($value), $availableSort))
            return [$key => $value];
        else 
            return $defaultSort;
    }
}