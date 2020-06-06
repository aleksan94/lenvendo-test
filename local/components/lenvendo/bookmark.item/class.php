<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

\CBitrixComponent::includeComponentClass('lenvendo:bookmark');
\CBitrixComponent::includeComponentClass('lenvendo:bookmark.add');

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

        if(\CBookmarkAdd::isAjax()) {
            $action = \CBookmarkAdd::getAjaxAction();

            if($action === 'deleteBookmark') {
                \Bitrix\Main\Loader::includeModule('iblock');

                $ID = $_REQUEST['ID'];
                $password = $_REQUEST['PASSWORD'];

                if(!$ID) \CBookmarkAdd::ajaxError('Не передан ID закладки');

                $bookmark = \CIBlockElement::GetList([], ['ID' => $ID], false, ['nTopCount' => 1], ['ID', 'PROPERTY_PASSWORD'])->Fetch();
                if(!$bookmark) \CBookmarkAdd::ajaxError('Закладка с ID '.$ID.' не найдена');
                $bookmarkPassword = $bookmark['PROPERTY_PASSWORD_VALUE'];

                if(
                        !$password && !$bookmarkPassword 
                    || md5($password) == $bookmarkPassword) 
                {
                    $blEl = new \CIBlockElement();
                    $res = $blEl->Delete($ID);
                    if(!$res)
                        \CBookmarkAdd::ajaxError($blEl->LAST_ERROR);    
                    else
                        \CBookmarkAdd::ajaxOk(['LIST_PAGE_URL' => $this->arResult['LIST_PAGE_URL']]);
                }
                else 
                    \CBookmarkAdd::ajaxError('Ошибка удаления. Пароли не совпадают');
            }

            die();
        }

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