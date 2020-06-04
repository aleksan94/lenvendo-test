<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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
    	$this->includeComponentTemplate();
    }
}