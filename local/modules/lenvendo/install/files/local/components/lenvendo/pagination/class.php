<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


/**
 * 
 */
class CPagination extends \CBitrixComponent
{
    const DEFAULT_PAGE_KEY = 'page';
    const DEFAULT_MAX_PAGES = 3;

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

    	$this->includeComponentTemplate();
    }

    private function prepareResult()
    {
        $this->arResult = [
            'PAGE_SIZE' => $this->getPageSize(), // число элементов на странице
            'PAGE' => $this->getCurPage(), // номер страницы
            'PAGES_LIST' => $this->getPages(),
            'PAGE_KEY' => $this->getPageKey(),
            'FIRST_PAGE' => $this->getFirstPage(),
            'LAST_PAGE' => $this->getLastPage(),
            'NEXT_PAGE' => $this->getNextPage(),
            'PREV_PAGE' => $this->getPrevPage(),
            'SHOW_END' => $this->arParams['SHOW_END'] !== 'Y',
            'SHOW_NEXT' => $this->arParams['SHOW_NEXT'] !== 'Y',
            //'PAGES_NUM' => $this->getPagesNum(), // количество страниц
        ];
    }

    private function getCurPage()
    {
        if($p = (int)$this->arParams['PAGE_NUM']) 
            $page = $p;
        else if($p = (int)$_REQUEST[$this->getPageKey()])
            $page = $p;
        else 
            $page = 1;

        return $page;
    }

    /**
     * Ключ, по которому будет искаться номер страницы
     * @return [type] [description]
     */
    private function getPageKey()
    {
        return ($k = $this->arParams['PAGE_KEY']) ? $k : self::DEFAULT_PAGE_KEY;
    }

    private function getPagesNum()
    {
        return ceil( $this->arParams['COUNT_ITEMS'] / $this->getPageSize() );
    }

    private function getPageSize()
    {
        return ($s = (int)$this->arParams['PAGE_SIZE']) ? $s : 20;
    }

    private function getMaxPages()
    {
        //return ($m = (int)$this->arParams['MAX_PAGES']) ? $m : self::DEFAULT_MAX_PAGES;
        $maxPages = ($m = (int)$this->arParams['MAX_PAGES']) ? $m : self::DEFAULT_MAX_PAGES;
        $maxPages = ($n = $this->getPagesNum()) < $maxPages ? $n : $maxPages;
        return $maxPages;
    }

    private function getPages()
    {
        $maxPages = $this->getMaxPages();
        $pagesNum = $this->getPagesNum();

        $curPage = $this->getCurPage();

        $cnt = 1;
        $startPage = $endPage = $curPage;
        while($cnt < $maxPages) {
            if($cnt % 2 === 1) {
                $p = $endPage + 1;
                if($p <= $pagesNum)
                    $endPage++;
                else
                    $startPage--;
            }
            else {
                $p = $startPage - 1;
                if($p >= 1) 
                    $startPage--;
                else 
                    $endPage++;
            }

            $cnt++;
        }

        return range($startPage, $endPage);
    }

    public function getFirstPage()
    {
        return $this->getCurPage() != 1 ? 1 : false;
    }

    public function getLastPage()
    {
        return ($p = $this->getPagesNum()) > $this->getCurPage() ? $p : false;
    }

    public function getNextPage()
    {
        return ($p = $this->getCurPage()) < $this->getPagesNum() ? $p + 1 : false;
    }

    public function getPrevPage()
    {
        return ($p = $this->getCurPage()) > 1 ? $p - 1 : false;
    }
}