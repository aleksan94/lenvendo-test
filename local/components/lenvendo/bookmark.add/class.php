<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

\CBitrixComponent::includeComponentClass('lenvendo:bookmark');

/**
 * 
 */
class CBookmarkAdd extends \CBitrixComponent
{
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
        if($this->isAjax()) {
            $action = $this->getAjaxAction();

            if($action === 'addUrl') $this->addUrl();

            die();
        }

        $this->prepareResult();

        Extension::load('ui.bootstrap4');

    	$this->includeComponentTemplate();
    }

    private function prepareResult()
    {
        $this->arResult = [
            'LIST_PAGE_URL' => $this->arParams['FOLDER'].$this->arParams['URL_TEMPLATES']['list'],
        ];
    }

    public function addUrl()
    {
        $url = $this->prepareUrl($_REQUEST['URL']);

        if($this->checkUrlExists($url)) $this->ajaxError('Указанный URL уже существует в БД');

        $arPageData = $this->getPageData($url);

        $this->ajaxOk($arPageData);
    }

    public function prepareUrl($url)
    {
        $httpExists = preg_match("/^https?\:\/\//", $url);
        return $httpExists ? $url : "http://".$url;
    }

    private function checkUrlExists($url)
    {
        if(!$url) return false;

        \Bitrix\Main\Loader::includeModule('iblock');

        $arFilter = [
            'IBLOCK_CODE' => \CBookmark::IBLOCK_CODE_BOOKMARK, 
            'ACTIVE' => 'Y', 
            'PROPERTY_URL' => trim($url)
        ];

        return (bool)\CIBlockElement::GetList([], $arFilter, false, ['nTopCount' => 1], ['ID'])->Fetch();
    }

    /**
     * Достаем данные из страницы
     * @param  String $url
     * @return array      
     */
    public function getPageData($url)
    {
        $content = file_get_contents($url);

        // title
        preg_match("/\<title\>(.+?)\<\/title\>/", $content, $matches);
        $title = trim($matches[1]);

        // description
        preg_match("/<meta .*name=\"description\" .*content=\"(.+?)\"/", $content, $matches);
        $description = $matches[1];

        return $description;
    }

    private function isAjax()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST' && $_REQUEST['IS_AJAX'] === 'Y';
    }

    private function getAjaxAction()
    {
        return trim($_REQUEST['AJAX_ACTION']);
    }

    private function ajaxRespone($response)
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        header('Content-Type: application/json;');
        echo json_encode($response);
        die();
    }
    private function ajaxOk($data)
    {
        $this->ajaxRespone(['status' => 'ok', 'data' => $data]);
    }
    private function ajaxError($message)
    {
        $this->ajaxRespone(['status' => 'error', 'message' => $message]);
    }
}