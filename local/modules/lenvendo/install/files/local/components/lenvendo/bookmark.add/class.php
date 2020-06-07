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
        $this->prepareResult();

        if($this->isAjax()) {
            $action = $this->getAjaxAction();

            if($action === 'addUrl') $this->addUrl();

            die();
        }

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
        \Bitrix\Main\Loader::includeModule('iblock');

        $url = $this->prepareUrl($_REQUEST['URL']);

        if($this->checkUrlExists($url)) $this->ajaxError('Указанный URL уже существует в БД');

        // если переданы пароли
        if(isset($_REQUEST['PASSWORD']) && isset($_REQUEST['CONFIRM_PASSWORD'])) {
            if($_REQUEST['PASSWORD'] !== $_REQUEST['CONFIRM_PASSWORD']) $this->ajaxError('Пароли не совпадают');
            $password = md5($_REQUEST['PASSWORD']);
        }

        $arPageData = $this->getPageData($url);
        if(!$arPageData) $this->ajaxError("Содержимое страницы $url не получено");

        $arFields = [
            'IBLOCK_ID' => \CBookmark::getIBlockID(),
            'ACTIVE' => 'Y',
            'NAME' => $arPageData['TITLE'], // МОЖЕТ НЕ БЫТЬ TITLE !!! надо поправить
            'PROPERTY_VALUES' => [
                'URL' => $url,
                'FAVICON' => $arPageData['FAVICON'],
                'META_DESCRIPTION' => $arPageData['DESCRIPTION'],
                'META_KEYWORDS' => $arPageData['KEYWORDS'],
                'PASSWORD' => $password,
            ]
        ];

        $blEl = new \CIBlockElement();
        if($id = $blEl->Add($arFields)) {
            $this->ajaxOk(['DETAIL_PAGE_URL' => $this->getDetailPageUrl($id)]);            
        }
        else $this->ajaxError($blEl->LAST_ERROR);
    }

    public function prepareUrl($url)
    {
        $clearUrl = trim( trim( preg_replace("/^.+?\:\/\//", '', $url) ), '/');
        return "https://".$clearUrl;
    }

    private function getDetailPageUrl($id)
    {
        return $id ? str_replace("#ELEMENT_ID#", $id, $this->arParams['FOLDER'].$this->arParams['URL_TEMPLATES']['item']) : false;
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
        \Bitrix\Main\Loader::includeModule('lenvendo');

        $html = file_get_contents($url);
        if(!$html) return false;

        // phpQuery DOM init
        \phpQuery::newDocument($html);
        // charset        
        $charset = pq('meta[charset]"')->attr('charset');
        // если кодировка не UTF-8, то конвертируем
        if($charset && strpos(strtolower($charset), 'utf') === false) {
            $html = iconv($charset, 'UTF-8', $html);
            \phpQuery::newDocument($html);
        }
        // favicon
        $favicon = pq('link[rel*="icon"]')->attr('href');
        if(substr($favicon, 0, 1) === '/' && substr($favicon, 1, 1) !== '/')
            $favicon = $url."/".trim($favicon, '/');
        // title
        $title = pq('title')->text();
        // description
        $description = pq('meta[name="description"]')->attr('content');
        // keywords
        $keywords = pq('meta[name="keywords"]')->attr('content');

        $arData = [
            'FAVICON' => $favicon,
            'TITLE' => $title,
            'DESCRIPTION' => $description,
            'KEYWORDS' => $keywords,
        ];

        return $arData;


        die();

        // favicon
        preg_match("/\<link .*?href=\"(.+?)\" .*?rel=\".*?icon.*?\"/", $content, $matches);
        $favicon = $matches[1];
        if(preg_match("/.*[\<\>\,\=!\\\].*/", $favicon)) $favicon = false;
        if(!$favicon) {
            preg_match("/\<link .*?rel=\".*?icon.*?\" .*?href=\"(.+?)\"/", $content, $matches);
            $favicon = trim( trim($matches[1]), '/');
            if(preg_match("/.*[\<\>\,\=!\\\].*/", $favicon)) $favicon = false;
        }
        if($favicon && !preg_match("/^https?\:\/\//", $favicon))
            $favicon = $url."/".$favicon;

        // title
        preg_match("/\<title\>(.+?)\<\/title\>/", $content, $matches);
        $title = $matches[1];

        // description
        preg_match("/<meta .*name=\"description\" .*content=\"(.+?)\"/", $content, $matches);
        $description = $matches[1];

        // keywords
        preg_match("/<meta .*name=\"keywords\" .*content=\"(.+?)\"/", $content, $matches);
        $keywords = $matches[1];

        $arData = [
            'FAVICON' => $favicon,
            'TITLE' => $title,
            'DESCRIPTION' => $description,
            'KEYWORDS' => $keywords,
        ];

        // если кодировка отличная от UTF-8 - конвертируем
        if($charset) 
            $arData = array_map(function($el) use ($charset) { return iconv($charset, 'UTF-8', $el); }, $arData);

        return $arData;
    }

    public function isAjax()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST' && $_REQUEST['IS_AJAX'] === 'Y';
    }

    public function getAjaxAction()
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
    public function ajaxOk($data)
    {
        self::ajaxRespone(['status' => 'ok', 'data' => $data]);
    }
    public function ajaxError($message)
    {
        self::ajaxRespone(['status' => 'error', 'message' => $message]);
    }
}